<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once(__DIR__ . '/conexao.php');

function obterPerfilHeader($conexao) {
  $perfil = ['nome'=>null,'imgperfil'=>null];
  $id = null; $tabela = null;
  if (!empty($_SESSION['cliente']['id_usuario'])) { $id = (int)$_SESSION['cliente']['id_usuario']; $tabela = 'cliente'; }
  elseif (!empty($_SESSION['prestadora']['id_usuario'])) { $id = (int)$_SESSION['prestadora']['id_usuario']; $tabela = 'prestadora'; }
  if (!$tabela || !$id) { return $perfil; }
  $hasImg = false;
  $colRes = $conexao->query("SHOW COLUMNS FROM $tabela LIKE 'imgperfil'");
  if ($colRes && $colRes->num_rows) { $hasImg = true; }
  $select = $hasImg ? "SELECT nome, imgperfil FROM $tabela WHERE id_usuario = ? LIMIT 1" : "SELECT nome FROM $tabela WHERE id_usuario = ? LIMIT 1";
  $stmt = $conexao->prepare($select);
  if ($stmt) {
    $stmt->bind_param('i',$id);
    if ($stmt->execute()) {
      $r = $stmt->get_result();
      if ($r && $r->num_rows) {
        $dados = $r->fetch_assoc();
        $perfil['nome'] = $dados['nome'] ?? null;
        if ($hasImg) { $perfil['imgperfil'] = $dados['imgperfil'] ?? null; }
      }
    }
    $stmt->close();
  }
  return $perfil;
}
$perfilHeader = obterPerfilHeader($conexao);
// Permite injetar barra de busca dentro do header se a página definir $renderSearchBar=true
if(!isset($renderSearchBar)) { $renderSearchBar = false; }
?>
<header class="global-header">
  <link rel="stylesheet" href="/Programacao_TCC_Avena/public/chat-badge.css" />
  <div class="logo">
    <a href="/Programacao_TCC_Avena/html/Pagina_Inicial.html"><img src="/Programacao_TCC_Avena/img/logoAvena.png" alt="Logo Avena"></a>
  </div>
  <?php if($renderSearchBar){ ?>
    <div class="search-bar-inline">
      <input type="text" placeholder="Manicure" id="search_servico" name="search_servico" value="<?= isset($_GET['search_servico']) ? htmlspecialchars($_GET['search_servico']) : '' ?>">
      <span class="divider-inline">/</span>
      <input type="text" placeholder="Cidade ou Estado" id="search_localizacao" name="search_localizacao" value="<?= isset($_GET['search_localizacao']) ? htmlspecialchars($_GET['search_localizacao']) : '' ?>">
      <button type="button" class="pesquisa-btn-inline" id="pesquisa-btn-inline" aria-label="Buscar">🔍</button>
    </div>
  <?php } ?>
  <div class="menu-cluster">
    <?php if(!empty($perfilHeader['nome'])) { ?>
          <?php $isChatPage = (basename($_SERVER['SCRIPT_NAME']) === 'chat.php'); ?>
          <div class="perfil-area">
        <span class="nome"><?= htmlspecialchars($perfilHeader['nome']) ?></span>
        <?php $srcImg = (!empty($perfilHeader['imgperfil'])) ? $perfilHeader['imgperfil'] : '../img/SemFoto.jpg'; ?>
        <a href="/Programacao_TCC_Avena/html/EdicaoPerfilGeral.php" class="perfil-link" title="Editar Perfil">
          <img src="<?= htmlspecialchars($srcImg) ?>" alt="Perfil" class="perfil-foto" style="cursor:pointer;">
        </a>
      </div>
          <?php if(!$isChatPage) { ?>
            <a href="/Programacao_TCC_Avena/html/chat.php" class="chat-btn" id="global-chat-btn" title="Mensagens" aria-label="Mensagens" style="position:relative; display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:12px; background:#ff7f86; font-size:22px; text-decoration:none; color:#fff;">
              <span style="line-height:1">💬</span>
              <span class="badge-dot" id="global-chat-badge" style="display:none; position:absolute; top:-3px; right:-3px;"></span>
            </a>
          <?php } ?>
    <?php } else { ?>
      <a href="/Programacao_TCC_Avena/html/login.php" class="btn-entrar" id="btn-entrar">ENTRAR</a>
    <?php } ?>
    <button class="menu-icon" id="menu-btn">&#9776;</button>
  </div>
</header>
<nav id="menu" class="hidden">
  <ul>
    <li><a href="/Programacao_TCC_Avena/html/quemSomos.php">Quem somos</a></li>
    <li><a href="/Programacao_TCC_Avena/html/cadastro.php">Cadastrar-se</a></li>
    <hr>
    <li><a href="/Programacao_TCC_Avena/html/sejaParceiro.php">Seja um Parceiro</a></li>
    <li><a href="/Programacao_TCC_Avena/html/Pagina_Inicial.html"><span class="Home">Home</span></a></li>
  </ul>
