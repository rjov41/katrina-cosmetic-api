<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\MetaHistorial;
use App\Models\MetaRecuperacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListadosPaginasController extends Controller
{
    public function facturasList(Request $request)
    {
        $response = [];
        $status = 200;
        // $facturaEstado = 1; // Activo
        $parametros = [];

        if ($request['roleName'] == "vendedor") { // si es vendedor solo devuelvo sus facturas
            // vendedor
            // supervisor
            // administrador

            $parametros[] = ["user_id", $request['userId']];
        }

        if (!is_null($request['estado'])) $parametros[] = ["status", $request['estado']];
        if (!is_null($request['tipo_venta'])) $parametros[] = ["tipo_venta", $request['tipo_venta']];
        if (!is_null($request['status_pagado'])) $parametros[] = ["status_pagado", $request['status_pagado']];
        if (!is_null($request['status_entrega'])) $parametros[] = ["entregado", $request['status_entrega']];
        if (!is_null($request['despachado'])) $parametros[] = ["despachado", $request['despachado']];
        if (!is_null($request['created_at'])) {
            $created_at = Carbon::parse($request['created_at']);
            $parametros[] = ["created_at", '>=', $created_at . " 00:00:00"];
        }

        // DB::enableQueryLog();
        $facturas =  Factura::where($parametros);

        // ** Filtrado por cliente
        $facturas->when($request['filter'] && !is_numeric($request['filter']), function ($q) use ($request) {
            $clientesId = [];

            $clientes = Cliente::select('id')
                ->orWhere('nombreCompleto', 'LIKE', '%' . $request['filter'] . '%')
                ->orWhere('nombreEmpresa', 'LIKE', '%' . $request['filter'] . '%')
                ->get();

            if (count($clientes) > 0) {
                foreach ($clientes as $cliente) {
                    $clientesId[] = $cliente->id;
                }
            }

            return $q->wherein('cliente_id', $clientesId);
        }); // Fin Filtrado por cliente

        // ** Filtrado por Factura
        $facturas->when($request['filter'] && is_numeric($request['filter']), function ($q) use ($request) {
            return $q->where('id', 'LIKE', '%' . $request['filter'] . '%');
        }); // Fin Filtrado por Factura

        // dd(json_encode($facturas));
        $facturas = $facturas->paginate(15);
        // dd(DB::getQueryLog());

        if (count($facturas) > 0) {
            foreach ($facturas as $factura) {
                $factura->user;
                $factura->cliente->factura_historial;
                $factura->factura_detalle = $factura->factura_detalle()->where([
                    ['estado', '=', 1],
                ])->get();
            }
        }

        $response = $facturas;


        return response()->json($response, $status);
    }

    public function metasHistoricoList(Request $request)
    {
        $response = [];
        $status = 200;
        // $facturaEstado = 1; // Activo
        $parametros = [["estado",1]];


        // if ($request['estado']) $parametros[] = ["estado", $request['estado']];
        if ($request['userId'] && $request['userId'] != 0) $parametros[] = ["user_id", $request['userId']];

        if (empty($request->dateIni)) {
            $dateIni = Carbon::now();
        } else {
            $dateIni = Carbon::parse($request->dateIni);
        }

        if (empty($request->dateFin)) {
            $dateFin = Carbon::now();
        } else {
            $dateFin = Carbon::parse($request->dateFin);
        }

        // DB::enableQueryLog();
        $metas =  MetaHistorial::where($parametros);

        // ** Filtrado por rango de fechas Meta 
        $metas->when($request->allDates && $request->allDates == "false", function ($q) use ($dateIni, $dateFin) {
            return $q->whereBetween('fecha_asignacion', [$dateIni->toDateString() . " 00:00:00",  $dateFin->toDateString() . " 23:59:59"]);
        });

        // ** Filtrado por Meta
        $metas->when($request['filter'] && is_numeric($request['filter']), function ($q) use ($request) {
            return $q->where('id', 'LIKE', '%' . $request['filter'] . '%');
        });

        // dd(json_encode($facturas));
        $metas = $metas->orderBy('fecha_asignacion','desc')->paginate(15);
        // dd(DB::getQueryLog());

        if (count($metas) > 0) {
            foreach ($metas as $meta) {
                $meta->user;
            }
        }

        $response = $metas;


        return response()->json($response, $status);
    }
}
