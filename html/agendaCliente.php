<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../php/conexao.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['id_usuario'];

// ======================= BUSCAR DADOS DO CLIENTE =======================
$sqlCliente = "SELECT nome, email, imgperfil FROM cliente WHERE id_usuario = ?";
$stmt = $conexao->prepare($sqlCliente);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultCliente = $stmt->get_result();

if ($resultCliente->num_rows === 0) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$cliente = $resultCliente->fetch_assoc();


// ======================= INSERIR ANOTAÇÃO =======================
$id_usuario = $_SESSION["id_usuario"];
$tipo_usuario = "cliente";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["texto"])) {

    $data = $_POST["data"];
    $texto = $_POST["texto"];

    // SUA TABELA TEM: id, id_usuario, tipo_usuario, data_evento, anotacao, criado_em
    $sqlInsert = "INSERT INTO agenda (id_usuario, tipo_usuario, data_evento, anotacao)
                  VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sqlInsert);
    $stmt->bind_param("isss", $id_usuario, $tipo_usuario, $data, $texto);
    $stmt->execute();

    header("Location: agendaCliente.php?ok=1");
    exit();
}


// ======================= BUSCAR ANOTAÇÕES =======================
$sqlLista = "SELECT id, data_evento, anotacao 
             FROM agenda
             WHERE id_usuario = ? AND tipo_usuario = ?
             ORDER BY data_evento ASC";

$stmt = $conexao->prepare($sqlLista);
$stmt->bind_param("is", $id_usuario, $tipo_usuario);
$stmt->execute();
$result = $stmt->get_result();

if(isset($_POST['deletar'])) {
    $idDeletar = intval($_POST['deletar']);
    $sqlDeletar = "DELETE FROM agenda WHERE id = ? AND id_usuario = ? AND tipo_usuario = ?";
    $stmtDel = $conexao->prepare($sqlDeletar);
    $stmtDel->bind_param("iis", $idDeletar, $id_usuario, $tipo_usuario);
    $stmtDel->execute();
    header("Location: agendaCliente.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agenda | Avena</title>

  <link rel="stylesheet" href="/Programacao_TCC_Avena/css/agendaCliente.css">
</head>
<body>

<!-- COOKIE BANNER -->
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

<!-- MODAL -->
<div id="modalErro" class="modal">
    <div class="modal-content">
        <p id="mensagemErro">...</p>
        <button onclick="fecharModal()">OK</button>
    </div>
</div>

<header class="header">
    <div class="logo">
        <a href="Pagina_Inicial.html">
            <img src="/Programacao_TCC_Avena/img/logoAvena.png" >
        </a>
    </div>

    <div class="perfil-area" id="perfil-area">
        <span class="nome"><?php echo htmlspecialchars($cliente['nome']); ?></span>
        <img src="<?php echo htmlspecialchars($cliente['imgperfil']); ?>" class="perfil-foto">
    </div>
</header>

<main class="agenda-container">

    <h1 class="titulo-agenda">Minha Agenda</h1>

    <!-- FORMULÁRIO DE NOVA ANOTAÇÃO -->
    <form method="POST" class="form-anotacao">
        <div class="form-group">
            <label>Data:</label>
            <input type="date" name="data" required>
        </div>

        <div class="form-group">
            <label>Anotação:</label>
            <textarea name="texto" maxlength="255" required></textarea>
        </div>

        <button type="submit" class="btn-salvar">Salvar Anotação</button>
    </form>

    <h2 class="titulo-lista">Minhas Anotações</h2>

    <div class="lista-anotacoes">
        <?php while($a = mysqli_fetch_assoc($result)): ?>

            
        <?php
            $dataEvento = new DateTime($a['data_evento'] . " 00:00:00");
            $hoje = new DateTime("today 00:00:00");

            $diff = $hoje->diff($dataEvento);
            $diasRestantes = (int)$diff->format("%r%a");
        ?>


<div class="anotacao-card">

    <div class="anotacao-data">
        <?= date("d/m/Y", strtotime($a['data_evento'])) ?>
    </div>

    <div class="anotacao-texto">
        <?= nl2br(htmlspecialchars($a['anotacao'])) ?>
    </div>

    <div class="dias-restantes">
        <?php
            if ($diasRestantes > 1) {
                echo "Faltam $diasRestantes dias";
            } elseif ($diasRestantes === 1) {
                echo "Falta 1 dia";
            } elseif ($diasRestantes === 0) {
                echo "É hoje!";
            } else {
                echo "Evento passou há " . abs($diasRestantes) . " dias";
            }
        ?>
    </div>

    <form method="POST">
        <input type="hidden" name="deletar" value="<?= $a['id'] ?>">
        <button class="btn-delete" name="deletar">Excluir</button>
    </form>

</div>

<?php endwhile; ?>
    </div>

</main>

<script src="../js/login.js"></script>
<script src="\Programacao_TCC_Avena\js\cookies.js"></script>

</body>
</html>
