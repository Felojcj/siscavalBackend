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
        }

        $data = Excel::toArray('', $file);
        $headers = $data[0][0];
        $queryArray;

        unset($data[0][0]);

        foreach ($data as $row) {
          for ($i=0; $i < count($headers); $i++) {
              // $insertData = $row[$i + 1][$i];
                $arr [] = array(
                    "$headers[$i]" => $headers[$i],
                    ...$row
                    // $insertData
                );
            }

            if ($headers <> $detailArr) {
                return 'No';
            }
        }

        return $arr;

        // $path = $file->storeAs('storage/uploads', $filename, 'public');

        // return response()->json(['status' => '201', 'message' => 'Guardado correctamente']);

        // $schedule = Schedule::create($validateSchedule->getData());

        return response()->json(['status' => '201', 'data' => $schedule]);
        $plantilla = Template::where('id', $programacion->id_template)->first();
    }
}
