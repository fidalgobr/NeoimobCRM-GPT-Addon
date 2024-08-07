# NeoimobCRM-GPT-Addon

Este repositório contém um plugin para WordPress que adiciona a funcionalidade de gerar descrições de imóveis para o CRM Neoimob com base em informações preenchidas anteriormente. O plugin utiliza o modelo GPT para criar descrições detalhadas e envolventes automaticamente.

## Funcionalidades

- Geração automática de descrições de imóveis usando GPT.
- Integração direta com o CRM Neoimob.
- Configuração simples e rápida.

## Requisitos

- Uma chave de API do ChatGPT.

## Instalação

1. **Baixe o plugin:**
   - Clone este repositório ou baixe o arquivo ZIP e extraia na pasta wp-contents/plugin do seu site Wordpress.

   ```sh
   git clone https://github.com/seu-usuario/NeoimobCRM-GPT-Addon.git

2. **Ative o plugin:**

    - No painel de administração do WordPress, vá para Plugins e ative o plugin "Integração ChatGPT + Neoimob".

3. **Insira sua chave privada da api do ChatGPT:**
   - Vá na pasta do plugin, em seguida em classes/Gerador_texto_chatgpt.php e edite a linha 79 com a sua chave.
