<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use App\Models\Paciente;

class PacienteController extends Controller
{

    /**
     * Listar pacientes
     * 
     * @query string $nome Nome do paciente
     * @query int $page Número da página
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $page = request()->query('page', 1);
            $nome = request()->query('nome');

            $pacientes = Paciente::where('nome', 'like', "%{$nome}%")
                ->orderBy('nome')
                ->paginate(10, ['*'], 'page', $page);

            if ($pacientes->isEmpty()) {
                return response()->json(['error' => 'Paciente não encontrado'], 404);
            }

            return response()->json($pacientes);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lista pacientes e consultas por médico
     * 
     * @param int $medicoId Id do médico
     * 
     *  @return \Illuminate\Http\Response
     */
    public function consultas(int $medicoId): \Illuminate\Http\JsonResponse
    {
        try {

            $page = request()->query('page', 1);
            $apenasAgendadas = request()->query('apenas-agendadas', false);

            $pacientes = Paciente::whereHas('consultas', function ($query) use ($medicoId) {
                $query->where('medico_id', $medicoId);
            })
                ->when($apenasAgendadas, function ($query) {
                    $query->whereHas('consultas', function ($query) {
                        $query->where('data', '>', now());
                    });
                })
                ->with('consultas')
                ->paginate(10, ['*'], 'page', $page);

            if ($pacientes->isEmpty()) {
                return response()->json(['error' => 'Paciente não encontrado'], 404);
            }

            return response()->json($pacientes);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Cadastra um novo paciente
     * 
     * @param StorePacienteRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(StorePacienteRequest $request) : \Illuminate\Http\JsonResponse
    {
        try {

            $paciente = Paciente::create([
                'nome' => $request->nome,
                'cpf' => $request->cpf,
                'celular' => $request->celular,
            ]);

            return response()->json(
                [
                    'message' => 'Paciente criado com sucesso',
                    'paciente' => $paciente
                ],
                201
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Atualiza um paciente
     * 
     * @param UpdatePacienteRequest $request
     * @param int $id Id do paciente
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePacienteRequest $request, int $id) : \Illuminate\Http\JsonResponse
    {
        try {
            
            $paciente = Paciente::findOrFail($id);

            if ($request->cpf !== $paciente->cpf) {
                return response()->json(['error' => 'CPF não pode ser alterado'], 400);
            }
            
            $paciente->update([
                'nome' => $request->nome,
                'cpf' => $request->cpf,
                'celular' => $request->celular,
            ]);

            return response()->json(
                [
                    'message' => 'Paciente atualizado com sucesso',
                    'paciente' => $paciente
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
