<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(__DIR__ . '/../php/conexao.php');


// VERIFICA LOGIN
if (!isset($_SESSION["id_usuario"])) {
    echo "Erro: usuário não logado";
    exit;
}

$avaliador_id = $_SESSION["id_usuario"];
$avaliador_tipo = $_SESSION["tipo"]; // cliente ou prestadora

$avaliado_id = $_GET["id"] ?? null;
if (!$avaliado_id) {
    echo "Erro: ID não informado";
    exit;
}


// DEFINIR TIPO DO AVALIADO
if ($avaliador_tipo == "cliente") {
    // cliente avalia prestadora
    $avaliado_tipo = "prestadora";
    $sql = "SELECT nome FROM prestadora WHERE id_usuario = $avaliado_id";
    $sqlUser = "SELECT nome, imgperfil FROM cliente WHERE id_usuario = $avaliador_id";
} else {
    // prestadora avalia cliente
    $avaliado_tipo = "cliente";
    $sql = "SELECT nome FROM cliente WHERE id_usuario = $avaliado_id";
    $sqlUser = "SELECT nome, imgperfil FROM prestadora WHERE id_usuario = $avaliador_id";
}

$query = mysqli_query($conexao, $sql);
$avaliado = mysqli_fetch_assoc($query);

$resultUser = mysqli_query($conexao, $sqlUser);
$info = mysqli_fetch_assoc($resultUser);


// SALVAR AVALIAÇÃO
if (isset($_POST['submit'])) {

    $avaliado_id = mysqli_real_escape_string($conexao, $_POST["avaliado_id"]);
    $nota        = mysqli_real_escape_string($conexao, $_POST["nota"]);
    $comentario  = mysqli_real_escape_string($conexao, $_POST["comentario"]);
<<<<<<< HEAD


    

    $sqlInsert = "
        INSERT INTO avaliacoes 
        (avaliador_id, avaliador_tipo, avaliado_id, avaliado_tipo, nota, comentario)
        VALUES 
        ('$avaliador_id', '$avaliador_tipo', '$avaliado_id', '$avaliado_tipo', '$nota', '$comentario')
    ";
    
    print_r($sqlInsert);

    echo resultado($conexao, $sqlInsert);
    
}


=======
    $data        = date("Y-m-d H:i:s");

    $sqlInsert = "
        INSERT INTO avaliacoes 
        (avaliador_id, avaliador_tipo, avaliado_id, avaliado_tipo, nota, comentario, data_avaliacao)
        VALUES 
        ('$avaliador_id', '$avaliador_tipo', '$avaliado_id', '$avaliado_tipo', '$nota', '$comentario', '$data')
    ";

    if (mysqli_query($conexao, $sqlInsert)) {
        header("Location: avaliar.php?ok=1");
        exit;
    } else {
        echo "Erro ao salvar avaliação: " . mysqli_error($conexao);
    }
}

>>>>>>> 40117b1 (sistema de avalações implementadas. O usuário pode agora criar uma avaliação. Agora preciso trazer as informações delas pras outras páginas como as de serviço.)
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel - Avena</title>

 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

 
  

  <link rel="stylesheet" href="../css/avaliar.css"> 
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

<<<<<<< HEAD
  <!-- Mensagem -->
    <div id="modalErro" class="modal">
        <div class="modal-content">
            <p id="mensagemErro">E-mail ou senha incorretos</p>
            <button onclick="fecharModal()">OK</button>
        </div>
    </div>
=======
>>>>>>> 40117b1 (sistema de avalações implementadas. O usuário pode agora criar uma avaliação. Agora preciso trazer as informações delas pras outras páginas como as de serviço.)



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


  
<<<<<<< HEAD
<!-- 
========================
// BOTÃO VOLTAR
========================
-->
<style>
  .arrow-animated {
    margin-left: 20px;
    margin-bottom: 10px;
    color: #917ba4;
    width: 30px;
    height: 30px;
    animation: floatLeft 1.6s ease-in-out infinite;
  }

  @keyframes floatLeft {
    0%   { transform: translateX(0); }
    50%  { transform: translateX(-2px); }
    100% { transform: translateX(0); }
  }
  h2{
    margin-left: 20px;  
  }
</style>
<a href= "..\html\avaliarLista.php">
<svg xmlns="http://www.w3.org/2000/svg" 
     width="20" height="20" fill="currentColor" 
     class="bi bi-arrow-left arrow-animated"
     viewBox="0 0 16 16">
  <path fill-rule="evenodd" 
        d="M5.854 4.146a.5.5 0 0 1 0 .708L3.707 7H14.5a.5.5 0 0 1 0 1H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0z"/>
