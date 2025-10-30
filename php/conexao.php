<?php
$servidor = "localhost";
$usuario = "root";
$senha = "usbw";
$dbname = "db_avena";

$conexao = new mysqli($servidor, $usuario, $senha, $dbname);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

date_default_timezone_set('America/Sao_Paulo');
?>
