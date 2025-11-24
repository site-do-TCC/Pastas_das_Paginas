<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once(__DIR__ . '/../php/conexao.php'); // ajuste caminho se precisar


// -------------------- TRATAMENTO DO POST --------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitar'])) {
  // Detecta id do cliente logado usando novo padrão de sessão
  $id_contratante = null;
  if (!empty($_SESSION['cliente']['id_usuario'])) {
    $id_contratante = (int)$_SESSION['cliente']['id_usuario'];
  } elseif (!empty($_SESSION['prestadora']['id_usuario'])) {
    // Se for prestadora tentando solicitar, pode bloquear ou tratar diferente; aqui apenas impede.
    header("Location: ../html/login.php");
    exit;
  }
  if (!$id_contratante) {
    header("Location: ../html/login.php");
    exit;
  }

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

// ===== Detecta usuário logado independente da chave usada na sessão =====
$profLog = null; $logado = false; $id_usuario = null; $tipoSess = null;
if (!empty($_SESSION['cliente']['id_usuario'])) {
  $id_usuario = (int)$_SESSION['cliente']['id_usuario'];
  $tipoSess = 'cliente';
} elseif (!empty($_SESSION['prestadora']['id_usuario'])) {
  $id_usuario = (int)$_SESSION['prestadora']['id_usuario'];
  $tipoSess = 'profissional';
}
if ($id_usuario !== null) {
  $logado = true;
  if ($tipoSess === 'profissional') {
    $stmt = $conexao->prepare("SELECT * FROM prestadora WHERE id_usuario = ? LIMIT 1");
    if ($stmt) { $stmt->bind_param('i',$id_usuario); if ($stmt->execute()) { $res = $stmt->get_result(); $profLog = $res->fetch_assoc(); } $stmt->close(); }
  } else {
    $stmt = $conexao->prepare("SELECT * FROM cliente WHERE id_usuario = ? LIMIT 1");
    if ($stmt) { $stmt->bind_param('i',$id_usuario); if ($stmt->execute()) { $res = $stmt->get_result(); $profLog = $res->fetch_assoc(); } $stmt->close(); }
  }
  if (!$profLog) { $profLog = ['nome'=>'Usuário','imgperfil'=>'../img/SemFoto.jpg']; }
  if (empty($profLog['imgperfil'])) $profLog['imgperfil'] = '../img/SemFoto.jpg';
}

// remove debug print de sessão
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($prof['nome']) ?> | Avena</title>
  <link rel="stylesheet" href="../css/servico.css">
  <link rel="stylesheet" href="../css/header_nav.css">
