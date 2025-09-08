<?php

    session_start();
    //print_r($_REQUEST);

    // com isso não da pra acessar pela url
    if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha']) && !empty($_POST['senha']))
    {
        
        include_once('conexao.php');
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        //print_r('Email: ' . $email);
        //print_r('<br>');
        //print_r('Senha: ' . $senha);

        if ($_POST['tipo'] == 'profissional'){  
            //print_r('Estou pesquisando na área de profissionais');
            $sql = "SELECT * FROM prestadora WHERE email = '$email' and senha = '$senha'";
            $result = $conexao->query($sql);
            //print_r($result);

            if(mysqli_num_rows($result) < 1){
                unset($_SESSION['email']);
                unset($_SESSION['senha']);
                print_r('Não existe');
            }else{
                $_SESSION['email'] = $email;
                $_SESSION['senha'] = $senha;
                header('Location: \Programacao_TCC_Avena\html\bemVindoPrestadora.php');
            }
        }
        else{
            //print_r('Estou pesquisando na área de contratantes');
            $sql = "SELECT * FROM cliente WHERE email = '$email' and senha = '$senha'";
            $result = $conexao->query($sql);
            //print_r($result);

            if(mysqli_num_rows($result) < 1){
                unset($_SESSION['email']);
                unset($_SESSION['senha']);
                print_r('Não existe');
            }else{
                $_SESSION['email'] = $email;
                $_SESSION['senha'] = $senha;
                header('Location: \Programacao_TCC_Avena\html\bemVindoCliente.php');
            }
        }
        

        

    }
    else
    {
        header('Location: \Programacao_TCC_Avena\html\login.php');
    }
?>