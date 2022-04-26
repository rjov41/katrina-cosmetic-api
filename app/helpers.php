<?php

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Factura_Detalle;

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
                $detalle->status = 0;    // si la cantidad es menor o igual a 0 desactivo el producto de la factura
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
        $factura->factura_detalle;

        if(count($factura->factura_detalle)>0){
            $total = 0;

            foreach ($factura->factura_detalle as $productoDetalle) {
                // $total +=  $productoDetalle["precio_unidad"] * $productoDetalle["cantidad"];
                $total +=  $productoDetalle["precio"];
            }

            $factura->monto = $total;
            $factura->saldo_restante = $total;
            $factura->update();

            validarStatusPagadoGlobal($factura->cliente_id);

        }
    }

    function AsignarPrecioPorUnidadGlobal(){
        $facturas = Factura::all();

        foreach ($facturas  as $key => $factura) {
            $factura->factura_detalle;
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
