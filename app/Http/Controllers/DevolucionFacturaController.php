<?php

namespace App\Http\Controllers;

use App\Models\DevolucionFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DevolucionFacturaController extends Controller
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
        // $facturaEstado = 1; // Activo
        $parametros = [];

        if (!is_null($request['estado'])) $parametros[] = ["estado", $request['estado']];

        // dd($facturaEstado);
        $facturas =  DevolucionFactura::where($parametros)->get();

        // echo "<pre>";
        // print_r (json_encode($facturas));
        // echo "</pre>";

        if (count($facturas) > 0) {
            foreach ($facturas as $key => $factura) {

                $factura->factura;
                $factura->user;
                // $factura->factura_historial;
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
            'factura_id' => 'required|numeric',
            'user_id'            => 'required|numeric',
            'descripcion' => 'string|nullable',
            'estado' => 'required|numeric|max:1',
        ]);
        // dd($request->all());
        // dd($validation->errors());
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        } else {

            $devolucionFactura = DevolucionFactura::create([
                'factura_id' => $request['factura_id'],
                'user_id'    => $request['user_id'],
                'descripcion' => $request['descripcion'],
                'estado'     => $request['estado'],
            ]);

            return response()->json([
                // 'success' => 'Usuario Insertado con exito',
                // 'data' =>[
                'id' => $devolucionFactura->id,
                // ]
            ], 201);
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
        // $productoEstado = 1; // Activo

        if (is_numeric($id)) {

            // if($request->input("estado") != null) $productoEstado = $request->input("estado");
            // dd($productoEstado);

            $devolucionFactura =  DevolucionFactura::where([
                ['id', '=', $id],
                // ['estado', '=', $productoEstado],
            ])->first();


            // $cliente =  Cliente::find($id);
            if ($devolucionFactura) {
                $response = $devolucionFactura;
                $status = 200;
            } else {
                $response[] = "El producto no existe o fue eliminado.";
            }
        } else {
            $response[] = "El Valor de Id debe ser numerico.";
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
            $producto =  DevolucionFactura::find($id);

            if ($producto) {
                $validation = Validator::make($request->all(), [
                    'factura_id' => 'required|numeric',
                    'user_id' => 'required|numeric',
                    'descripcion' => 'string|nullable',
                    'estado' => 'required|numeric|max:1',
                ]);

                if ($validation->fails()) {
                    $response[] = $validation->errors();
                } else {


                    $productoUpdate = $producto->update([
                        'factura_id' => $request['factura_id'],
                        'user_id'    => $request['user_id'],
                        'descripcion' => $request['descripcion'],
                        'estado'     => $request['estado'],
                    ]);


                    if ($productoUpdate) {
                        $response[] = 'la factura fue modificado con exito.';
                        $status = 200;
                    } else {
                        $response[] = 'Error al modificar los datos.';
                    }
                }
            } else {
                $response[] = "La factura no existe.";
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
