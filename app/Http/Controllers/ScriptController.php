<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScriptController extends Controller
{
    public function AsignarPrecioPorUnidadGlobal()
    {
        $response = [];
        $status = 200;

        DB::beginTransaction();
        try {
            AsignarPrecioPorUnidadGlobal();
            // DB::rollback();
            DB::commit();
            return response()->json([
                'mensaje' => "Exito [AsignarPrecioPorUnidadGlobal]",], 200);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(["mensaje" => "Error en el Script  [AsignarPrecioPorUnidadGlobal]"], 400);
        }

        return response()->json($response, $status);
    }

    public function validarStatusPagadoGlobal()
    {
        $response = [];
        $status = 200;

        DB::beginTransaction();
        try {

            $clientes = Cliente::select("*")
                ->where('estado', 1)
                ->get();
            if(count($clientes) > 0){
                // validarStatusPagadoGlobal(1); //cliente perfecto
                foreach ($clientes as $key => $cliente) {
                    // print_r($cliente->id." "."\n");
                    validarStatusPagadoGlobal($cliente->id);

                }
                // DB::rollback();

                DB::commit();
                return response()->json(['mensaje' => "Exito [validarStatusPagadoGlobal]",], 200);
            }

            return response()->json(['mensaje' => "No hay clientes [validarStatusPagadoGlobal]",], 400);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json(["mensaje" => "Error en el Script  [validarStatusPagadoGlobal]"], 400);
        }

        return response()->json($response, $status);
    }

    public function ActualizarPrecioFactura($id)
    {
        $response = [];
        $status = 200;

        DB::beginTransaction();
        try {
            ActualizarPrecioFactura($id); //cliente perfecto
            // DB::rollback();
            DB::commit();
            return response()->json([
                'mensaje' => "Exito [ActualizarPrecioFactura]",], 200);
        } catch (Exception $e) {
            DB::rollback();
            // print_r(json_encode($e));
            return response()->json(["mensaje" => "Error en el Script  [ActualizarPrecioFactura]"], 400);
        }

        return response()->json($response, $status);
    }

}
