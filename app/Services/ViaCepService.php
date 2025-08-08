<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ViaCepService
{
    public function consultar(string $cep): array
    {
        // Remove qualquer caractere não numérico
        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            return ['erro' => 'CEP inválido'];
        }

        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['erro']) && $data['erro']) {
                return ['erro' => 'CEP não encontrado'];
            }

            return $data;
        }

        return ['erro' => 'Erro na comunicação com ViaCEP'];
    }
}
