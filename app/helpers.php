<?php

use App\Models\Cliente;
use App\Models\ClientesReactivados;
use App\Models\Factura;
use App\Models\Factura_Detalle;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

function validarStatusPagadoGlobal($clienteID){

    $cliente = Cliente::find($clienteID);

    $cliente->factura = $cliente->facturas()->where([
        ['status', '=', 1],
        ['tipo_venta', '=', 1]  // 1 = Credito | 2 = Contado,
        // ['status_pagado', '=', 0] // 0 = en proceso | 1 = Finalizado,
    ])->get();

    $cliente->factura_historial = $cliente->factura_historial()->where([
        ['estado', '=', 1],
        // ['debitado', '=', 0] // 0 = aun no usado el abono | 1 = ya se uso el abono,
    ])->get();

    $totalAbonos = 0 ;
    if(count($cliente->factura_historial)>0){
        foreach ($cliente->factura_historial as $itemHistorial) {
            $totalAbonos += $itemHistorial["precio"] ;

            $itemHistorial["debitado"] = 1; // coloco como debitado los abonos que ya fuy sumando al acumulador
            $itemHistorial->update();

        }
    }

    if(count($cliente->factura)>0){
        $tieneSaldo = TRUE; // Bandera para saber cuando debo de dejar ajustar el calculo de saldo restante de las facturas

        foreach ($cliente->factura as $factura) {
            if($tieneSaldo){
                // print_r (json_encode( ["monto" => $factura["monto"], "totalAbonos"=>$totalAbonos ]));
                $totalAbonos =  $totalAbonos - $factura["monto"];
                // print_r (json_encode( ["monto" => $factura["monto"], "totalAbonos"=>$totalAbonos ]));
                if($totalAbonos < 0){ // si el precio es mas alto que el total de abonos (dejo la factura abierta y ajusto el saldo_restante)
                    $tieneSaldo = FALSE;
                    $factura["status_pagado"] = 0;
                    $factura["saldo_restante"] = abs($totalAbonos);

                }else{// cierro la factura y el saldo restante lo dejo 0
                    $factura["saldo_restante"] = 0;
                    $factura["status_pagado"] = 1;
                }

            }else{ // si no tiene saldo reinicio la factura
                $factura["saldo_restante"] = $factura["monto"];
                $factura["status_pagado"] = 0;
            }

            $factura->update();
        }
    }

    // print_r (json_encode( ["cliente" => $cliente, "totalAbonos"=>$totalAbonos ]));

}

function debitarAbonosClientes($clienteID){
    $cliente = Cliente::find($clienteID);
    // $cliente = Cliente::find($clienteID);
    // $cliente = $abono->cliente;

    $cliente->factura = $cliente->facturas()->where([
        ['status', '=', 1],
        ['status_pagado', '=', 0] // 0 = en proceso | 1 = Finalizado,
    ])->get();

    $cliente->factura_historial = $cliente->factura_historial()->where([
        ['estado', '=', 1],
        ['debitado', '=', 0] // 0 = aun no usado el abono | 1 = ya se uso el abono,
    ])->get();

    // print_r(json_encode($cliente->factura_historial));
    $totalAbonos = 0 ;
    if(count($cliente->factura_historial)>0){
        foreach ($cliente->factura_historial as $itemHistorial) {
            $totalAbonos += $itemHistorial["precio"] ;

            $itemHistorial["debitado"] = 1; // coloco como debitado los abonos que ya fuy sumando al acumulador
            $itemHistorial->update();

        }
    }

    if(count($cliente->factura)>0){
        $tieneSaldo = TRUE; // Bandera para saber cuando debo de dejar ajustar el calculo de saldo restante de las facturas

        foreach ($cliente->factura as $factura) {
            if($tieneSaldo){
                // 200 - 500 = -300 ajusta el restante
                // 500 - 500 = 0  cierra factura y ajusta restante
                $totalAbonos =  $totalAbonos - $factura["saldo_restante"];

                if($totalAbonos < 0){ // si el precio es mas alto que el total de abonos (dejo la factura abierta y ajusto el saldo_restante)
                    $tieneSaldo = FALSE;
                    $factura["saldo_restante"] = abs($totalAbonos) ;

                }else{// cierro la factura y el saldo restante lo dejo 0
                    $factura["saldo_restante"] = 0;
                    $factura["status_pagado"] = 1;
                }

            }

            $factura->update();
        }
    }

    // print_r (json_encode( ["cliente" => $cliente->factura, "totalAbonos"=>$totalAbonos ]));

}

