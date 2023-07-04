<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use App\Models\MetaHistorial;
use App\Models\MetaRecuperacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MetasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [];
        $status = 200;
        $parametros = [];

        // $facturaEstado = 1; // Activo

        if (!is_null($request['estado'])) $parametros[] = ["estado", $request['estado']];


        // dd($facturaEstado);
        $facturas =  Meta::where($parametros)->get();

        if (count($facturas) > 0) {
            foreach ($facturas as $factura) {
                $factura->user;
            }

            $response = $facturas;
        }

        return response()->json($response, $status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id'        => 'required|numeric|unique:metas,user_id,'.$request['user_id'],
            'monto'          => 'numeric|required',
            // 'estado'         => 'required|numeric|max:1',
        ]);

        if (!$validation->fails()) {
            $response = array();
            $error = 201;

            $response = Meta::create([
                'user_id' => $request['user_id'],
                'monto' => $request['monto'],
                'estado' => 1,
            ]);
            
            return response()->json($response, $error);
        } else {
            return response()->json([$validation->errors()], 400);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = [];
        $status = 400;
        // $clienteEstado = 1; // Activo

        if (is_numeric($id)) {

            // if(!is_null($request['estado'])) $clienteEstado = $request['estado'];

            // dd($request['estado']);
            $meta =  Meta::where([
                ['id', '=', $id],
            ])->first();



            // $cliente =  Cliente::find($id);
            if ($meta) {
                $meta->user;

                $response = $meta;
                $status = 200;
            } else {
                $response[] = "La meta no existe o fue eliminada.";
            }
        } else {
            $response[] = "El valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = [];
        $status = 400;

        if (is_numeric($id)) {
            $meta =  Meta::find($id);

            if ($meta) {
                $validation = Validator::make($request->all(), [
                    'user_id'        => 'required|numeric',
                    'monto'          => 'numeric|required',
                    'estado'         => 'required|numeric|max:1',
                ]);

                if ($validation->fails()) {
                    $response[] = $validation->errors();
                } else {

                    // dd($request->all());
                    $meta->update([
                        'user_id' => $request['user_id'],
                        'monto' => $request['monto'],
                        'estado' => $request['estado'],

                    ]);

                    $response = $meta;
                    $status = 200;
                }
            } else {
                $response[] = "La meta no existe.";
            }
        } else {
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function editarMetaHistorial(Request $request,$id)
    {
        $response = [];
        $status = 400;

        if(is_numeric($id)){
            $metaHistorial =  MetaHistorial::find($id);

            if($metaHistorial){
                $validation = Validator::make($request->all() ,[
                    'fecha_asignacion'        => 'required|string',
                    'monto_meta'            => 'numeric|required',
                ]);


                if($validation->fails()) {
                    $response[] = $validation->errors();
                } else {
                    $metaHistorialUpdate = $metaHistorial->update([
                        'monto_meta' => $request['monto_meta'],
                        'fecha_asignacion' => $request['fecha_asignacion'],
                    ]);

                    if($metaHistorialUpdate){
                        $response = $metaHistorialUpdate;
                        $status = 200;

                    }else{
                        $response[] = 'Error al modificar los datos.';
                    }
                }

            }else{
                $response[] = "La meta no existe.";
            }

        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }

    public function eliminarMetaHistorial($id){
        $response = [];
        $status = 400;

        if(is_numeric($id)){
            $metaHistorial =  MetaHistorial::find($id);

            if($metaHistorial){
                $metaHistorialDelete = $metaHistorial->update([
                    'estado' => 0,
                ]);

                if($metaHistorialDelete){
                    $response[] = 'La meta fue eliminado con exito.';
                    $status = 200;

                }else{
                    $response[] = 'Error al eliminar el usuario.';
                }

            }else{
                $response[] = "La meta no existe.";
            }

        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }

    public function crearMetaHistorial(Request $request){
        $response = [];
        $status = 400;
        // dd($request["mes"]);
        crearMetaMensual($request["mes"]);
        $response[] = 'La meta fue eliminado con exito.';
        $status = 200;

        return response()->json($response, $status);
    }
}
