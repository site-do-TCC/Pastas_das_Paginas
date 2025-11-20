<?php
session_start();
require_once __DIR__ . '/conexao.php';

header('Content-Type: application/json; charset=utf-8');
$conexao->set_charset('utf8mb4');

function resolveCurrentUser()
{
    $tipo = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : null;
    $idPrest = (int) (isset($_SESSION['id_prestadora']) ? $_SESSION['id_prestadora'] : (isset($_SESSION['prestadora']['id_usuario']) ? $_SESSION['prestadora']['id_usuario'] : 0));
    $idCli   = (int) (isset($_SESSION['id_cliente']) ? $_SESSION['id_cliente'] : (isset($_SESSION['cliente']['id_usuario']) ? $_SESSION['cliente']['id_usuario'] : 0));

    if ($tipo === 'cliente' || ($idCli && !$idPrest)) {
        return ['role' => 'cliente', 'id_cliente' => $idCli, 'id_prestadora' => 0];
    }
    if ($tipo === 'profissional' || ($idPrest && !$idCli)) {
        return ['role' => 'prestadora', 'id_cliente' => 0, 'id_prestadora' => $idPrest];
    }
    return ['role' => null, 'id_cliente' => 0, 'id_prestadora' => 0];
}

try {
    $current = resolveCurrentUser();
    if (!$current['role']) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'erro' => 'Usuário não autenticado']);
        exit;
    }

    $isCliente = ($current['role'] === 'cliente');
    $idAtual = $isCliente ? $current['id_cliente'] : $current['id_prestadora'];

    if ($isCliente) {
        $sql = "
            SELECT c.id_chat,
                   p.id_usuario AS other_id,
                   p.nome AS other_name,
                   COALESCE(p.imgperfil, '../img/SemFoto.jpg') AS other_photo,
                   (
                       SELECT id_mensagem
                       FROM mensagem m
                       WHERE m.id_chat = c.id_chat
                       ORDER BY m.id_mensagem DESC
                       LIMIT 1
                   ) AS last_message_id,
                   (
                       SELECT conteudo
                       FROM mensagem m
                       WHERE m.id_chat = c.id_chat
                       ORDER BY m.id_mensagem DESC
                       LIMIT 1
                   ) AS last_message,
                   (
                       SELECT enviado_em
                       FROM mensagem m
                       WHERE m.id_chat = c.id_chat
                       ORDER BY m.id_mensagem DESC
                       LIMIT 1
                   ) AS last_message_time
            FROM chat c
            INNER JOIN prestadora p ON p.id_usuario = c.id_prestadora
            WHERE c.id_cliente = ?
            ORDER BY last_message_time DESC, c.id_chat DESC";
    } else {
        $sql = "
            SELECT c.id_chat,
                   cl.id_usuario AS other_id,
                   cl.nome AS other_name,
                   '../img/SemFoto.jpg' AS other_photo,
                   (
                       SELECT id_mensagem
                       FROM mensagem m
                       WHERE m.id_chat = c.id_chat
                       ORDER BY m.id_mensagem DESC
                       LIMIT 1
                   ) AS last_message_id,
                   (
                       SELECT conteudo
                       FROM mensagem m
                       WHERE m.id_chat = c.id_chat
                       ORDER BY m.id_mensagem DESC
                       LIMIT 1
                   ) AS last_message,
                   (
                       SELECT enviado_em
                       FROM mensagem m
                       WHERE m.id_chat = c.id_chat
                       ORDER BY m.id_mensagem DESC
                       LIMIT 1
                   ) AS last_message_time
            FROM chat c
            INNER JOIN cliente cl ON cl.id_usuario = c.id_cliente
            WHERE c.id_prestadora = ?
            ORDER BY last_message_time DESC, c.id_chat DESC";
    }

    $stmt = $conexao->prepare($sql);
    if (!$stmt) {
        throw new Exception('Erro ao preparar consulta: ' . $conexao->error);
    }
    $stmt->bind_param('i', $idAtual);
    $stmt->execute();
    $res = $stmt->get_result();

    $chats = [];
    while ($row = $res->fetch_assoc()) {
        $chats[] = [
            'id' => (int) $row['other_id'],
            'chatId' => (int) $row['id_chat'],
            'name' => $row['other_name'],
            'photo' => $row['other_photo'],
            'lastMessageId' => isset($row['last_message_id']) ? (int) $row['last_message_id'] : null,
            'lastMessage' => $row['last_message'] ?? '',
            'lastMessageTime' => $row['last_message_time'] ?? null,
            'online' => false
        ];
    }

    $stmt->close();

    echo json_encode(['ok' => true, 'chats' => $chats], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Exception $e) {
    error_log('getChatList error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'erro' => 'Erro interno: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit;
}
?>