function calcularDeudaFacturaCliente($clienteID){
    $cliente = Cliente::find($clienteID);
    $data = array(
        "saldo_restante" => 0,
        "totalFactura" => 0
    );

    $cliente->factura = $cliente->facturas()->where([
        ['status', '=', 1], //1 Activo | 0 Inactivo
        ['tipo_venta', '=', 1], // 1 = Credito | 2 = Contado,
        ['status_pagado', '=', 0] // 0 = en proceso | 1 = Finalizado,
    ])->get();

    // print_r (count($cliente->factura));
    if(count($cliente->factura)>0){

        $totalFactura = 0;
        $saldoRestanteFactura = 0;

        foreach ($cliente->factura as $factura) {
            $saldoRestanteFactura += $factura["saldo_restante"];
            $totalFactura += $factura["monto"];
        }

        $data["saldo_restante"] = $saldoRestanteFactura;
        $data["totalFactura"] = $totalFactura;
    }


    return $data;
}

function calcularDeudaFacturasGlobal($clienteID){
    $cliente = Cliente::find($clienteID);

    $cliente->factura = $cliente->facturas()->where([
        ['status', '=', 1],
        ['tipo_venta', '=', 1]  // 1 = Credito | 2 = Contado,
        // ['status_pagado', '=', 0] // 0 = en proceso | 1 = Finalizado,
    ])->get();

    $cliente->factura_historial = $cliente->factura_historial()->where([
        ['estado', '=', 1],
        // ['debitado', '=', 0] // 0 = aun no usado el abono | 1 = ya se uso el abono,
    ])->get();

    $totalAbonos = 0 ;
    if(count($cliente->factura_historial)>0){
        foreach ($cliente->factura_historial as $itemHistorial) {
            $totalAbonos += $itemHistorial["precio"] ;
        }
    }

    if(count($cliente->factura)>0){
        foreach ($cliente->factura as $factura) {
            $totalAbonos =  $totalAbonos - $factura["monto"];
        }
    }

    return number_format($totalAbonos, 2);
}

function actualizarCantidadDetalleProducto($detalleID, $cantidad){
    $detalle = Factura_Detalle::find($detalleID);

    if($detalle){
        if(($detalle->cantidad - $cantidad) <=0){
            $detalle->cantidad = 0;  // si la cantidad es menor o igual a 0 la pongo en 0
            $detalle->estado = 0;    // si la cantidad es menor o igual a 0 desactivo el producto de la factura
        }else{
            $detalle->cantidad = $detalle->cantidad - $cantidad;
            // $total +=  $productoDetalle["precio_unidad"] * $productoDetalle["cantidad"];
            $detalle->precio = $detalle["precio_unidad"] * $detalle->cantidad;
        }

        $detalle->update();

        ActualizarPrecioFactura($detalle->factura_id);
        return true;
    }



    return false;
}


function ActualizarPrecioFactura($factura_id){
    $factura = Factura::find($factura_id);
    $factura_detalle = $factura->factura_detalle()->where([
        ['estado', '=', 1],
    ])->get();

    if(count($factura_detalle)>0){
        $total = 0;

        foreach ($factura_detalle as $productoDetalle) {
            // $total +=  $productoDetalle["precio_unidad"] * $productoDetalle["cantidad"];
            $total +=  $productoDetalle["precio"];
        }

        $factura->monto = $total;
        $factura->saldo_restante = $total;

    }else{ // NO TIENES PRODUCTOS ACTIVOS EN LA FACTURA
        // $factura->monto = 0;
        // $factura->saldo_restante = 0;

        // desactibo la factura para que no se tome en cuenta
        $factura->status = 0;
    }

    // print_r (json_encode($factura));

    $factura->update();

    validarStatusPagadoGlobal($factura->cliente_id);
}

function AsignarPrecioPorUnidadGlobal(){
    $facturas = Factura::all();

    foreach ($facturas  as $key => $factura) {
        $factura->factura_detalle = $factura->factura_detalle()->where([
            ['estado', '=', 1],
        ])->get();
        if(count($factura->factura_detalle)>0){
            $precio_unidad = 0;

            foreach ($factura->factura_detalle as $productoDetalle) {

                $precio_unidad =  $productoDetalle["precio"] / $productoDetalle["cantidad"] ;
                $precio_unidad_format = number_format($precio_unidad, 2);

                $productoDetalle["precio_unidad"] = $precio_unidad_format;
                $productoDetalle->update();
            }
            // $factura->precio_unidad = $precio_unidad;

            ActualizarPrecioFactura($factura->id);
            // validarStatusPagadoGlobal($factura->cliente_id);

        }
    }



}

