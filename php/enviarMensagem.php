<?php
require 'conexao.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $id_chat = $_POST['id_chat'] ?? null;
    $remetente_tipo = $_POST['remetente'] ?? null;
    $id_remetente = $_POST['id_remetente'] ?? null;
    $conteudo = $_POST['conteudo'] ?? null;

    if (!$id_chat || !$remetente_tipo || !$id_remetente || !$conteudo) {
        echo json_encode(['erro' => 'Dados incompletos']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id_cliente, id_prestadora FROM chat WHERE id_chat = ?");
    $stmt->execute([$id_chat]);
    $chat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chat) {
        echo json_encode(['erro' => 'Chat não encontrado']);
        exit;
    }

    if ($remetente_tipo === 'cliente') {
        $destinatario_tipo = 'prestadora';
        $id_destinatario = $chat['id_prestadora'];
    } else {
        $destinatario_tipo = 'cliente';
        $id_destinatario = $chat['id_cliente'];
    }

    $sql = "INSERT INTO mensagens 
        (id_chat, remetente_tipo, id_remetente, destinatario_tipo, id_destinatario, conteudo)
        VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_chat, $remetente_tipo, $id_remetente, $destinatario_tipo, $id_destinatario, $conteudo]);

    echo json_encode(['status' => 'ok', 'mensagem' => $conteudo]);
} catch (PDOException $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
