<?php
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = 'usbw';
$dbName = 'db_avena';

$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if($conexao->connect_errno){
    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}
?>
