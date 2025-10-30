<?php
session_start();
include_once(__DIR__ . '/conexao.php');
mysqli_set_charset($conexao, "utf8");
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Verifica se os campos foram enviados
if (
    isset($_POST['id_mensagem']) &&
    isset($_POST['id_chat']) &&
    isset($_POST['id_remetente']) &&
    isset($_POST['id_destinatario']) &&
    isset($_POST['conteudo'])
) {
    // Captura os valores vindos do JavaScript
    $id_mensagem = mysqli_real_escape_string($conexao, $_POST['id_mensagem']);
    $id_chat = mysqli_real_escape_string($conexao, $_POST['id_chat']);
    $id_remetente = mysqli_real_escape_string($conexao, $_POST['id_destinatario']);
    $id_destinatario = mysqli_real_escape_string($conexao, $_POST['id_destinatario']);
    $conteudo = mysqli_real_escape_string($conexao, $_POST['conteudo']);


    // Exemplo: salvar no banco
    $sql = "INSERT INTO mensagem (id_chat, id_de, id_para, conteudo, enviado_em)
        VALUES ('$id_chat', '$id_remetente', '$id_destinatario', '$conteudo', NOW())";

    if (mysqli_query($conexao, $sql)) {
        echo "Mensagem salva com sucesso!";
    } else {
        echo "Erro ao salvar: " . mysqli_error($conexao);
    }
} else {
    echo "Dados não recebidos corretamente!";
}





?>