</nav>
<script>
<script>
(function(){
  const src = '/Programacao_TCC_Avena/sounds/NovaMensagem.wav';
  const a = document.createElement('audio');
  a.src = src; a.preload = 'auto'; a.style.display='none';
  document.body.appendChild(a);
  const playFx = ()=>{
    try { a.currentTime = 0; a.play(); } catch(e) {}
  };
  // Não sobrescreve objeto existente; apenas garante playNew disponível.
  if (!window.__SND__) window.__SND__ = {};
  window.__SND__.playNew = window.__SND__.playNew || playFx;
})();

// Toca som quando badge aparece (primeira vez) ou quando muda para estado pulsante new-chat
(function(){
  setTimeout(bindMenu,700);

  // Barra de busca inline (se existir)
  const btnBusca = document.getElementById('pesquisa-btn-inline');
  if(btnBusca){
    btnBusca.addEventListener('click', function(){
      const s = encodeURIComponent(document.getElementById('search_servico').value);
      const l = encodeURIComponent(document.getElementById('search_localizacao').value);
      window.location = '/Programacao_TCC_Avena/html/busca.php?search_servico='+s+'&search_localizacao='+l;
    });
    document.addEventListener('keydown', function(ev){ if(ev.key==='Enter'){ btnBusca.click(); } });
  }

  // ====== Poll de chats para badge (unread ou novos) ======
  const chatBadgeEl = document.getElementById('global-chat-badge');
  let chatBadgeTimer = null;
  async function pollChatBadge(){
    if(!chatBadgeEl) return;
    try {
      const r = await fetch('/Programacao_TCC_Avena/php/getChatList.php', { credentials:'same-origin', cache:'no-store' });
      if(!r.ok) return;
      const data = await r.json();
      if(!data.ok) return;
      if (data.role && !window.chatRole) { window.chatRole = data.role; }
      const chats = Array.isArray(data.chats) ? data.chats : [];
      const anyNew = chats.some(c => c.newChat);
      const anyUnread = chats.some(c => c.unread > 0);
      const maxId = chats.reduce((m,c)=> Math.max(m, c.lastMessageId||0), 0);
      window.__CHAT_LAST_MAX_ID = maxId; // expõe para listener
      const lastSeen = parseInt(localStorage.getItem('chatLastSeenMaxId')||'0',10);
      // Regra: mostra se há novo chat ou maxId avançou além do visto.
      const shouldShow = (anyNew || maxId > lastSeen || anyUnread && maxId > lastSeen);
      chatBadgeEl.style.display = shouldShow ? 'inline-block' : 'none';
    } catch(e){ /* silencia */ }
  }
  function startChatBadge(){
    pollChatBadge();
    chatBadgeTimer = setInterval(pollChatBadge, 15000); // 15s
  }
  // Só inicia se logado (existe perfilHeader nome)
  <?php if(!empty($perfilHeader['nome'])) { ?> startChatBadge(); <?php } ?>
  window.__stopChatBadge = () => { if(chatBadgeTimer) clearInterval(chatBadgeTimer); };
  // Remove badge ao clicar e grava maxId atual como visto
  const chatBtn = document.getElementById('global-chat-btn');
  if(chatBtn && !chatBtn.__badgeClearBound){
    chatBtn.__badgeClearBound = true;
    chatBtn.addEventListener('click', function(){
      try {
        const maxId = window.__CHAT_LAST_MAX_ID || 0;
        localStorage.setItem('chatLastSeenMaxId', String(maxId));
        if(chatBadgeEl){ chatBadgeEl.style.display='none'; }
      } catch(e){}
    });
  }
})();
</script>
<script>
// Inicializa áudio de nova mensagem (usa arquivo existente NovaMensagem.wav)
(function(){
  const src = '/Programacao_TCC_Avena/sounds/NovaMensagem.wav';
  const a = document.createElement('audio');
  a.src = src; a.preload = 'auto'; a.style.display='none';
  document.body.appendChild(a);
  const playFx = ()=>{
    try { a.currentTime = 0; a.play(); } catch(e) {}
  };
  window.__SND__ = window.__SND__ || { playNew: playFx };
})();

// Toca som quando badge aparece (primeira vez) ou quando muda para estado pulsante new-chat
(function(){
  let prevVisible = false;
  let prevPulse = false;
  const badge = document.getElementById('global-chat-badge');
  const observer = new MutationObserver(()=>{
    if(!badge) return;
    const visible = badge.style.display !== 'none';
    const pulsing = badge.classList.contains('new-chat');
    // Som só se transição e não já visto em bem-vindo
    if ((visible && !prevVisible) || (visible && pulsing && !prevPulse)) {
      if (!localStorage.getItem('chatNotifSeen')) {
        if (!(Date.now() < (window.__SUPPRESS_SOUND_TS||0))) window.__SND__?.playNew();
      }
    }
    prevVisible = visible; prevPulse = pulsing;
  });
  if(badge){ observer.observe(badge, { attributes:true, attributeFilter:['style','class'] }); }
})();
</script>
