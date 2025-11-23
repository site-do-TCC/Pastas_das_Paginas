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
    if (!empty($_POST['ajax'])) {
      header('Content-Type: application/json');
      echo json_encode(['ok'=>false,'erro'=>'Prestadoras não podem solicitar serviços.']);
      exit;
    } else {
      header("Location: ../html/login.php");
      exit;
    }
  }
  if (!$id_contratante) {
    if (!empty($_POST['ajax'])) {
      header('Content-Type: application/json');
      echo json_encode(['ok'=>false,'erro'=>'Faça login para solicitar.']);
      exit;
    } else {
      header("Location: ../html/login.php");
      exit;
    }
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
        if (!empty($_POST['ajax'])) {
          header('Content-Type: application/json');
          echo json_encode(['ok'=>true,'id_prestadora'=>$id_prestadora]);
          exit;
        } else {
          header("Location: servico.php?id_prestadora={$id_prestadora}&success=1");
          exit;
        }
    } else {
        if (!empty($_POST['ajax'])) {
          header('Content-Type: application/json');
          echo json_encode(['ok'=>false,'erro'=>'Erro ao inserir solicitação.']);
          exit;
        } else {
          echo "<script>mostrarModal('Erro ao solicitar serviço. Veja se id_contratante e id_prestadora existem.');</script>";
        }
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
        $href = '..\html\bemVindoPrestadora.php';
        $sqlPrestadora = "SELECT nome, imgperfil FROM prestadora WHERE id_usuario = ".$id_usuario;
        $resultadoPrestadora = mysqli_query($conexao, $sqlPrestadora);
        $profLog = mysqli_fetch_assoc($resultadoPrestadora);
    } else {
        $href = '..\html\bemVindoCliente.php';
        $sqlCliente = "SELECT nome, imgperfil FROM cliente WHERE id_usuario = ".$id_usuario;
        $resultadoCliente = mysqli_query($conexao, $sqlCliente);
        $profLog = mysqli_fetch_assoc($resultadoCliente);
    }
}else {
    $href = '..\html\Pagina_Inicial.html';
}


$resultMedia = mysqli_query($conexao, $sqlMedia);
$mediaAvaliacoes = mysqli_fetch_assoc($resultMedia)['media'];
$mediaAvaliacoes = $mediaAvaliacoes ? number_format($mediaAvaliacoes, 1) : "0.0";
//print_r($_SESSION); 
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
    <a href= <?= $href?> style="text-decoration:none;">

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
                <input type="hidden" name="ajax" value="1">
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
<script>
// Intercepta envio para AJAX e evita reload/redirect
(function(){
  const form = document.getElementById('solicitacao-form');
  if(!form || form.__boundAjax) return; form.__boundAjax = true;
  form.addEventListener('submit', async function(ev){
    // Se for prestadora, deixa lógica padrão do botão bloquear
    if (form.querySelector('button[onclick]')) return;
    ev.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    if(btn){ btn.disabled = true; btn.textContent = 'Enviando...'; }
    try {
      const fd = new FormData(form);
      const r = await fetch(window.location.href, { method:'POST', body: fd, credentials:'same-origin' });
      let data=null; try{ data = await r.json(); }catch{}
      if(data && data.ok){
        mostrarModal('Solicitação enviada com sucesso!');
        // Badge vermelha imediata
        const badge = document.getElementById('global-chat-badge');
        if(badge){ badge.style.display='inline-block'; badge.classList.remove('new-chat'); badge.style.background='#dc2626'; }
        // Limpa marca visto para forçar futura notificação se vier mensagem
        localStorage.removeItem('chatLastSeenMaxId');
      } else {
        mostrarModal(data?.erro || 'Falha ao solicitar.');
      }
    } catch(e){ mostrarModal('Erro de rede ao solicitar.'); }
    finally { if(btn){ btn.disabled=false; btn.textContent='Solicitar Serviço'; } }
  });
})();
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
