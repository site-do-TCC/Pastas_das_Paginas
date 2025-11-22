<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once(__DIR__ . '/../php/conexao.php');

if(!isset($_SESSION["id_usuario"]) || !isset($_SESSION["tipo"])) {
    echo "Erro: usuário não logado";
    exit;
}

$logado_id = $_SESSION["id_usuario"];
$tipo = $_SESSION["tipo"];

// Se cliente → lista prestadoras
if ($tipo == "cliente") {
    $sql = "SELECT id_usuario, nome, imgperfil FROM prestadora";
    $sqlUser = "SELECT nome, imgperfil FROM cliente WHERE id_usuario = $logado_id";
}
// Se prestadora → lista clientes
else {
    $sql = "SELECT id_usuario, nome, imgperfil FROM cliente";
    $sqlUser = "SELECT nome, imgperfil FROM prestadora WHERE id_usuario = $logado_id";
}

$result = mysqli_query($conexao, $sql);
$resultUser = mysqli_query($conexao, $sqlUser);
$info = mysqli_fetch_assoc($resultUser);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel - Avena</title>

 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

 
  

  <link rel="stylesheet" href="../css/avaliarLista.css"> 
</head>
<body>



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

 <!-- Mensagem -->
    <div id="modalErro" class="modal">
        <div class="modal-content">
            <p id="mensagemErro">E-mail ou senha incorretos</p>
            <button onclick="fecharModal()">OK</button>
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
        <span class="nome"><?php echo $info['nome'] ?></span>

       
        <img src="<?php  echo $info['imgperfil']?>" alt="Foto de perfil" class="perfil-foto">

       
        
      </div>
    </nav>
  </header>


  




  <main>
    <h2>Escolha quem você quer avaliar</h2>

<div class="lista">
<?php while($row = mysqli_fetch_assoc($result)): ?>
    <div class="card">
        <img src="<?php  echo $row['imgperfil']?>" alt="Foto de perfil" class="perfil-foto">
        <p><?= htmlspecialchars($row["nome"]) ?></p>
        <a href="avaliar.php?id=<?= $row['id_usuario'] ?>">
            <button class="btn-avaliar">Avaliar</button>
        </a>
    </div>
<?php endwhile; ?>
</div>
  </main>

 

</body>
   <script src="../js/login.js"></script> 
  <script src="\Programacao_TCC_Avena\js\cookies.js"></script>
</html>

<script>

    const urlParams = new URLSearchParams(window.location.search);
    const ok = urlParams.get('ok');

    if (ok === '1') {
        mostrarModal("Avaliação salva com sucesso!");
    }

</script>
