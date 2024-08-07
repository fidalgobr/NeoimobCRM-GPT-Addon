<?php
class Gerador_texto_chatgpt
{
    private $erro_info;
    private $api_key;
    private $info;

    private function interagir_gpt()
    {
        $prompt =
            "Você irá escrever descrições de imóveis para um site de venda e aluguel de imóveis com base em informações providas para você em um formato JSON.

            Regras: 

            - Não inventar informações falsas
            - Levar em consideração informações adicionais, caso sejam providas
            - Só escrever a descrição, nada de títulos nem outras mensagems
            - Escrever em HTML, use tags para tipografia. ex: <strong> para negrito, etc
            - Não colocar o preço na descrição, exceto de solicitado nas informações adicionais";

        $data = [
            "model" => "gpt-4o",
            "messages" => [
                [
                    "role" => "system",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => $prompt
                        ]
                    ]
                ],
                [
                    "role" => "user",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => $this->info
                        ]
                    ]
                ]
            ],
            "temperature" => 1,
            "max_tokens" => 1024,
            "top_p" => 1,
            "frequency_penalty" => 0,
            "presence_penalty" => 0
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_key,
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $resposta = curl_exec($ch);

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (!$resposta || $http_status !== 200) {
            $this->erro_info = curl_error($ch);
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $resposta_info = json_decode($resposta, true);

        return $resposta_info['choices'][0]['message']['content'];
    }

    function __construct()
    {
        $this->info = file_get_contents('php://input');
        $this->api_key = 'substitua-esta-string-pela-chave';
    }

    public function run()
    {
        $gpt_resposta = $this->interagir_gpt();

        if (!$this->erro_info) {
            echo json_encode([
                'resposta' => $gpt_resposta
            ]);
        } else {
            status_header(500);
            echo json_encode([
                'erro' => $this->erro_info
            ]);
        }
    }
}