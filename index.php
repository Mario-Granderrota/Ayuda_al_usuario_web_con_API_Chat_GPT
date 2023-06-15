<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'gpt_chat_api.php';
$config = parse_ini_file('config.ini');
$chat_api = new GptChatApi();
$mensaje_sistema = $config['mensaje_sistema'];
$max_tokens_por_respuesta = (int)$config['max_tokens_por_respuesta'];

$tokens_diarios_permitidos = $config['max_tokens_diarios'];

if (isset($_POST['pregunta'])) {
    $pregunta = $_POST['pregunta'];

    $opciones = [
        'max_tokens' => $max_tokens_por_respuesta,
        'temperature' => 0.0,
        'mensaje_sistema' => $mensaje_sistema,
    ];

    $ip = $_SERVER['REMOTE_ADDR'];
    $tokens_usados = $chat_api->contar_tokens($ip);

    if ($tokens_usados < $tokens_diarios_permitidos) {
        $respuesta = $chat_api->generar_respuesta($pregunta, $opciones);
        $chat_api->sumar_tokens($ip, strlen($respuesta));
    } else {
        $respuesta = 'Lo sentimos, has alcanzado el lÃ­mite de tokens diarios.';
    }
} else {
    $pregunta = '';
    $respuesta = '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi empresa</title>
</head>
<body>
    <h1>Mi empresa</h1>
    <form action="" method="post">
        <label for="pregunta">Pregunta:</label><br>
        <textarea name="pregunta" id="pregunta" rows="5" cols="40"><?php echo htmlspecialchars($pregunta); ?></textarea><br>
        <input type="submit" value="Enviar">
    </form>
    <p>Respuesta:</p>
    <pre><?php echo htmlspecialchars($respuesta); ?></pre>
</body>
</html>
