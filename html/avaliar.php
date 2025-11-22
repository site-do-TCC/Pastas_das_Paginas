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
$avaliador_tipo = $_SESSION["tipo"]; // cliente ou profissional
if ($avaliador_tipo == 'profissional') {
    $avaliador_tipo = 'prestadora';
}
    

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


    

    $sqlInsert = "
        INSERT INTO avaliacoes 
        (avaliador_id, avaliador_tipo, avaliado_id, avaliado_tipo, nota, comentario)
        VALUES 
        ('$avaliador_id', '$avaliador_tipo', '$avaliado_id', '$avaliado_tipo', '$nota', '$comentario')
    ";
    
    print_r($sqlInsert);

    echo resultado($conexao, $sqlInsert);
    
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


  




  <main class="container">



   
   <h2>Avaliando: <?= htmlspecialchars($avaliado["nome"]) ?></h2>

<form action="avaliar.php?id=<?= $avaliado_id ?>" method="POST">
    <input type="hidden" name="avaliado_id" value="<?= $avaliado_id ?>">

    <div class="stars" required>
        <i class="star" data-value="1" onclick="estrelaUm()" id="1" required>★</i>
        <i class="star" data-value="2" onclick="estrelaDois()" id="2" required>★</i>
        <i class="star" data-value="3"  onclick="estrelaTres()" id="3" required>★</i>
        <i class="star" data-value="4"  onclick="estrelaQuatro()" id="4" required>★</i>
        <i class="star" data-value="5"  onclick="estrelaCinco()" id="5" required>★</i>
    </div>

    <input type="hidden" id="nota" name="nota">

    <textarea name="comentario" placeholder="Escreva um comentário..." required></textarea>

    <button type="submit" class="btn-enviar" name="submit">Enviar Avaliação</button>
</form>

  </main>

 

</body>
   <script src="../js/login"></script> 
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

<?php
function resultado($conexao, $sqlInsert){
if (mysqli_query($conexao, $sqlInsert)) {
        header("Location: avaliarLista.php?ok=1");
    } else {
        echo "Erro ao salvar avaliação: " . mysqli_error($conexao);
    }
}
?>