function devolverStockProducto($detalle_id,$cantidad){
    // print_r(json_encode($detalle_id));
    $detalle = Factura_Detalle::where("id",$detalle_id)->first();
    if($detalle){
        $producto = Producto::where("id",$detalle->producto_id)->first();
        // print_r(json_encode($producto));
        $producto->stock = $producto->stock + $cantidad;
        $producto->estado = 1;
        $producto->update();
        // print_r(json_encode($producto));

        return true;
    }

    return false;
}

function queryEstadoCuenta($cliente_id){
    $response = [
        "estado_cuenta" => [],
    ];

    if(is_numeric($cliente_id)){
        $query = "SELECT
                *
            FROM (
                SELECT
                    c.id AS cliente_id,
                    CONCAT('PED-',f.id ) AS `numero_documento`,
                    'Pedido' as tipo_documento,
                    f.created_at AS fecha,
                    f.fecha_vencimiento AS f_vencimiento,
                f.monto AS credito,
                    '' AS abono
                FROM clientes c
                INNER JOIN facturas f ON f.cliente_id = c.id
                WHERE
                    f.`status` = 1
                    UNION ALL
                SELECT
                    c.id AS cliente_id,
                    CONCAT('REC-', rh.numero ) AS `numero_documento`,
                    'Recibo' as tipo_documento,
                    rh.created_at AS fecha,
                    rh.created_at AS f_vencimiento,
                '' AS credito,
                    fh.precio AS abono
                FROM	clientes c
                INNER JOIN factura_historials fh ON fh.cliente_id = c.id
                INNER JOIN recibo_historials rh ON rh.factura_historial_id = fh.id
                WHERE
                    fh.`estado` = 1
            ) estado_cuenta
            WHERE estado_cuenta.cliente_id = $cliente_id
            ORDER BY estado_cuenta.fecha ASC
        ";

        // if($request->userId != 0){
        //     $query = $query." AND c.user_id = ".$request->userId;
        // }

        $estadoCuenta = DB::select($query);

        if (count($estadoCuenta)>0) {
            $saldo = 0;
            foreach ($estadoCuenta as $operacion) {
                // if(!isset($operacion->saldo)) $operacion->saldo = 0;
                $saldo = ($operacion->credito != "") ? number_format((float)$operacion->credito,2,".","") + number_format((float)$saldo,2,".","")   : number_format((float)$saldo,2,".","") - number_format((float)($operacion->abono),2,".","");

                $operacion->saldo = $saldo;
                // print_r(intval($operacion->credito) + $operacion->saldo ."<br>");
            }
            $response["estado_cuenta"] = $estadoCuenta;
        }

    }

    return $response;

}

function validarReactivacionCliente($user_id, $cliente_id ,$factura_id , $listaInactivos ){

    // print_r($listaInactivos);
    if(count($listaInactivos) > 0){ // si existe en la lista de clientes inactivos registro el dia que se reactivo

        ClientesReactivados::create([
            'user_id'         => $user_id,
            'cliente_id' => $cliente_id,
            'factura_id' => $factura_id,
            'estado' => 1,

        ]); // inserto registro de reactivacion de cliente

    }
}

function carteraQuery($request ){
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

    $facturasStorage = Factura::select("*")
        //->where('tipo_venta', $request->tipo_venta ? $request->tipo_venta : 1) // si envian valor lo tomo, si no por defecto toma credito
        ->where('status_pagado', $request->status_pagado ? $request->status_pagado : 0) // si envian valor lo tomo, si no por defecto asigno por pagar = 0
        ->where('status', 1);

    if(!$request->allDates){
        $facturasStorage = $facturasStorage->whereBetween('created_at', [$dateIni->toDateString()." 00:00:00",  $dateFin->toDateString()." 23:59:59"]);
    }

    if($userId != 0){
        $facturasStorage = $facturasStorage->where('user_id', $userId);
    }

    $facturas = $facturasStorage->get();


    if(count($facturas) > 0){
        $total = 0;
        foreach ($facturas as $factura) {
            $total += $factura->saldo_restante;
            // $total += number_format((float) ($factura->monto),2,".","");
            //$total += number_format((float) ($factura->saldo_restante),2,".","");


            $factura->user;
            $factura->cliente->factura_historial = $factura->cliente->factura_historial()->where([
                ['estado', '=', 1],
            ])->get()->last();
            $factura->factura_detalle = $factura->factura_detalle()->where([
                ['estado', '=', 1],
            ])->get();
        }

        $response["total"]    = $total;
        $response["factura"] = $facturas;
    }

    return $response;
}
