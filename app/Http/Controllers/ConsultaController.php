<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultaRequest;
use App\Http\Requests\UpdateConsultaRequest;
use App\Models\Consulta;

class ConsultaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConsultaRequest $request)
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

    /**
     * Display the specified resource.
     */
    public function show(Consulta $consulta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consulta $consulta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConsultaRequest $request, Consulta $consulta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consulta $consulta)
    {
        //
    }
}
