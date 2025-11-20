<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servidor = "localhost";
$usuario = "root";
$senha = "usbw";
$dbname = "db_avena";

$conexao = new mysqli($servidor, $usuario, $senha, $dbname);
$conexao->set_charset('utf8mb4');

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

date_default_timezone_set('America/Sao_Paulo');

?>
