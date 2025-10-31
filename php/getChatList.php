<?php
include_once(__DIR__ . '/conexao.php');

header('Content-Type: application/json; charset=utf-8');

// Consulta — pega a prestadora de nome "Mulittle"
if ($_SESSION['nome'] === 'Mulittle') {
    // busca na tabela prestadora
    $sql = "SELECT id_usuario AS id, nome AS name, 
            '../img/SemFoto.jpg' AS photo
            FROM cliente
            WHERE nome = 'Jacob'";
} else {
    // busca na tabela cliente (sem imgperfil)
    $sql = "SELECT id_usuario AS id, nome AS name, 
            COALESCE(imgperfil, '../img/SemFoto.jpg') AS photo
            FROM prestadora
            WHERE nome = 'Mulittle'";
}


$result = mysqli_query($conexao, $sql);

$chatList = [];

while ($row = mysqli_fetch_assoc($result)) {
    $row['lastMessage'] = ""; // opcional
    $row['online'] = true;    // ou false, se quiser controlar status
    $chatList[] = $row;
}

echo json_encode($chatList, JSON_UNESCAPED_UNICODE);

$echo = $usuario;
?>
