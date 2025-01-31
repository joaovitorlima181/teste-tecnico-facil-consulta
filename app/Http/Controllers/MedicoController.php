<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicoRequest;
use App\Models\Medico;
use App\Models\Paciente;

class MedicoController extends Controller
{
    /**
     * Lista todos os médicos
     * 
     * @queryParam string nome Nome do médico
     * @queryParam int page Número da página
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $nome = request()->query('nome');
            $nome = str_replace(['dr', 'dra'], '', $nome);

            $page = request()->query('page', 1);

            $medicos = Medico::where('nome', 'like', "%{$nome}%")
                ->orderBy('nome')
                ->paginate(10, ['*'], 'page', $page);

            if ($medicos->isEmpty()) {
                return response()->json(['error' => 'Médico não encontrado'], 404);
            }

            return response()->json($medicos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lista os pacientes de um médico
     * 
     * @param int $medicoId Id do médico
     * @queryParam string nome Nome do paciente
     * @queryParam int page Número da página
     * @queryParam bool apenas-agendadas Se true, retorna apenas os pacientes com consultas agendadas
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function pacientes(int $medicoId) : \Illuminate\Http\JsonResponse
    {
        $page = request()->input('page', 1);
        $nome = request()->input('nome', '');
        $apenasAgendadas = filter_var(request()->query('apenas-agendadas'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        $pacientes = Paciente::with(['consultas' => function ($query) use ($medicoId, $apenasAgendadas, $nome) {
            $query->where('medico_id', $medicoId);

            if ($apenasAgendadas) {
                $query->where('data', '>', now());
            }

        }])
            ->where('nome', 'like', '%' . $nome . '%')
            ->whereHas('consultas', function ($query) use ($medicoId) {
                $query->where('medico_id', $medicoId);
            })
            ->paginate(10, ['*'], 'page', $page);

        if ($pacientes->isEmpty()) {
            return response()->json(['error' => 'Paciente não encontrado'], 404);
        }

        return response()->json([
            'pacientes' => $pacientes
        ]);
    }

    /**
     * Cria um novo médico
     * 
     * @param StoreMedicoRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMedicoRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $medico = Medico::create([
                'nome' => $request->nome,
                'especialidade' => $request->especialidade,
                'cidade_id' => $request->cidade_id
            ]);

            return response()->json(
                [
                    'message' => 'Médico criado com sucesso',
                    'medico' => $medico
                ],
                201
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
