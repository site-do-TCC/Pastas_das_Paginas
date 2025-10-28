<?php
session_start();
include_once('conexao.php'); // inclui a conexão

if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {

    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $tipo = $_POST['tipo']; // 'cliente', 'contratante' ou 'profissional'

    if($tipo === 'profissional') {
        $stmt = $conexao->prepare("SELECT * FROM prestadora WHERE email = ? AND senha = ?");
        $stmt->bind_param("ss", $email, $senha);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows < 1){
            header('Location: ../html/login.php?erro=1');
            exit;
        } else {
            $dados = $result->fetch_assoc();
            $_SESSION['id_prestadora'] = $dados['id_prestadora'];
            $_SESSION['tipo'] = 'profissional';
            $_SESSION['email'] = $email;
            header('Location: ../html/EdicaoPerfil.php');
            exit;
        }

    } elseif($tipo === 'cliente' || $tipo === 'contratante') {
        $stmt = $conexao->prepare("SELECT * FROM cliente WHERE email = ? AND senha = ?");
        $stmt->bind_param("ss", $email, $senha);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows < 1){
            header('Location: ../html/login.php?erro=1');
            exit;
        } else {
            $dados = $result->fetch_assoc();
            $_SESSION['id_cliente'] = $dados['id_usuario']; // CORRIGIDO para id_usuario
            $_SESSION['tipo'] = 'cliente';
            $_SESSION['email'] = $email;
            header('Location: ../html/bemVindoCliente.php');
            exit;
        }

    } else {
        header('Location: ../html/login.php?erro=3');
        exit;
    }

} else {
    header('Location: ../html/login.php?erro=2');
    exit;
}
?>
