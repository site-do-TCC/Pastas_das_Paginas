<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../php/conexao.php";

// =============================================
// 1) VERIFICA LOGIN
// =============================================
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../html/login.php");
    exit;
}

// Somente prestadoras podem acessar
if ($_SESSION['tipo'] !== 'profissional') {
    header("Location: ..\html\Pagina_Inicial.html");
    exit;
}

$id_usuario = intval($_SESSION['id_usuario']);

// =============================================
// 2) PEGAR DADOS DA usuario LOGADA
// =============================================
$sql = mysqli_query($conexao, "SELECT nome, imgperfil FROM prestadora WHERE id_usuario = $id_usuario");
$prest = mysqli_fetch_assoc($sql);

// =============================================
// 3) TRATAMENTO DE ACEITAR OU RECUSAR SOLICITAÇÃO
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);
    $acao = $_POST['acao']; // aceitar ou recusar

    // Atualiza status da solicitação
    $status = ($acao === "aceitar") ? "aceito" : "recusado"; 
    mysqli_query($conexao, "UPDATE solicitacoes SET status = '$status' WHERE id = $id");

    // Criar notificação para o cliente
    $sqlInfo = mysqli_query($conexao, "
        SELECT id_contratante 
        FROM solicitacoes 
        WHERE id = $id
    ");
    $inf = mysqli_fetch_assoc($sqlInfo);
    $id_cliente = $inf['id_contratante'];

    mysqli_query($conexao, "
        INSERT INTO notificacoes (id_usuario, id_solicitacao, mensagem)
        VALUES ($id_cliente, $id, 'Seu pedido foi $status pela prestadora.')
    ");

    header("Location: agenda.php?success=$status");
    exit;
}

// =============================================
// 4) SOLICITAÇÕES RECEBIDAS
// =============================================
//var_dump($_SESSION);
//exit;
$solicitacoes = mysqli_query($conexao, "
    SELECT 
        s.id,
        s.status,
        u.nome AS cliente_nome,
        u.imgperfil AS cliente_img
    FROM solicitacoes s
    INNER JOIN cliente u ON u.id_usuario = s.id_contratante
    WHERE s.id_prestadora = $id_usuario
    ORDER BY s.id DESC
");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agenda | Avena</title>

  <link rel="stylesheet" href="/Programacao_TCC_Avena/css/agenda.css">
</head>
<body>

<!-- ========================= COOKIE BANNER ========================= -->
<div id="cookie-banner" class="cookie-banner">
  <div class="cookie-content">
    <h4>Privacidade e Cookies</h4>
    <p>
      A Singularity Solutions utiliza cookies para melhorar sua experiência.
      <a href="\Programacao_TCC_Avena\img\AVENA - Termos de Uso e Política de Privacidade.pdf" target="_blank">
        Política de Privacidade
      </a>
    </p>
    <div class="cookie-buttons">
      <button id="accept-cookies" class="cookie-btn accept">Aceitar</button>
      <button id="decline-cookies" class="cookie-btn decline">Recusar</button>
    </div>
  </div>
</div>

<!-- ========================= MODAL ========================= -->
<div id="modalErro" class="modal">
    <div class="modal-content">
        <p id="mensagemErro">...</p>
        <button onclick="fecharModal()">OK</button>
    </div>
</div>

<header class="header">
    <div class="logo">
        <a href="Pagina_Inicial.html">
            <img src="/Programacao_TCC_Avena/img/logoAvena.png">
        </a>
    </div>

    <div class="perfil-area" id="perfil-area">
        <span class="nome"><?php echo htmlspecialchars($prest['nome']); ?></span>
        <img src="<?php echo htmlspecialchars($prest['imgperfil']); ?>" class="perfil-foto">
    </div>
</header>

<main class="agenda-container">

    <h1 class="titulo-agenda">Minhas Solicitações</h1>

    <?php if (isset($_GET['success'])): ?>
        <script>mostrarModal("Solicitação <?= htmlspecialchars($_GET['success']) ?> com sucesso!");</script>
    <?php endif; ?>

    <div class="lista-solicitacoes">
        <?php while($s = mysqli_fetch_assoc($solicitacoes)): ?>
        <div class="sol-card">

            <div class="sol-info">
                <img src="<?= htmlspecialchars($s['cliente_img']) ?>" class="cliente-img">

                <div>
                    <h2><?= htmlspecialchars($s['cliente_nome']) ?></h2>
                    <p>Status: <strong class="status <?= $s['status'] ?>">
                        <?= htmlspecialchars($s['status']) ?>
                    </strong></p>
                </div>
            </div>

            <?php if ($s['status'] === "pendente"): ?>
            <form method="POST" class="acoes">
                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button name="acao" value="aceitar" class="btn aceitar">Aceitar</button>
                <button name="acao" value="recusar" class="btn recusar">Recusar</button>
            </form>
            <?php endif; ?>

        </div>
        <?php endwhile; ?>
    </div>

</main>

<script src="../js/login.js"></script>
<script src="\Programacao_TCC_Avena\js\cookies.js"></script>

</body>
</html>