</svg>
</a>
<!-- 
========================
// BOTÃO VOLTAR
========================
-->
=======

>>>>>>> 40117b1 (sistema de avalações implementadas. O usuário pode agora criar uma avaliação. Agora preciso trazer as informações delas pras outras páginas como as de serviço.)



  <main class="container">
<<<<<<< HEAD



   
=======
>>>>>>> 40117b1 (sistema de avalações implementadas. O usuário pode agora criar uma avaliação. Agora preciso trazer as informações delas pras outras páginas como as de serviço.)
   <h2>Avaliando: <?= htmlspecialchars($avaliado["nome"]) ?></h2>

<form action="avaliar.php?id=<?= $avaliado_id ?>" method="POST">
    <input type="hidden" name="avaliado_id" value="<?= $avaliado_id ?>">

<<<<<<< HEAD
    <div class="stars" required>
        <i class="star" data-value="1" onclick="estrelaUm()" id="1" required>★</i>
        <i class="star" data-value="2" onclick="estrelaDois()" id="2" required>★</i>
        <i class="star" data-value="3"  onclick="estrelaTres()" id="3" required>★</i>
        <i class="star" data-value="4"  onclick="estrelaQuatro()" id="4" required>★</i>
        <i class="star" data-value="5"  onclick="estrelaCinco()" id="5" required>★</i>
=======
    <div class="stars">
        <i class="star" data-value="1" onclick="estrelaUm()" id="1">★</i>
        <i class="star" data-value="2" onclick="estrelaDois()" id="2">★</i>
        <i class="star" data-value="3"  onclick="estrelaTres()" id="3" >★</i>
        <i class="star" data-value="4"  onclick="estrelaQuatro()" id="4">★</i>
        <i class="star" data-value="5"  onclick="estrelaCinco()" id="5">★</i>
>>>>>>> 40117b1 (sistema de avalações implementadas. O usuário pode agora criar uma avaliação. Agora preciso trazer as informações delas pras outras páginas como as de serviço.)
    </div>

    <input type="hidden" id="nota" name="nota">

<<<<<<< HEAD
    <textarea name="comentario" placeholder="Escreva um comentário..." required></textarea>
=======
    <textarea name="comentario" placeholder="Escreva um comentário..."></textarea>
>>>>>>> 40117b1 (sistema de avalações implementadas. O usuário pode agora criar uma avaliação. Agora preciso trazer as informações delas pras outras páginas como as de serviço.)

    <button type="submit" class="btn-enviar" name="submit">Enviar Avaliação</button>
</form>

  </main>

 

</body>
<<<<<<< HEAD
   <script src="../js/login"></script> 
=======
   <script src="../js/login.js"></script> 
>>>>>>> 40117b1 (sistema de avalações implementadas. O usuário pode agora criar uma avaliação. Agora preciso trazer as informações delas pras outras páginas como as de serviço.)
  <script src="\Programacao_TCC_Avena\js\cookies.js"></script>
  <script>
const stars = document.querySelectorAll(".star");
const nota = document.querySelector("#nota");

stars.forEach((star) => {
    star.addEventListener("click", () => {
        let value = star.dataset.value;
        nota.value = value;

        stars.forEach(s => s.classList.remove("selected"));
        for (let i = 0; i < value; i++) stars[i].classList.add("selected");
    });
});


let rating = 0;

stars.forEach(star => {
    star.addEventListener('click', function() {

        const value = parseInt(this.dataset.value);

        // Se clicar na mesma estrela -> limpa tudo
        if (value === rating) {
            rating = 0;
        } else {
            rating = value;
        }

        // Atualiza visual
        stars.forEach(s => {
            if (parseInt(s.dataset.value) <= rating) {
                s.classList.add('active');
            } else {
                s.classList.remove('active');
            }
        });
    });
});
</script>
</html>
<<<<<<< HEAD

<?php
function resultado($conexao, $sqlInsert){
if (mysqli_query($conexao, $sqlInsert)) {
        header("Location: avaliarLista.php?ok=1");
    } else {
        echo "Erro ao salvar avaliação: " . mysqli_error($conexao);
    }
}
?>
=======
>>>>>>> 40117b1 (sistema de avalações implementadas. O usuário pode agora criar uma avaliação. Agora preciso trazer as informações delas pras outras páginas como as de serviço.)
