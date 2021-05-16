<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Excel;
use App\Models\Template;
use App\Models\Detail;

class ScheduleController extends Controller
{
    public function import(Request $request, Schedule $schedule)
    {
        $validateSchedule = Validator::make($request->all(),[
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            // 'implementation_date' => 'required|date',
            'id_user' => 'required|integer',
            'id_template' => 'required|integer',
            'status' => 'required|boolean',
            'import_file' => 'required|mimes:xlsx,xlx,xls'
        ]);

        if($validateSchedule->fails()) {
            return response()->json(['status'=>'500','data'=>$validateSchedule->errors()], 500);
        }

        $file = $request->file('import_file');
        $name = $file->getClientOriginalName();
        $filename = pathinfo($name, PATHINFO_FILENAME);
        $template = Template::where('id', $request->id_template)->first();

        if ($filename <> $template->name) {
            return response()->json(['status'=>'404','message'=>'El nombre del archivo es diferente al de la plantilla'], 404);
        }

        $details = Detail::where('id_template', $template->id)->get();

        foreach ($details as $detail) {
            $detailArr [] = $detail->column_name;
            $data_typeArr [] = $detail->data_type;
        }

        $data = Excel::toArray('', $file);
        $headers = $data[0][0];
        $queryArray;

        unset($data[0][0]);

        $columnsData = $data[0];

        foreach ($columnsData as $row) {
          for ($i=0; $i < count($headers); $i++) {
              // $arr [] = array(
              //   "$headers[$i]" => $row
              // );

              // return $arr;
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
              return response()->json(['status' => '400', 'message' => 'Las columnas ' .$headers .' son diferentes a las definidas en el detalle de la plantilla' . $detailArr], 400);
        }



        // $path = $file->storeAs('storage/uploads', $filename, 'public');

        // return response()->json(['status' => '201', 'message' => 'Guardado correctamente']);

        // $schedule = Schedule::create($validateSchedule->getData());

        return response()->json(['status' => '201', 'data' => $schedule]);
        $plantilla = Template::where('id', $programacion->id_template)->first();
    }
}
