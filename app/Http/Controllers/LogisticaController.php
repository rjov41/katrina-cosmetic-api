<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogisticaController extends Controller
{
    function carteraDate(Request $request)
    {
        $response = [
            'factura' => [],
            'total' => 0,
        ];

        $userId = $request['userId'];
        // "dateIni": "2022-03-15",
        // "dateFin": "2022-03-15",
        if(empty($request->dateIni)){
            $dateIni = Carbon::now();
        }else{
            $dateIni = Carbon::parse($request->dateIni);
        }

        if(empty($request->dateIni)){
            $dateFin = Carbon::now();
        }else{
            $dateFin = Carbon::parse($request->dateFin);
        }

        // DB::enableQueryLog();
        // print_r(json_encode($dateIni->toDateString()." 00:00:00"));

        // $facturasPorFecha = Factura::whereBetween('fecha_factura', [$dateIni, $dateFin])->get();
        $facturas = Factura::select("*")
            ->whereBetween('created_at', [$dateIni->toDateString()." 00:00:00",  $dateFin->toDateString()." 23:59:59"])
            // ->where('created_at',">=", $dateIni->toDateString()." 00:00:00")
            // ->where('created_at',"<=",  $dateFin->toDateString()." 23:59:59")
            ->where('user_id', $userId)
            ->where('status', 1)
            ->get();

        // $query = DB::getQueryLog();
        // dd($query);

        if(count($facturas) > 0){
            $total = 0;
            foreach ($facturas as $factura) {
                $total += $factura->monto;

                $factura->user;
                $factura->cliente->factura_historial;
                $factura->factura_detalle = $factura->factura_detalle()->where([
                    ['estado', '=', 1],
                ])->get();
            }

            $response["total"]    = number_format($total, 2,".","");
            $response["factura"] = $facturas;
        }

        return response()->json($response, 200);
    }

    function reciboDate()
    {
        $response = [];
    }

    function Mora30A60()
    {
        $response = [];
    }

    function Mora60A90()
    {
        $response = [];
    }


    function ventasDAte()
    {
        $response = [];
    }
}
