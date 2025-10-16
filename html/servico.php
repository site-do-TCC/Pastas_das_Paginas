<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once(__DIR__ . '/../php/conexao.php');

 //Verifica se foi passado um ID na URL
if (!isset($_GET['id_usuario'])) {
    header("Location: busca.php");
    exit;
}

$id_usuario = intval($_GET['id_usuario']);
$sql = "SELECT * FROM prestadora WHERE id_usuario = $id_usuario";
$resultado = mysqli_query($conexao, $sql);

 //Se não encontrar, redireciona
if (mysqli_num_rows($resultado) == 0) {
  header("Location: busca.php");
  exit;
}

$prof = mysqli_fetch_assoc($resultado);
$logado = isset($_SESSION['id_usuario']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($prof['nome']) ?> | Avena</title>
  <link rel="stylesheet" href="\Programacao_TCC_Avena\css\servico.css">
</head>
<body>
  <header class="header">
    <div class="logo">
      <img src="\Programacao_TCC_Avena\img\logoAvena.png" alt="Logo Avena">
    </div>
    <a href="\Programacao_TCC_Avena\html\Pagina_Inicial.html" class="btn-entrar">ENTRAR</a>
  </header>

  <nav class="breadcrumb">
    <a href="\Programacao_TCC_Avena\html\Pagina_Inicial.html"></a> /
    <a href="busca.php" style="text-decoration:none;">Busca</a> /
    <span><?= htmlspecialchars($prof['nome']) ?></span>
  </nav>

  <main class="container">
    <section class="info">
    <div class="abaixo-img">
      <div class="perfil">
        <img src="<?= htmlspecialchars($prof['imgperfil'] ) ?>" class="foto-perfil" alt="<?= htmlspecialchars($prof['nome']) ?>">
        <h3>Sobre <?= htmlspecialchars($prof['nome']) ?></h3>
        <h2><?= htmlspecialchars($prof['nome']) ?></h2>
      </div>

      <div class="dados">
        
        <!--
        <div class="avaliacao">
          
        </div>
        
        <a href="#">57 Avaliações</a>
        -->
        
            
            <p><?= nl2br(htmlspecialchars($prof['empresa_biografia'])) ?></p>

            <p><strong>Contato:</strong> <?= htmlspecialchars($prof['empresa_telefone']) ?></p>

            <button class="solicitar-btn" onclick="solicitarServico()">SOLICITAR</button>
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

  <script>
    function solicitarServico() {
      const logado = <?= json_encode($logado) ?>;
      if (!logado) {
        window.location.href = "/Programacao_TCC_Avena/html/login.html";
      } else {
        alert("Serviço solicitado com sucesso! (aqui entra a função que você vai definir)");
      }
    }
  </script>
</body>
</html>
