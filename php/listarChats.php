<?php
require 'conexao.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $sql = "SELECT c.id_chat, cli.nome AS nome_cliente, p.nome AS nome_prestadora,
                   p.imgperfil AS foto_prestadora
            FROM chat c
            JOIN cliente cli ON cli.id_usuario = c.id_cliente
            JOIN prestadora p ON p.id_usuario = c.id_prestadora";
    $stmt = $pdo->query($sql);
    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($chats);
} catch (PDOException $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
