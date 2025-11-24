<?php
session_start();
include_once(__DIR__ . '/../php/conexao.php');

// Normaliza: se o array cliente/prestadora tem id_usuario, garante a chave id_cliente/id_prestadora
if (isset($_SESSION['cliente']['id_usuario']) && empty($_SESSION['id_cliente'])) {
    $_SESSION['id_cliente'] = $_SESSION['cliente']['id_usuario'];
}
if (isset($_SESSION['prestadora']['id_usuario']) && empty($_SESSION['id_prestadora'])) {
    $_SESSION['id_prestadora'] = $_SESSION['prestadora']['id_usuario'];
}

// Prioriza nome vindo da sessão; se não houver, tenta buscar no banco cobrindo ambas as colunas possíveis
$nome = 'Visitante';
if (!empty($_SESSION['prestadora']['nome'])) {
    $nome = htmlspecialchars($_SESSION['prestadora']['nome']);
} elseif (!empty($_SESSION['cliente']['nome'])) {
    $nome = htmlspecialchars($_SESSION['cliente']['nome']);
} else {
    // tenta puxar do banco usando id disponível (verifica id_usuario OU id_prestadora/id_cliente)
    if (!empty($_SESSION['id_prestadora'])) {
        $id = (int) $_SESSION['id_prestadora'];
        $stmt = $conexao->prepare("SELECT nome FROM prestadora WHERE id_usuario = ? OR id_prestadora = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("ii", $id, $id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $nome = $row['nome'];
            }
            $stmt->close();
        }
    } elseif (!empty($_SESSION['id_cliente'])) {
        $id = (int) $_SESSION['id_cliente'];
        $stmt = $conexao->prepare("SELECT nome FROM cliente WHERE id_usuario = ? OR id_cliente = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("ii", $id, $id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $nome = $row['nome'];
            }
            $stmt->close();
        }
    }
}

$foto = "../img/SemFoto.jpg"; // Foto padrão

if (!isset($conexao)) {
  die("Erro: conexão com o banco não encontrada. Verifique ../php/conexao.php");
}

// ==================== OPCIONAL: PUXA NOME ATUALIZADO DO BANCO ====================
// Só busca o nome no banco se for cliente OU prestadora, conforme tipo atual
if (!empty($_SESSION['id_cliente'])) {
  $id = (int)$_SESSION['id_cliente'];
  $stmt = $conexao->prepare("SELECT nome FROM cliente WHERE id_usuario = ?");
  if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
      $row = $res->fetch_assoc();
      $nome = $row['nome'];
    }
    $stmt->close();
  }
} elseif (!empty($_SESSION['id_prestadora'])) {
  $id = (int)$_SESSION['id_prestadora'];
  $stmt = $conexao->prepare("SELECT nome FROM prestadora WHERE id_usuario = ?");
  if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
      $row = $res->fetch_assoc();
      $nome = $row['nome'];
    }
    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat</title>
  <link rel="stylesheet" href="../css/chat.css">
  <link rel="stylesheet" href="../css/header_nav.css">
