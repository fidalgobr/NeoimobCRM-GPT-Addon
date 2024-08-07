<?php
include_once 'Gerador_texto_chatgpt.php';

class Iniciador_api
{
    private function adicionar_wp_endpoint()
    {

        function funcao_resposta()
        {
            $gerador = new Gerador_texto_chatgpt();
            $gerador->run();
        }

        add_action('rest_api_init', function () {
            register_rest_route(
                'chatgpt-neoimob/v1',
                '/gerar-descricao/',
                array(
                    'methods' => 'POST',
                    'callback' => 'funcao_resposta',
                    'permission_callback' => function () {
                        //optei por nem registrar a API caso o usuário não esteja logado
                        //wordpress é muito enjoado com API, queria evitar problema com nonces e etc
                        return true;
                    }
                )
            );
        });
    }
    public function iniciar()
    {
        $this->adicionar_wp_endpoint();
    }
}