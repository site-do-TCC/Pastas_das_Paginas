<?php
require 'conexao.php';
header('Content-Type: application/json; charset=utf-8');

$id_chat = $_GET['id_chat'] ?? null;
if (!$id_chat) {
    echo json_encode(['erro' => 'ID do chat não enviado']);
    exit;
}

try {
    $sql = "SELECT id_mensagem, remetente_tipo AS remetente, id_remetente, conteudo, data_envio 
            FROM mensagens 
            WHERE id_chat = ? 
            ORDER BY data_envio ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_chat]);
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($mensagens);
} catch (PDOException $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
