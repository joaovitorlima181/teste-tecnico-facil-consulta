<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * Cria novo usuário
     * 
     * @param StoreUserRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(StoreUserRequest $request) : \Illuminate\Http\JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);


            return response()->json(
                [
                    'message' => 'User created successfully',
                    'user' => $user
                ],
                201
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Realiza login do usuário, retornando o token JWT
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login() : \Illuminate\Http\JsonResponse
    {
        try {
            $credentials = request(['email', 'password']);

            if (! $token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $success = $this->respondWithToken($token);

            return response()->json(
                [
                    'message' => 'User logged in successfully',
                    'token' => $success
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza o token JWT
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() : \Illuminate\Http\JsonResponse
    {
        try {
            $success = $this->respondWithToken(auth('api')->refresh());

            return response()->json(
                [
                    'message' => 'Token refreshed successfully',
                    'token' => $success
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gera resposta com o token JWT
     *
     * @param string $token Token JWT
     *
     * @return array
     */
    protected function respondWithToken(string $token) : array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }
}
