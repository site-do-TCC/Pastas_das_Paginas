<?php
include_once('../php/conexao.php');
include_once('../php/settings.php');

$nome = "Usuário";
$foto = "../img/SemFoto.jpg"; // Foto padrão

if (!isset($conexao)) {
  die("Erro: conexão com o banco não encontrada. Verifique ../php/conexao.php");
}

if (!empty($_SESSION['id_cliente'])) {
  $idCliente = (int) $_SESSION['id_cliente'];

  $stmt = $conexao->prepare("SELECT nome FROM cliente WHERE id_usuario = ?");
  if ($stmt === false) {
  } else {
    $stmt->bind_param("i", $idCliente);
    $executou = $stmt->execute();
    if ($executou) {
      $resultado = $stmt->get_result();
      if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        if (!empty($row['nome'])) {
          $nome = $row['nome'];
        }

      } else {

      }
    } else {

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
  <link rel="stylesheet" href="../css/Chat.css">
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

       
        <!-- <img src=<//
         ?php echo htmlspecialchars($foto); ?> alt="Foto de perfil" class="perfil-foto"> -->

     
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
            <span id="chat-user-status"> </span>
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
        <button class="send-btn" id="send-btn" value="send-value" title="Enviar">
          <div class="botaoMandarImg"><img src="../img/botaoMandar.png"></div>

        </button>
      </div>
    </section>
  </main>

  <script src="../js/chat.js"></script>
</body>

</html>