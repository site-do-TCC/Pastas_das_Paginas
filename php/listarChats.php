<?php
require 'conexao.php';
header('Content-Type: application/json; charset=utf-8');
mysqli_set_charset($conexao, "utf8");

$sql = "SELECT c.id_chat, cli.nome AS nome_cliente, p.nome AS nome_prestadora, p.imgperfil AS foto_prestadora
        FROM chat c
        JOIN cliente cli ON cli.id_usuario = c.id_cliente
        JOIN prestadora p ON p.id_usuario = c.id_prestadora";

$result = $conexao->query($sql);

if ($result) {
    $chats = [];
    while ($row = $result->fetch_assoc()) {
        $chats[] = $row;
    }
    echo json_encode($chats, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['erro' => $conexao->error]);
}
exit;
?>
