<?php

namespace App\Http\Controllers;

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
            validarStatusPagadoGlobal(1); //cliente perfecto
            // DB::rollback();
            DB::commit();
            return response()->json([
                'mensaje' => "Exito [validarStatusPagadoGlobal]",], 200);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(["mensaje" => "Error en el Script  [validarStatusPagadoGlobal]"], 400);
        }

        return response()->json($response, $status);
    }

}
