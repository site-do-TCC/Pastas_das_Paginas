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
</head>
<body>
  <header>
    <nav>
      <div class="logo">
        <a href="Pagina_Inicial.html">
          <img src="../img/logoAvena.png" alt="Logo Avena">
        </a>
      </div>

      <div class="perfil-area">
        <span class="nome"><?php echo htmlspecialchars($nome); ?></span>
        <button class="menu-icon" id="menu-btn">&#9776;</button>
      </div>
    </nav>
  </header>

  <main class="chat-container" role="main">
    <aside class="chat-sidebar" aria-label="Lista de chats">
      <div class="search-bar">
        <input id="search-input" type="text" placeholder="Pesquisar...">
      </div>
      <div class="chat-list" id="chat-list"></div>
    </aside>

    <section class="chat-content" aria-live="polite">
      <div class="chat-header">
        <div class="chat-user">
          <div id="chat-user-photo" class="user-photo placeholder"></div>
          <div class="user-info">
            <h3 id="chat-user-name">Avena</h3>
            <span id="chat-user-status" class="status" aria-live="polite"></span>
          </div>
        </div>
      </div>

      <div class="chat-messages" id="chat-messages"></div>

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
  <script src="../js/chat.js"></script>
</body>
</html>
