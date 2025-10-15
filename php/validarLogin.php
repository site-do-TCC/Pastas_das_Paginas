<?php
session_start();

if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {

    
    
    include_once('conexao.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $sql = "SELECT * FROM prestadora WHERE email = '$email' AND senha = '$senha'";
    $result = mysqli_query($conexao, $sql);
    $usuario = mysqli_fetch_assoc($result);
    

    if ($_POST['tipo'] == 'profissional'){  
        $sql = "SELECT * FROM prestadora WHERE email = '$email' and senha = '$senha'";
        $result = $conexao->query($sql);

        if(mysqli_num_rows($result) < 1){
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: /Programacao_TCC_Avena/html/login.php?erro=1');
            exit;
        } else {
            $_SESSION['tipo'] = 'profissional';
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            if ($usuario['passou_cadastro'] == 1) {
                header("Location: \Programacao_TCC_Avena\html\busca.php");
                exit;
            } else {
                header("Location: \Programacao_TCC_Avena\html\EdicaoPerfil.php");
                exit;
            }
        }
    } else {
        $sql = "SELECT * FROM cliente WHERE email = '$email' and senha = '$senha'";
        $result = $conexao->query($sql);

        if(mysqli_num_rows($result) < 1){
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: /Programacao_TCC_Avena/html/login.php?erro=1');
            exit;
        } else {
            $_SESSION['tipo'] = 'cliente';
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            header('Location: /Programacao_TCC_Avena/html/bemVindoCliente.php');
            exit;
        }
    }

} else {
    header('Location: /Programacao_TCC_Avena/html/login.php?erro=2');
    exit;
}
?>
