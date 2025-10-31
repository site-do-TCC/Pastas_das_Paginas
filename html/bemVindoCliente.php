<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include_once('../php/conexao.php');

$nome = "Usuário";


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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel - Avena</title>

 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

 
  <link rel="stylesheet" href="../css/Login.css">
  <link rel="stylesheet" href="../css/bemVindoCliente.css"> 
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

       
        <img src=../img/SemFoto.jpg alt="Foto de perfil" class="perfil-foto">

     
        <button class="menu-icon" id="menu-btn">&#9776;</button>
      </div>
    </nav>
  </header>


  <nav id="menulogin" class="hidden">
    <ul>
      <li><a href="sobre.html"><span class="quemSomos">Quem somos</span></a></li>
      <li><a href="cadastro.php">Cadastrar-se</a></li>
      <hr>
      <li><a href="contato.html">Seja um Parceiro</a></li>
      <li><a href="suporte.html">Suporte</a></li>
    </ul>
  </nav>

  <main class="conteudo">
    <div class="container">
      <h2>Bem-vindo de volta, <?php echo htmlspecialchars($nome); ?>!</h2>
      <p>Encontre prestadoras de serviços qualificadas para as suas necessidades.</p>

      <div class="botoes">
        <a href="busca.php" class="btn buscar">🔍 Buscar Serviços</a>
        <a href="agenda.php" class="btn agenda">📅 Minha Agenda</a>
        <a href="chat.php" class="btn mensagens">💬 Mensagens</a>
        <a href="avaliacoes.php" class="btn avaliacoes">⭐ Minhas Avaliações</a>
      </div>
    </div>
  </main>

  <script src="../js/login.js"></script> 
  <script>
   
   (<?php echo json_encode($_SESSION); ?>);
  </script>
</body>
</html>
