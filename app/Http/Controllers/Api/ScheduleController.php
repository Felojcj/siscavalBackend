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
            return response()->json(['status'=>'500','data'=>$validateSchedule->errors()], 500);
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
                  return response()->json(['status'=>'400','data'=>$validator->errors()], 400);
              }
          }
        }

        if ($headers <> $detailArr) {
              return response()->json(['status' => '400', 'message' => 'Las columnas son diferentes a las definidas en el detalle de la plantilla'], 400);
        }

        $schedule->implementation_date = Carbon::now();
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

        $user = User::find($request->id_user);

        $schedule = Schedule::create($validateSchedule->getData());

        return response()->json(['status' => '201','data' => $schedule]);
    }
}
