<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
{
    public function store(Request $request)
    {
        $validateTemplate = Validator::make($request->all(),[
          'name' => 'required|string',
          'description' => 'required|string',
          'id_dependence' => 'required|integer',
          'status' => 'required|boolean'
        ]);

        if($validateTemplate->fails()) {
          return response()->json(['status' => '500', 'errors' => $validateTemplate->errors()], 500);
        }

        $template = Template::create($validateTemplate->getData());

        return response()->json(['status' => '201','data' => $template], 201);
    }

    public function listTemplates()
    {
      $result = [];
      foreach (Template::join('Dependences','templates.id_dependence','=','dependences.id')
        ->select('templates.id', 'templates.name', 'templates.description', 'templates.status', 'dependences.id as dependence_id', 'dependences.description as dependence_name', 'dependences.status as dependence_status')
        ->get() as $template) {
          $result[] = [
            'id' => $template->id,
            'name' => $template->name,
            'description' => $template->description,
            'status' => $template->status,
            'dependency' => [
              'id' => $template->dependence_id,
              'name' => $template->dependence_name,
              'status' => $template->dependence_status
            ]
          ];
        }

      return response()->json($result);
    }

    public function listTemplate($id)
    {
        $template = Template::find($id);

        if($template){
            return response($template);
        }

        return response(['message' => 'No existe la plantilla con el id '. $id], 404);
    }

    public function changeStatus($id) {
        $template = Template::where('id', $id)->first();

        if(!$template){
            return response()->json(['data'=>'No existe la plantilla'], 404);
        }

        $status = 0;

        if($template->status == 0) {
            $status = 1;
        }

        $template->status = $status;
        $template->save();

        return response()
                    ->json(['data' => 'El estado de la plantilla cambio', 'template_status' => $status], 200);
    }

    public function update(Request $request, $id)
    {
        $template = Template::where('id', $id)->first();

        if(!$template){
            return response()->json(['data'=>'No existe la plantilla'], 404);
        }

        $validateTemplate = Validator::make($request->all(),[
            'name' => 'string',
            'description' => 'string',
            'id_dependence' => 'integer',
            'status' => 'boolean'
        ]);

        if($validateTemplate->fails()) {
            return response()->json(['data'=>$validateTemplate->errors()], 500);
        }

        $request->name ? $template->name = $request->name: false;
        $request->description ? $template->description = $request->description : false;
        $request->id_dependence ? $template->id_dependence = $request->id_dependence: false;
        $request->status ? $template->status = $request->status: false;
        $template->save();

        return response()
                    ->json(['data' => $template, 'message' => "Plantilla Actualizada"], 200);
    }
}
