<?php 
namespace App\Services;

use GuzzleHttp\Client;

class FrenetService
{
    protected $client;
    protected $token;
    protected $url = 'https://api.frenet.com.br/shipping/quote';

    public function __construct($token = null)
    {
        $this->client = new Client();
        $this->token = $token ?? env('FRENET_API_TOKEN');
    }

    public function calcularFrete(array $produtos, string $cepOrigem, string $cepDestino, float $valorNota)
    {
        $itens = [];

        foreach ($produtos as $produto) {
            // Verifica se todas as dimensões e peso estão presentes e são maiores que zero
            if (!empty($produto['weight']) && !empty($produto['length']) && 
                !empty($produto['height']) && !empty($produto['width']) &&
                (float)$produto['weight'] > 0 && (int)$produto['length'] > 0 &&
                (int)$produto['height'] > 0 && (int)$produto['width'] > 0) {
                
                $itens[] = [
                    "Weight"   => (float) $produto['weight'],
                    "Length"   => (int) $produto['length'],
                    "Height"   => (int) $produto['height'],
                    "Width"    => (int) $produto['width'],
                    "Quantity" => (int) ($produto['quantity'] ?? 1),
                ];
            }
        }

        $body = [
            "ShippingItemArray"     => $itens,
            "SellerCEP"             => $cepOrigem,
            "RecipientCEP"          => $cepDestino,
            "ShipmentInvoiceValue"  => $valorNota,
        ];

        $response = $this->client->request('POST', $this->url, [
            'headers' => [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'token'         => $this->token,
            ],
            'body' => json_encode($body),
        ]);

        return json_decode($response->getBody(), true);
    }
}
