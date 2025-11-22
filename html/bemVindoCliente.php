<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include_once('../php/conexao.php');

//$nome = "Usuário";


if (!isset($conexao)) {
    die("Erro: conexão com o banco não encontrada. Verifique ../php/conexao.php");
}

if (!empty($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conexao->prepare("SELECT nome FROM cliente WHERE id_usuario = ?");
    if ($stmt === false) {

    } else {
        $stmt->bind_param("i", $id_usuario);
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
    $stmt = $conexao->prepare("SELECT imgperfil FROM cliente WHERE id_usuario = ?");
    if ($stmt === false) {

    } else {
        $stmt->bind_param("i", $id_usuario);
        $executou = $stmt->execute();
        if ($executou) {
            $resultado = $stmt->get_result();
            if ($resultado && $resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                if (!empty($row['imgperfil'])) {
                    $img = $row['imgperfil'];
                    
                }
            } else {
                
            }
        } else {
            
        }
        $stmt->close();
        
    }
}


// =============================================
// BUSCAR NOTIFICAÇÕES DO CLIENTE
// =============================================
$notificacoes = [];
$stmt = $conexao->prepare("
    SELECT id, mensagem, visualizado, data
    FROM notificacoes
    WHERE id_usuario = ?
    ORDER BY id DESC
");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $notificacoes[] = $row;
}
$stmt->close();

// Contar notificações não lidas
$notif_nao_lidas = 0;
foreach ($notificacoes as $n) {
    if ($n['visualizado'] == 0) $notif_nao_lidas++;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel - Avena</title>

 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

 
  <link rel="stylesheet" href="../css/Login.css">
  <link rel="stylesheet" href="../css/bemVindoCliente.css"> 
</head>
<body>

  <!-- ===============================
       Menu Lateral
       =============================== -->
  <nav id="menu" class="hidden">
    <ul>
      <li><a href=".\quemSomos.php">Quem somos</a></li>
      <li><a href=".\cadastro.php">Cadastrar-se</a></li>
      <hr>
      <li><a href=".\sejaParceiro.php">Seja um Parceiro</a></li>
      <li><a href=".\Pagina_Inicial.html"><span class="Home">Home</span></a></li>
    </ul>
  </nav>

  <!-- ===============================
       Banner de Consentimento de Cookies
       =============================== -->
  <div id="cookie-banner" class="cookie-banner">
    <div class="cookie-content">
      <h4>Privacidade e Cookies</h4>
      <p>
        A Singularity Solutions utiliza cookies para oferecer uma experiência mais personalizada,
        melhorar o desempenho da plataforma e garantir o funcionamento seguro dos serviços.
        Ao aceitar, você concorda com o uso de cookies conforme nossa
        <a href="\Programacao_TCC_Avena\img\AVENA - Termos de Uso e Política de Privacidade.pdf" target="_blank">
          Política de Privacidade
        </a>.
      </p>
      <div class="cookie-buttons">
        <button id="accept-cookies" class="cookie-btn accept">Aceitar</button>
        <button id="decline-cookies" class="cookie-btn decline">Recusar</button>
      </div>
    </div>
  </div>




  <header>
    <nav>
      <div class="logo">
        <a href="Pagina_Inicial.html">
          <img src="../img/logoAvena.png" alt="Logo Avena">
        </a>
      </div>

      <div class="perfil-area">
        <span class="nome"><?php echo $nome ?></span>

       
        <img src="<?php  echo $img?>" alt="Foto de perfil" class="perfil-foto">

        <!-- Ícone de Notificações -->
<div class="notificacoes-container">
    <button id="btn-notificacoes" class="notif-btn">
        🔔
        <?php if ($notif_nao_lidas > 0): ?>
            <span class="notif-count"><?php echo $notif_nao_lidas; ?></span>
        <?php endif; ?>
    </button>

    <!-- DROPDOWN DAS NOTIFICAÇÕES -->
    <div id="notif-dropdown" class="notif-dropdown hidden">
        <?php if (count($notificacoes) === 0): ?>
            <p class="vazio">Nenhuma notificação.</p>
        <?php else: ?>
            <?php foreach ($notificacoes as $n): ?>
                <div class="notif-item <?php echo $n['visualizado'] ? '' : 'nao-lida'; ?>">
                    <p><?php echo $n['mensagem']; ?></p>
                    <small><?php echo $n['data']; ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>



        <button class="menu-icon" id="menu-btn">&#9776;</button>
      </div>
    </nav>
  </header>


  




  <main class="conteudo">
    <div class="container">
      <h2>Bem-vindo de volta, <?php echo $nome; ?>!</h2>
      <p>Encontre prestadoras de serviços qualificadas para as suas necessidades.</p>

      <div class="botoes">
        <a href="busca.php" class="btn buscar">🔍 Buscar Serviços</a>
        <a href="agendaCliente.php" class="btn agenda">📅 Minha Agenda</a>
        <a href="contato.html" class="btn mensagens">💬 Mensagens</a>
        <a href="avaliarLista.php" class="btn avaliacoes">⭐ Minhas Avaliações</a>
      </div>
    </div>
  </main>

 
  <script>
   
   (<?php echo json_encode($_SESSION); ?>);
  </script>
  <script>
document.getElementById("btn-notificacoes").addEventListener("click", () => {
    document.getElementById("notif-dropdown").classList.toggle("hidden");

    // Marcar como lidas via AJAX
    fetch("../php/marcar_notificacoes.php");
});
</script>
</body>
   <script src="../js/login.js"></script> 
  <script src="\Programacao_TCC_Avena\js\cookies.js"></script>
</html>
