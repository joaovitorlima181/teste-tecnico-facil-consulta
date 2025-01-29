<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultaRequest;
use App\Models\Consulta;

class ConsultaController extends Controller
{
    /**
     * Cria uma nova consulta
     * 
     * @param StoreConsultaRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreConsultaRequest $request) : \Illuminate\Http\JsonResponse
    {
        try {
            $consulta = Consulta::create([
                'medico_id' => $request->medico_id,
                'paciente_id' => $request->paciente_id,
                'data' => $request->data,
                'hora' => $request->hora,
                'descricao' => $request->descricao,
            ]);

            return response()->json(
                [
                    'message' => 'Consulta criada com sucesso',
                    'consulta' => $consulta
                ],
                201
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