</head>
<body class="fixed-header-page">

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

  <?php include_once(__DIR__ . '/../php/header_nav.php'); ?>
  <style>
    /* Failsafe: garante visibilidade e posicionamento do botão do menu */
    .global-header .menu-icon { display:inline-block !important; visibility:visible !important; width:auto !important; height:auto !important; }
    /* Eleva z-index do menu aberto para evitar ficar atrás de outros elementos */
    #menu.show { z-index: 9999 !important; }
  </style>
  <script>
    // Diagnóstico + reinserção agressiva se ainda ausente
    (function(){
      function ensureMenuBtn(){
        var btn = document.getElementById('menu-btn');
        var cluster = document.querySelector('.global-header .menu-cluster');
        if(!btn && cluster){
          btn = document.createElement('button');
          btn.id='menu-btn'; btn.className='menu-icon'; btn.type='button'; btn.innerHTML='\u2630';
          cluster.appendChild(btn);
        }
        if(btn){
          // Força estilos inline caso CSS não tenha carregado
          btn.style.background='#917ba4';
          btn.style.color='#fff';
          btn.style.fontSize='2.2em';
          btn.style.border='none';
          btn.style.cursor='pointer';
          btn.style.padding='0 6px 4px';
        }
        var menu = document.getElementById('menu');
        if(btn && menu && !btn.dataset.bound){
          btn.addEventListener('click', function(){
            menu.classList.toggle('show');
            if(!menu.classList.contains('show')){ menu.classList.add('hidden'); } else { menu.classList.remove('hidden'); }
          });
          btn.dataset.bound='1';
        }
      }
      console.log('[DEBUG] Inicializando ensureMenuBtn.');
      console.log('[DEBUG] menu-btn antes:', document.getElementById('menu-btn'));
      console.log('[DEBUG] menu nav antes:', document.getElementById('menu'));
      ensureMenuBtn();
      setTimeout(ensureMenuBtn,500);
      setTimeout(ensureMenuBtn,1200);
    })();
  </script>
  <script>
  // Injeção de fallback: se o botão não veio do include, cria um novo
  (function(){
    var existing = document.getElementById('menu-btn');
    if(!existing){
      var cluster = document.querySelector('.global-header .menu-cluster');
      if(cluster){
        var btn = document.createElement('button');
        btn.id = 'menu-btn';
        btn.className = 'menu-icon';
        btn.type = 'button';
        btn.innerHTML = '&#9776;';
        cluster.appendChild(btn);
      }
    }
  })();
  </script>

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

      <div class="dados">
        

        <div class="avaliacao">
          
        </div>
        
        <a href="#">57 Avaliações</a>

        
            
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
  <!-- login.js será carregado uma vez ao final -->
</body>
<script src="../js/login.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const url = new URL(window.location.href);
    const success = url.searchParams.get("success");
    const erro = url.searchParams.get("erro");

    if (success) {
        mostrarModal("Solicitação enviada com sucesso!");
        // Força exibição imediata da badge de chat no nav indicando novo chat disponível
        try {
          const badge = document.getElementById('global-chat-badge');
          if (badge) {
            // Notificação de solicitação: vermelho (não roxo)
            badge.style.display = 'inline-block';
            badge.classList.remove('new-chat');
            badge.style.background = '#dc2626';
            badge.style.animation = 'pulseBadge 1.3s ease-in-out infinite';
          }
          // Dispara evento customizado para outros scripts que queiram reagir
          document.dispatchEvent(new CustomEvent('chatPlaceholderCreated', { detail:{ source:'solicitacao', ts: Date.now() } }));
        } catch(e) { /* silencia */ }
        // Limpa o parâmetro success da URL para evitar reexibir modal ao voltar/atualizar
        try {
          const cleanUrl = window.location.pathname + '?id_prestadora=<?= (int)$id_prestadora ?>';
          window.history.replaceState({}, document.title, cleanUrl);
        } catch(e) { /* ignore */ }
    }

    if (erro) {
        mostrarModal(erro);
    }
});
</script>
<script src="\Programacao_TCC_Avena\js\cookies.js"></script>
<script>
// Fallback para garantir funcionamento do botão de menu caso algum script sobrescreva
(function(){
  function bindMenu(){
    var btn = document.getElementById('menu-btn');
    var menu = document.getElementById('menu');
    if(btn && menu && !btn.dataset.bound){
      btn.addEventListener('click', function(){
        menu.classList.toggle('show');
        if(!menu.classList.contains('show')){ menu.classList.add('hidden'); } else { menu.classList.remove('hidden'); }
      });
      btn.dataset.bound = '1';
    }
  }
  bindMenu();
  setTimeout(bindMenu, 800);
})();
</script>
<script>
// Fallback genérico: captura cliques no botão mesmo sem listener (delegação)
(function(){
  document.addEventListener('click', function(ev){
    if(ev.target && ev.target.id === 'menu-btn'){
      var menu = document.getElementById('menu');
      if(menu){
        menu.classList.toggle('show');
        if(!menu.classList.contains('show')){ menu.classList.add('hidden'); } else { menu.classList.remove('hidden'); }
        console.log('[DEBUG] Toggle via delegação. Estado show:', menu.classList.contains('show'));
      }
    }
  });
})();
</script>
</html>
