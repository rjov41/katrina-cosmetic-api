<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductosController extends Controller
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
        $productoEstado = 1; // Activo

        // if($request->input("estado") != null) $productoEstado = $request->input("estado");

        // dd($clienteEstado);
        $producto =  Producto::where('estado',$productoEstado)->get();

        if(count($producto) > 0){
            $response[] = $producto;
        }

        return response()->json($producto, $status);
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
        $validation = Validator::make($request->all() ,[
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'stock' => 'required|numeric',
            'precio' => 'required|numeric',
            // 'comision' => 'required|numeric',
            'linea' => 'required|string',
            'descripcion' => 'required|string',
            'estado' => 'required|numeric|max:1',
        ]);
        // dd($request->all());
        // dd($validation->errors());
        if($validation->fails()) {
            return response()->json($validation->errors(), 400);
        } else {

            $user = Producto::create([
                'marca' => $request['marca'],
                'modelo' => $request['modelo'],
                'stock' => $request['stock'],
                'precio' => $request['precio'],
                // 'comision' => $request['comision'],
                'linea' => $request['linea'],
                'descripcion' => $request['descripcion'],
                'estado' => $request['estado'],
            ]);

            return response()->json([
                // 'success' => 'Usuario Insertado con exito',
                // 'data' =>[
                    'id' => $user->id,
                // ]
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $response = [];
        $status = 400;
        // $productoEstado = 1; // Activo

        if(is_numeric($id)){

            // if($request->input("estado") != null) $productoEstado = $request->input("estado");
            // dd($productoEstado);

            $producto =  Producto::where([
                ['id', '=', $id],
            // ['estado', '=', $productoEstado],
            ])->first();


            // $cliente =  Cliente::find($id);
            if($producto){
                $response = $producto;
                $status = 200;

            }else{
                $response[] = "El producto no existe o fue eliminado.";
            }

        }else{
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

        if(is_numeric($id)){
            $producto =  Producto::find($id);

            if($producto){
                $validation = Validator::make($request->all() ,[
                    'marca' => 'required|string',
                    'modelo' => 'required|string',
                    'stock' => 'required|numeric',
                    'precio' => 'required|numeric',
                    // 'comision' => 'required|numeric',
                    'linea' => 'required|string',
                    'descripcion' => 'required|string',
                    'estado' => 'required|numeric|max:1',
                ]);

                if($validation->fails()) {
                    $response[] = $validation->errors();
                } else {


                    $productoUpdate = $producto->update([
                        'marca' => $request['marca'],
                        'modelo' => $request['modelo'],
                        'stock' => $request['stock'],
                        'precio' => $request['precio'],
                        // 'comision' => $request['comision'],
                        'linea' => $request['linea'],
                        'descripcion' => $request['descripcion'],
                        'estado' => $request['estado'],
                    ]);


                    if($productoUpdate){
                        $response[] = 'El producto fue modificado con exito.';
                        $status = 200;

                    }else{
                        $response[] = 'Error al modificar los datos.';
                    }

                }

            }else{
                $response[] = "El producto no existe.";
            }

        }else{
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
        $response = [];
        $status = 400;

        if(is_numeric($id)){
            $producto =  Producto::find($id);
            // dd($producto);
            if(!empty($producto)){
                $productoDelete = $producto->update([
                    'estado' => 0,
                ]);

                if($productoDelete){
                    $response[] = 'El producto fue eliminado con exito.';
                    $status = 200;

                }else{
                    $response[] = 'Error al eliminar el producto.';
                }

            }else{
                $response[] = "El producto no existe.";
            }

        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }
}
