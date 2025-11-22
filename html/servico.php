<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once(__DIR__ . '/../php/conexao.php'); // ajuste caminho se precisar


// -------------------- TRATAMENTO DO POST --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitar'])) {


    // ID DO CLIENTE LOGADO
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../html/login.php");
        exit;
    }

    $id_contratante = intval($_SESSION['id_usuario']);

    // ID DA PRESTADORA
    $id_prestadora = intval($_POST['id_prestadora']);

    // INSERIR NA TABELA
    $insert = mysqli_prepare(
        $conexao,
        "INSERT INTO solicitacoes (id_contratante, id_prestadora) VALUES (?, ?)"
    );

    mysqli_stmt_bind_param($insert, "ii", $id_contratante, $id_prestadora);
    $ok = mysqli_stmt_execute($insert);
    mysqli_stmt_close($insert);

    if ($ok) {
        header("Location: servico.php?id_prestadora={$id_prestadora}&success=1");
        exit;
    } else {
        echo "<script>mostrarModal('Erro ao solicitar serviço. Veja se id_contratante e id_prestadora existem.');</script>";
    }
}
// -------------------- FIM TRATAMENTO POST --------------------

// GET id_prestadora (a que veio do card)
if (!isset($_GET['id_prestadora'])) {
    header("Location: busca.php");
    exit;
}
$id_prestadora = intval($_GET['id_prestadora']);
$_SESSION['id_prestadora'] = $id_prestadora;

// busca dados da prestadora
$sql = "SELECT * FROM prestadora WHERE id_usuario = $id_prestadora";
$resultado = mysqli_query($conexao, $sql);
if (mysqli_num_rows($resultado) == 0) {
    header("Location: busca.php");
    exit;
}
$prof = mysqli_fetch_assoc($resultado);

// ======================
// BUSCAR AVALIAÇÕES DA PRESTADORA
// ======================

// QUANTIDADE DE AVALIAÇÕES
$sqlQtd = "
    SELECT COUNT(*) AS total 
    FROM avaliacoes 
    WHERE avaliado_id = $id_prestadora 
      AND avaliado_tipo = 'prestadora'
";
$resultQtd = mysqli_query($conexao, $sqlQtd);
$qtdAvaliacoes = mysqli_fetch_assoc($resultQtd)['total'] ?? 0;

// MÉDIA DAS NOTAS
$sqlMedia = "
    SELECT AVG(nota) AS media 
    FROM avaliacoes 
    WHERE avaliado_id = $id_prestadora 
      AND avaliado_tipo = 'prestadora'
";


// =================================
// info do usuário logado (se houver)
// =================================
$logado = isset($_SESSION['id_usuario']);
$id_usuario = $logado ? intval($_SESSION['id_usuario']) : null;

// buscar dados do perfil do usuário logado para header (se existir)
$profLog = null;
if ($logado) {
    if ($_SESSION['tipo'] === 'profissional') {
        $sqlPrestadora = "SELECT nome, imgperfil FROM prestadora WHERE id_usuario = ".$id_usuario;
        $resultadoPrestadora = mysqli_query($conexao, $sqlPrestadora);
        $profLog = mysqli_fetch_assoc($resultadoPrestadora);
    } else {
        $sqlCliente = "SELECT nome, imgperfil FROM cliente WHERE id_usuario = ".$id_usuario;
        $resultadoCliente = mysqli_query($conexao, $sqlCliente);
        $profLog = mysqli_fetch_assoc($resultadoCliente);
    }
}


$resultMedia = mysqli_query($conexao, $sqlMedia);
$mediaAvaliacoes = mysqli_fetch_assoc($resultMedia)['media'];
$mediaAvaliacoes = $mediaAvaliacoes ? number_format($mediaAvaliacoes, 1) : "0.0";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($prof['nome']) ?> | Avena</title>
  <link rel="stylesheet" href="\Programacao_TCC_Avena\css\servico.css">
