<?php

namespace App\Http\Controllers;

use App\Models\FacturaHistorial as ModelsFacturaHistorial;
use Illuminate\Http\Request;

class FacturaHistorial extends Controller
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
        $facturaEstado = 1; // Activo
        
        if(!is_null($request['estado'])) $facturaEstado = $request['estado'];
        
        // dd($facturaEstado);
        $facturas =  ModelsFacturaHistorial::where('estado',$facturaEstado)->get();
        
        if(count($facturas) > 0){
            foreach ($facturas as $key => $factura) {
                $factura->user;
                $factura->cliente;
                $factura->factura_detalle;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
