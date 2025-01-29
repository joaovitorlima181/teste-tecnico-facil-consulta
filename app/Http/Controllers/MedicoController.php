<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicoRequest;
use App\Models\Medico;

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
    public function index()
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
     * Cria um novo médico
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreMedicoRequest $request)
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
