<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Excel;
use App\Models\Template;
use App\Models\Detail;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\MessageValidated;

class ScheduleController extends Controller
{
    public function import(Request $request, $id)
    {
        $schedule = Schedule::where('id', $id)->first();

        if(!$schedule){
          return response()->json(['status' => '404', 'data'=>'No existe la programacion']);
        }

        $validateSchedule = Validator::make($request->all(),[
            'import_file' => 'required|mimes:xlsx,xlx,xls',
            'id_template' => 'required|integer',
        ]);

        if($validateSchedule->fails()) {
            return response()->json(['status'=>'500','data'=>$validateSchedule->errors()]);
        }

        $file = $request->file('import_file');
        $name = $file->getClientOriginalName();
        $filename = pathinfo($name, PATHINFO_FILENAME);
        $template = Template::where('id', $request->id_template)->first();

        if ($filename <> $template->name) {
            return response()->json(['status'=>'404','message'=>'El nombre del archivo es diferente al de la plantilla']);
        }

        $details = Detail::where('id_template', $template->id)->get();

        foreach ($details as $detail) {
            $detailArr [] = $detail->column_name;
            $data_typeArr [] = $detail->data_type;
        }

        $data = Excel::toArray('', $file);
        $headers = $data[0][0];

        unset($data[0][0]);
        $columnsData = $data[0];

        foreach ($columnsData as $row) {
          for ($i=0; $i < count($headers); $i++) {
              $validator = Validator::make(
                  array(
                      "$headers[$i]" => $row[$i]
                  ),
                  array(
                      "$headers[$i]" => "required|$data_typeArr[$i]"
                  )
              );
              if($validator->fails()) {
                  return response()->json(['status'=>'400','data'=>$validator->errors()]);
              }
          }
        }

        if ($headers <> $detailArr) {
              return response()->json(['status' => '400', 'message' => 'Las columnas son diferentes a las definidas en el detalle de la plantilla']);
        }

        $schedule->implementation_date = Carbon::now();
        $user = User::find($schedule->id_user);
        $originalname = $file->getClientOriginalName();
        $path = $file->storeAs('public', $originalname);
        $schedule->path = $path;
        $schedule->save();

        Mail::to($user->email)->send(new MessageValidated($user->email));

        return response()->json(['status' => '201','data' => 'Plantilla Validada correctamente']);
    }

    public function store(Request $request)
    {
        $validateSchedule = Validator::make($request->all(),[
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'id_user' => 'required|integer',
            'id_template' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        if($validateSchedule->fails()) {
            return response()->json(['status'=>'500','data'=>$validateSchedule->errors()]);
        }

        $schedule = Schedule::create($validateSchedule->getData());

        return response()->json(['status' => '201','data' => $schedule]);
    }

    public function listSchedules()
    {
        $result = [];

        foreach (Schedule::join('Templates','schedules.id_template','=','templates.id')
          ->select('schedules.id', 'schedules.updated_at', 'schedules.end_date', 'schedules.id_user', 'schedules.implementation_date', 'schedules.path', 'schedules.start_date', 'schedules.status', 'templates.id as id_template', 'templates.name')
          ->orderBy('updated_at', 'desc')
          ->get() as $schedule) {
            $result[] = [
              'id' => $schedule->id,
              'end_date' => $schedule->end_date,
              'id_user' => $schedule->id_user,
              'implementation_date' => $schedule->implementation_date,
              'path' => $schedule->path,
              'start_date' => $schedule->start_date,
              'status' => $schedule->status,
              'template' => [
                'id' => $schedule->id_template,
                'name' => $schedule->name,
              ]
            ];
          }

        return response()->json($result);
    }

    public function listSchedule($id)
    {
        $schedule = Schedule::find($id);

        if($schedule){
            return response($schedule);
        }

        return response(['message'=>'No existe la programacion'], 404);
    }

    public function update(Request $request,$id)
    {
        $schedule = Schedule::where('id',$id)->first();

        if(!$schedule){
            return response()->json(['status'=>'404','data'=>'No existe la programacion']);
        }

        $validateSchedule = Validator::make($request->all(),[
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'id_user' => 'required|integer',
            'id_template' => 'required|integer',
        ]);

        if($validateSchedule->fails()) {
            return response()
                ->json(['status'=>'500','data'=>$validateSchedule->errors()]);
        }

        $request->start_date ? $schedule->start_date = $request->start_date: false;
        $request->end_date ? $schedule->end_date = $request->end_date: false;
        $request->id_user ? $schedule->id_user = $request->id_user : false;
        $request->id_template ? $schedule->id_template = $request->id_template : false;
        $schedule->save();

        return response()
                    ->json(['status' => '200', 'data'=>$schedule,'message' => "Programacion actualizado"]);
    }

    public function changeStatus($id) {
        $schedule = Schedule::where('id',$id)->first();

        if(!$schedule){
            return response()->json(['status'=>'404','data'=>'No existe el valor valido']);
        }

        $status = 0;
        if($schedule->status == 0) {
            $status = 1;
        }

        $schedule->status = $status;
        $schedule->save();

        return response()
                    ->json(['status' => '200','data'=>'Estado de la programacion modificado','schedule_status'=>$status]);
    }

    public function download($id)
    {
        $file = Schedule::findOrFail($id);

        return response()->download(storage_path('app/'.$file->path));
    }
}
