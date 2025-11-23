<?php
session_start();
include("../php/conexao.php");

// Simulação: depois você coloca o ID da prestadora logada via login
if (!isset($conexao)) {
    die("Erro: conexão com o banco não encontrada. Verifique ../php/conexao.php");
}

if (!empty($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conexao->prepare("SELECT nome FROM prestadora WHERE id_usuario = ?");
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




$stmt = $conexao->prepare("SELECT imgperfil FROM prestadora WHERE id_usuario = ?");
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
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel da Prestadora - Avena</title>
  <link rel="stylesheet" href="../css/bemVindoPrestadora.css">
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
        <span class="nome"><?php echo htmlspecialchars($nome); ?></span>
        <img src="<?php echo htmlspecialchars($img); ?>" alt="Foto de perfil" class="perfil-foto">
        
      </div>
    </nav>
  </header>

  <main class="conteudo">
    <h2>Bem vinda de volta, <?php echo htmlspecialchars($nome); ?>!</h2>
    <p>Gerencie seus serviços e encontre novas oportunidades hoje.</p>

    <div class="botoes">
      <a href="..\html\EdicaoPerfilGeral.php" class="btn editar-perfil">⚙️ Editar Perfil</a>
      <a href="..\html\EditarServico.php" class="btn editar-servicos">🖋️ Editar Serviços</a>
      <a href="#" class="btn mensagens">💬 Mensagens</a>
      <a href="../html/avaliarLista.php" class="btn avaliacoes">⭐ Avaliações</a>
      <a href="..\html\cursos.php" class="btn cursos">🎓 Cursos</a>
      <a href="..\html\agendaPrestadora.php" class="btn agenda">📅 Minha Agenda</a>
    </div>
  </main>

  
</body>

   <script src="../js/login.js"></script>
  <script src="\Programacao_TCC_Avena\js\cookies.js"></script>
</html>
