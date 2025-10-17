<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat</title>
  <link rel="stylesheet" href="../css/Chat.css">
</head>
<body>
  <header>
    <nav>
      <div class="logo">
        <img src="../img/logoAvena.png" alt="Logo Avena" href="Pagina_Inicial.html">
      </div>
      <div class="menu">
        <button class="menu-icon" id="menu-btn">&#9776;</button>
      </div>
    </nav>
  </header>

  <!-- Menu lateral (oculto/abrível) -->
  <nav id="menu" class="hidden">
    <ul>
      <li><a href="#"><span class="quemSomos">Quem somos</span></a></li>
      <li><a href="../html/cadastro.php">Cadastrar-se</a></li>
      <hr>
      <li><a href="#">Seja um Parceiro</a></li>
      <li><a href="#">Suporte</a></li>
    </ul>
  </nav>

  <!-- ==================== CHAT BASE ==================== -->
  <main class="chat-container" role="main">
    <aside class="chat-sidebar" aria-label="Lista de chats">
      <div class="search-bar">
        <input id="search-input" type="text" placeholder="Pesquisar...">
      </div>

      <div class="chat-list" id="chat-list">
        <!-- Lista será populada por JS (fetchChatList) -->
      </div>
    </aside>

    <section class="chat-content" aria-live="polite">
      <div class="chat-header">
        <div class="chat-user">
          <div id="chat-user-photo" class="user-photo placeholder"></div>
          <div class="user-info">
            <h3 id="chat-user-name">Nome da Pessoa</h3>
            <span id="chat-user-status">Online</span>
          </div>
        </div>

        <div class="chat-actions">

        </div>
      </div>

      <div class="chat-messages" id="chat-messages">
        <!-- Mensagens (placeholder) serão renderizadas aqui -->
      </div>

      <div class="chat-input">
        <button class="attach-btn" id="attach-btn" title="Anexar">
            <div class="botaoUploadImg"><img src="../img/botaoUpload.png"></div>
        </button>
        <input id="message-input" type="text" placeholder="Digite uma mensagem...">
        <button class="send-btn" id="send-btn" title="Enviar">
            <div class="botaoMandarImg"><img src="../img/botaoMandar.png"></div>
            
        </button>
      </div>
    </section>
  </main>

  <script src="../js/chat.js"></script>
</body>
</html>
