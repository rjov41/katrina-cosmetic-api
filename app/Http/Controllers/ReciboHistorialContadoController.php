<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\ReciboHistorialContado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReciboHistorialContadoController extends Controller
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

        if(!is_null($request['estado'])) $parametros[] = ["estado", $request['estado']];
        // if(!is_null($request['recibo_cerrado'])) $parametros[] = ["recibo_cerrado", $request['recibo_cerrado']];


        // dd($facturaEstado);
        $recibos =  ReciboHistorialContado::where($parametros)->get();

        if(count($recibos) > 0){
            foreach ($recibos as $recibo) {
                $recibo->recibo->user;
                $recibo->factura->cliente;
            }

            $response = $recibos;
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
        $response = [];
        $status = 400;

        if(is_numeric($id)){
            $recibo =  ReciboHistorialContado::find($id);

            if($recibo){

                DB::beginTransaction();
                try {
                    $reciboDelete = $recibo->update([
                        'estado' => 0,
                    ]);

                    if($reciboDelete){
                        $abono = Factura::where('id',$recibo->factura_id)->first();
                        $abono->update([
                            'estado' => 0,
                        ]);

                        $response[] = 'El recibo fue eliminado con exito.';
                        $status = 200;
                    }else{
                        $response[] = 'Error al eliminar el recibo.';
                    }

                    DB::commit();
                    // DB::rollback();
                } catch (Exception $e) {
                    DB::rollback();
                    // print_r(json_encode($e));
                    return response()->json(["mensaje" => json_encode($e)], 400);
                }

            }else{
                $response[] = "El recibo no existe.";
            }

        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);

    }
}
