<?php
    //print_r($_REQUEST);

    // com isso não da pra acessar pela url
    if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha']))
    {
        
        include_once('conexao.php');
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        //print_r('Email: ' . $email);
        //print_r('<br>');
        //print_r('Senha: ' . $senha);

           
        $sql = "SELECT * FROM cliente WHERE email = '$email' and senha = '$senha'";

        $result = $conexao->query($sql);

        print_r($result);

    }
    else
    {
        header('Location: \Programacao_TCC_Avena\html\login.php');
    }
?>