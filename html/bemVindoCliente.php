<?php
session_start();
include 'php/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    header('Location: login.php');
    exit();
}

$idCliente = $_SESSION['id_cliente'];

// Busca o nome do cliente no banco
$sql = "SELECT nome FROM cliente WHERE id_cliente = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $idCliente);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $cliente = $resultado->fetch_assoc();
    $nome = $cliente['nome'];
} else {
    $nome = "Usuário";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avena - Painel do Cliente</title>
    <link rel="stylesheet" href="css/bemVindoCliente.css">
</head>
<body>
    <header class="top-bar">
        <div class="logo"> 
            <img src="img/logo.png" alt="Logo Avena">
            <span>AVENA</span>
        </div>
        <div class="user-info">
            <span><?php echo $nome; ?></span>
            <img src="img/perfil.png" alt="Foto de perfil" class="profile-pic">
        </div>
    </header>

    <main class="content">
        <h1>Bem-vindo de volta, <?php echo $nome; ?>!</h1>
        <p>Encontre prestadoras de serviços qualificadas para as suas necessidades.</p>

        <div class="button-grid">
            <a href="busca.php" class="btn pink">
                🔍 Buscar Serviços
            </a>
            <a href="#" class="btn purple">
                📅 Minha Agenda
            </a>
            <a href="#" class="btn light-purple">
                💬 Mensagens
            </a>
            <a href="#" class="btn red">
                ⭐ Minhas Avaliações
            </a>
        </div>
    </main>
</body>
</html>
