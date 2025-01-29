<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Medico;

class CidadeController extends Controller
{
    /**
     * Lista todas as cidades
     * 
     * @queryParam string nome Nome da cidade
     * @queryParam int page Número da página
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $nome = request()->query('nome');
            $page = request()->query('page', 1);

            $cidades = Cidade::where('nome', 'like', "%{$nome}%")
                ->orderBy('nome')
                ->paginate(10, ['*'], 'page', $page);

            if ($cidades->isEmpty()) {
                return response()->json(['error' => 'Cidade não encontrada'], 404);
            }

            return response()->json($cidades);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function medicos(int $id)
    {
        try {

            $nome = request()->query('nome');
            $nome = str_replace(['dr', 'dra'], '', $nome);

            $page = request()->query('page', 1);

            $medicos = Medico::where('cidade_id', $id)
                ->where('nome', 'like', "%{$nome}%")
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
}
