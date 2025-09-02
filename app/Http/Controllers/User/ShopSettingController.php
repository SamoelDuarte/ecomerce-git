<?php

namespace App\Http\Controllers\User;

use App\Models\User\UserCurrency;
use App\Models\User\UserAddress;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Models\User\Language;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopSettingController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::guard('web')->user()->id;
        $data['address'] = UserAddress::where('user_id', $user_id)->first();
        return view('user.item.shop_setting.index', $data);
    }


    public function store(Request $request)
    {
        // Remove máscara do CNPJ antes da validação
        $input = $request->all();
        if (isset($input['cnpj'])) {
            $input['cnpj'] = preg_replace('/[^0-9]/', '', $input['cnpj']);
        }
        $rules = [
            'token_frenet' => 'required',
            'cep' => 'required|size:8',
            'rua' => 'required',
            'numero' => 'required',
            'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required|size:2',
        ];

        $messages = [
            'token_frenet.required' => 'O token da Frenet é obrigatório',
            'cep.required' => 'O CEP é obrigatório',
            'cep.size' => 'O CEP deve ter 8 dígitos',
            'rua.required' => 'A rua é obrigatória',
            'numero.required' => 'O número é obrigatório',
            'bairro.required' => 'O bairro é obrigatório',
            'cidade.required' => 'A cidade é obrigatória',
            'estado.required' => 'O estado é obrigatório',
            'estado.size' => 'O estado deve ter 2 caracteres'
        ];

        $validator = Validator::make($input, $rules, $messages);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        
       
        
        $input['user_id'] = Auth::guard('web')->user()->id;

        UserAddress::updateOrCreate(
            ['user_id' => $input['user_id']],
            $input
        );

        Session::flash('success', __('Endereço salvo com sucesso'));
        return "success";
    }

    public function consultaCep(Request $request)
    {
        $cep = preg_replace('/[^0-9]/', '', $request->cep);
        
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $response = file_get_contents($url);
        $data = json_decode($response);

        if (isset($data->erro)) {
            return response()->json(['error' => true, 'message' => 'CEP não encontrado'], 404);
        }

        return response()->json([
            'rua' => $data->logradouro,
            'bairro' => $data->bairro,
            'cidade' => $data->localidade,
            'estado' => $data->uf
        ]);
    }
}
