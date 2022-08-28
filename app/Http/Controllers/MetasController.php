<?php

namespace App\Http\Controllers;

use App\Models\Meta;
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
            $cliente =  Meta::find($id);

            if ($cliente) {
                $validation = Validator::make($request->all(), [
                    'user_id'        => 'required|numeric',
                    'monto'          => 'numeric|required',
                    'estado'         => 'required|numeric|max:1',
                ]);

                if ($validation->fails()) {
                    $response[] = $validation->errors();
                } else {

                    // dd($request->all());
                    $cliente->update([
                        'user_id' => $request['user_id'],
                        'monto' => $request['monto'],
                        'estado' => $request['estado'],

                    ]);

                    $response = $cliente;
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
}
