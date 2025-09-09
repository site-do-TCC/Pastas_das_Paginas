<?php
    session_start();
    
    //print_r($_SESSION);

    if((!isset($_SESSION['email']) == true) || (!isset($_SESSION['senha']) == true || $_SESSION['tipo'] == 'cliente')){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: \Programacao_TCC_Avena\html\login.php');
        }else{
            $logado = $_SESSION['email'];
        }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem vinda prestadora</title>
</head>
<body>
    <h1>Tela da prestadora<h1>

    <?php
    
    echo "<h1>Bem vinda</h1><ul>$logado</ul>"
    
    ?>

    <a href="\Programacao_TCC_Avena\php\sair.php">Deslogar</a>
</body>
</html>