</head>
<body class="fixed-header-page">
  <link rel="stylesheet" href="/Programacao_TCC_Avena/css/header_nav.css">
  <?php include_once(__DIR__ . '/../php/header_nav.php'); ?>
  <script>
  // Fallback para garantir funcionamento do botão de menu no chat
  (function(){
    function bindMenu(){
      var btn = document.getElementById('menu-btn');
      var menu = document.getElementById('menu');
      if(!btn || !menu) return;
      if(btn.__bound) return; btn.__bound = true;
      btn.addEventListener('click', function(){
        menu.classList.toggle('show');
        if(!menu.classList.contains('show')) menu.classList.add('hidden'); else menu.classList.remove('hidden');
      });
    }
    if(document.readyState !== 'loading') bindMenu(); else document.addEventListener('DOMContentLoaded', bindMenu);
    // Rebind após 1s caso algum script remova listener
    setTimeout(bindMenu, 1000);
  })();
  </script>
  <script>
    // Marca visita ao chat para esconder banners de "novo chat" nas páginas de bem-vindo
    try { localStorage.setItem('openedChatPage', String(Date.now())); } catch(e) {}
  </script>

  <main class="chat-container" role="main">
    <aside class="chat-sidebar" aria-label="Lista de chats">
      <div class="search-bar">
        <input id="search-input" type="text" placeholder="Pesquisar...">
      </div>
      <div class="chat-list" id="chat-list"></div>
      <div class="test-tools" style="margin:12px 8px; display:flex; flex-direction:column; gap:8px;">
        <button id="btn-clear-messages" type="button" style="padding:8px 10px; border:none; border-radius:8px; background:#e2e8f0; cursor:pointer; font-size:13px;" disabled>Apagar mensagens do chat</button>
        <button id="btn-delete-chat" type="button" style="padding:8px 10px; border:none; border-radius:8px; background:#fecaca; cursor:pointer; font-size:13px;" disabled>Apagar chat completo</button>
        <div id="test-tools-status" style="font-size:12px; color:#555; min-height:18px;"></div>
      </div>
    </aside>

    <section class="chat-content" aria-live="polite">
      <div class="chat-header">
        <div class="chat-user">
          <a href="/Programacao_TCC_Avena/html/EdicaoPerfilGeral.php" id="chat-user-photo" class="user-photo placeholder" title="Editar Perfil" style="display:flex;align-items:center;justify-content:center;cursor:pointer;text-decoration:none;">
            <img id="chat-user-photo-img" src="../img/SemFoto.jpg" alt="Foto" style="display:none;" />
          </a>
          <div class="user-info">
            <h3 id="chat-user-name">Avena</h3>
            <span id="chat-user-status" class="status" aria-live="polite"></span>
          </div>
        </div>
      </div>

      <div class="chat-messages" id="chat-messages"></div>

      <div id="attached-bar" class="attachments-bar" aria-live="polite" aria-label="Arquivos anexados" hidden></div>
      <div class="chat-input" id="chat-input-area">
        <button id="attach-btn" type="button" aria-label="Anexar arquivo">
          <img src="../img/botaoUpload.png" alt="Anexar">
        </button>
        <textarea id="message-input" placeholder="Digite uma mensagem" rows="1"></textarea>
        <button id="send-btn" type="button" aria-label="Enviar mensagem">
          <img src="../img/botaoMandar.png" alt="Enviar">
        </button>
  <input id="file-input" type="file" style="display:none" multiple accept="image/*,video/mp4,video/webm,video/ogg,audio/mpeg,audio/ogg,audio/wav,application/pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" />
      </div>
    </section>
  </main>

  <script>
    // Diagnóstico rápido caso o JS principal não carregue
    window.addEventListener('DOMContentLoaded', () => {
      if (!document.getElementById('chat-list')) {
        console.error('[chat] elemento #chat-list ausente');
      }
      if (typeof fetch !== 'function') {
        console.error('[chat] fetch API indisponível');
      }
      // Marca tempo de carga para inspeção
      window.__CHAT_BOOT_TS = Date.now();
    });
  </script>
  <script>
    // Lógica dos botões de teste (apagar mensagens / chat) integrando com endpoints
    (function(){
      const btnClear = document.getElementById('btn-clear-messages');
      const btnDelete = document.getElementById('btn-delete-chat');
      const statusEl = document.getElementById('test-tools-status');
      function getActiveChatId(){ return (window.chats||[]).find(c => c.id === window.activeChatId)?.chatId || null; }
      function refreshEnable(){
        const chatId = getActiveChatId();
        const enable = !!chatId && chatId > 0;
        btnClear.disabled = !enable; btnDelete.disabled = !enable;
      }
      window.__enableTestButtons = refreshEnable;
      async function post(url, data){
        const body = new URLSearchParams(); Object.entries(data).forEach(([k,v])=>body.append(k,v));
        const r = await fetch(url,{method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body});
        let j=null; try{ j=await r.json(); }catch{} return { ok:r.ok && j?.ok, status:r.status, data:j};
      }
      btnClear.addEventListener('click', async ()=>{
        const cid = getActiveChatId(); if(!cid){ statusEl.textContent='Nenhum chat ativo.'; return; }
        statusEl.textContent='Apagando mensagens...';
        const resp = await post('/Programacao_TCC_Avena/php/clearChatMessages.php',{ chat_id: cid });
        if(resp.ok){ statusEl.textContent='Mensagens apagadas.'; if(typeof window.refreshActiveChatOnce==='function'){ await window.refreshActiveChatOnce(); } }
        else { statusEl.textContent='Falha: '+(resp.data?.erro||resp.status); }
      });
      btnDelete.addEventListener('click', async ()=>{
        const cid = getActiveChatId(); if(!cid){ statusEl.textContent='Nenhum chat ativo.'; return; }
        if(!confirm('Apagar chat inteiro? Esta ação é irreversível.')) return;
        statusEl.textContent='Apagando chat...';
        const resp = await post('/Programacao_TCC_Avena/php/deleteChat.php',{ chat_id: cid });
        if(resp.ok){ statusEl.textContent='Chat apagado.'; if(typeof window.fetchChatList==='function'){ await window.fetchChatList(); } window.activeChatId=null; if(document.getElementById('chat-messages')) document.getElementById('chat-messages').innerHTML=''; }
        else { statusEl.textContent='Falha: '+(resp.data?.erro||resp.status); }
      });
      setInterval(refreshEnable,1000);
    })();
  </script>
  <script src="../js/chat.js"></script>
</body>
</html>
