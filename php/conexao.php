<?php


    $dbHost = 'localhost'; // pode usar minúsculo
    $dbUsername = 'root';
    $dbPassword = 'usbw';
    $dbName = 'db_avena';

    $conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    date_default_timezone_set('America/Sao_Paulo')
    $globalData = date("d/m/Y")
    $globalHora = date("H:i")
    $showNome = false;
    
    //if ($conexao->connect_errno){
    //   echo 'Erro na conexão';
    //} else {
    //    echo 'Deu certo';
    //}
?>