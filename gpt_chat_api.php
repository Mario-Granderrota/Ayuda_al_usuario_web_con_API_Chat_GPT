<?php

class GptChatApi {
    private $api_url = 'https://api.openai.com/v1/engines/text-davinci-002/completions';
    private $api_clave;

    public function __construct() {
        $config = parse_ini_file('config.ini');
        $this->api_clave = $config['openai_api_key'];
    }

    public function generar_respuesta($pregunta, $opciones = []) {
        $payload = [
            'prompt' => $opciones['mensaje_sistema'] . $pregunta,
            'max_tokens' => $opciones['max_tokens'],
            'temperature' => $opciones['temperature'],
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_clave,
        ];

        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $respuesta = curl_exec($ch);

        if (curl_error($ch)) {
            $error_msg = curl_error($ch);