</head>
<body>

   <!-- ===============================
     Banner de Consentimento de Cookies - Singularity Solutions
     =================================== -->
     <div id="cookie-banner" class="cookie-banner">
  <div class="cookie-content">
  <h4>Privacidade e Cookies</h4>
  <p>
        A Singularity Solutions utiliza cookies para oferecer uma experiência mais personalizada,
        melhorar o desempenho da plataforma e garantir o funcionamento seguro dos serviços.
        Ao aceitar, você concorda com o uso de cookies conforme nossa
  <a href="\Programacao_TCC_Avena\img\AVENA - Termos de Uso e Política de Privacidade.pdf" target="_blank">Política de Privacidade</a>.
  </p>
  <div class="cookie-buttons">
  <button id="accept-cookies" class="cookie-btn accept">Aceitar</button>
  <button id="decline-cookies" class="cookie-btn decline">Recusar</button>
  </div>
  </div>
  </div>


  <!-- Mensagem -->
    <div id="modalErro" class="modal">
        <div class="modal-content">
            <p id="mensagemErro">...</p>
            <button onclick="fecharModal()">OK</button>
        </div>
    </div>

  <header class="header">
    <div class="logo">
      <a href="\Programacao_TCC_Avena\html\Pagina_Inicial.html"><img src="\Programacao_TCC_Avena\img\logoAvena.png" alt="Logo Avena"></a>
    </div>
    <!-- Botão do perfil se não estiver logado -->
    <a href="..\html\login.php" class="btn-entrar" id="btn-entrar">ENTRAR</a>
    <!-- Fim do botão do perfil se não estiver logado -->


    <!-- ===============================================
         Área do perfil se tiver logado (VAI DENTRO DA HEADER)
    ====================================================-->
    <?php if(isset($_SESSION['id_usuario'])){?>
    <div class="perfil-area" id="perfil-area">
      <span class="nome"><?php echo htmlspecialchars($profLog['nome']); ?></span>
      <img src="<?php echo htmlspecialchars($profLog['imgperfil']); ?>" alt="Foto de perfil" class="perfil-foto">
    </div>

    <?php } ?>
    <!-- ======================================================
          Fim da área do perfil se estiver logado (VAI DENTRO DA HEADER)
    =============================================================-->


  </header>

  <nav class="breadcrumb">
    <a href="\Programacao_TCC_Avena\html\Pagina_Inicial.html" style="text-decoration:none;">

    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
        <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
      </svg>
    </a>/
    <a href="busca.php" style="text-decoration:none;">Busca</a> /
    <span><?= htmlspecialchars($prof['nome']) ?></span>
  </nav>

  <main class="container">
    <section class="info">
    <div class="abaixo-img">
      <div class="perfil">
        <div class="perfil">
          <div class="topo">
            <img src="<?= htmlspecialchars($prof['imgperfil']) ?>" class="foto-perfil" alt="<?= htmlspecialchars($prof['nome']) ?>">
          <div class="lado-img">
              <h1><?= htmlspecialchars($prof['empresa_nome']) ?></h1>
          </div>
        </div>


</div>
        <h3>Sobre <?= htmlspecialchars($prof['empresa_nome']) ?></h3>
      </div>

    <div class="avaliacao">
    ⭐ <?= $mediaAvaliacoes ?>
</div>

<a href="avaliacoes.php?id=<?= $id_prestadora ?>" class="avaliacoes">
    <?= $qtdAvaliacoes ?> Avaliações
</a>

        
            
            <p><?= nl2br(htmlspecialchars($prof['empresa_biografia'])) ?></p>
            <p><?= nl2br(htmlspecialchars($prof['empresa_servicos'])) ?></p>

            <p><strong>Contato:</strong> <?= htmlspecialchars($prof['empresa_telefone']) ?></p>

            <?php if ($logado): ?>
              <form method="POST" action="">
                <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id_usuario) ?>">
                <input type="hidden" name="id_prestadora" value="<?= htmlspecialchars($id_prestadora) ?>">
                <?php if($_SESSION['tipo'] === 'profissional'): ?>
                  <button type="button" onclick="mostrarModal('Prestadoras não podem solicitar serviços.');" class="solicitar-btn">
                    Solicitar Serviço
                  </button>
                <?php else: ?>
                  <button type="submit" name="solicitar" class="solicitar-btn">Solicitar Serviço</button>
                <?php endif; ?>
              </form>
            <?php else: ?>
              <a href="../html/login.php" class="solicitar-btn">Entrar para Solicitar</a>
            <?php endif; ?>

  <?php if (isset($_GET['success'])): ?>
    <script>mostrarModal('Solicitação enviada com sucesso!');</script>
  <?php elseif (isset($errorMsg)): ?>
    <script>mostrarModal('<?= addslashes($errorMsg) ?>');</script>
  <?php endif; ?>

        </div>
    </div>

      <div class="banners">
        <img src="<?= htmlspecialchars($prof['banner1']) ?>" alt="Banner 1" class="banner-principal">
        <div class="mini-banners">
          <img src="<?= htmlspecialchars($prof['banner2']) ?>" alt="Banner 2" >
          <img src="<?= htmlspecialchars($prof['banner3']) ?>" alt="Banner 3" >
        </div>
      </div>
    </section>
  </main>
  <script src="../js/login.js"></script>
  <script>
    // ===========================================================
    // Exibir ou ocultar o botão "ENTRAR" com base no status de login. Se estiver logado as informações do perfil aparecem
    // ===========================================================
      const logado = <?= json_encode($logado) ?>;
      if (!logado) {
        document.getElementById("perfil-area").style.display = "none";
        document.getElementById("btn-entrar").style.display = "block";
      } else {
        document.getElementById("perfil-area").style.display = "block";
        document.getElementById("btn-entrar").style.display = "none";
      }
    // ===========================================================
    // Fim do exibir ou ocultar o botão "ENTRAR" com base no status de login. Se estiver logado as informações do perfil aparecem
    // ===========================================================    
  </script>
</body>
<script src="../js/login.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const url = new URL(window.location.href);
    const success = url.searchParams.get("success");
    const erro = url.searchParams.get("erro");

    if (success) {
        mostrarModal("Solicitação enviada com sucesso!");
    }

    if (erro) {
        mostrarModal(erro);
    }
});
</script>
<script src="\Programacao_TCC_Avena\js\cookies.js"></script>
</html>
