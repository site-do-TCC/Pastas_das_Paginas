<?php


    $dbHost = 'localhost'; // pode usar minúsculo
    $dbUsername = 'root';
    $dbPassword = 'usbw';
    $dbName = 'db_avena';

    $conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    //if ($conexao->connect_errno){
    //   echo 'Erro na conexão';
    //} else {
    //    echo 'Deu certo';
    //}
?>