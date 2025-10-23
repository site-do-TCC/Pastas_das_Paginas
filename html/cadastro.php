


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Avena</title>
  <link rel="stylesheet" href="\Programacao_TCC_Avena\css\cadastro.css">

</head>
<body>

 <!-- Mensagem -->
 <div id="modalErro" class="modal">
        <div class="modal-content">
            <p id="mensagemErro">E-mail não encontrado!</p>
            <button onclick="fecharModal()">OK</button>
        </div>
  </div>

  <!-- Menu -->
  <nav id="menu" class="hidden">
    <ul>
      <li><a href="#">Quem somos</a></li>
      <li><a href="\Programacao_TCC_Avena\html\cadastro.php"><span class="Cadastro">Cadastrar-se</span></a></li>
      <hr>
      <li><a href="#">Seja um Parceiro</a></li>
      <li><a href="#">Suporte</a></li>
    </ul>
  </nav>

  <header>
    <nav>
      <div class="logo">
        <a href="\Programacao_TCC_Avena\html\Pagina_Inicial.html"><img src="\Programacao_TCC_Avena\img\logoAvena.png" alt="Logo Avena"></a>
      </div>
      <div class="menu">
        <a class="btnEntrar" href="\Programacao_TCC_Avena\html\login.php" >Entrar</a>
        <button class="menu-icon" id="menu-btn">&#9776;</button>
      </div>
    </nav>
  </header>

  <!-- ===============================
     Banner de Consentimento de Cookies - Singularity Solutions
     =============================== -->
<div id="cookie-banner" class="cookie-banner">
  <div class="cookie-content">
  <h4>Privacidade e Cookies</h4>
  <p>
        A Singularity Solutions utiliza cookies para oferecer uma experiência mais personalizada,
        melhorar o desempenho da plataforma e garantir o funcionamento seguro dos serviços.
        Ao aceitar, você concorda com o uso de cookies conforme nossa
  <a href="\Programacao_TCC_Avena\img\AVENA - Termos de Uso e Política de Privacidade.pdf" target="_blank">Política de Privacidade</a>.
  </p>
  <div class="cookie-buttons">
  <button id="accept-cookies" class="cookie-btn accept">Aceitar</button>
  <button id="decline-cookies" class="cookie-btn decline">Recusar</button>
  </div>
  </div>
  </div>

  <main class="container">
    <div class="form-section">
      <form action="cadastro.php" method="POST">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Crie uma senha</label>
        <input type="password" id="senha" name="senha" required>

        <label for="tipo">Entrar como</label>
        <select id="tipo" name="tipo" required>
          <option value="">Selecione...</option>
          <option value="profissional">Profissional</option>
          <option value="contratante">Contratante</option>
        </select>

        <div class="termos">
          <input id="termos" type="checkbox" id="termos" required>
          <label for="termos"><a href="\Programacao_TCC_Avena\img\AVENA - Termos de Uso e Política de Privacidade.pdf" style="text-decoration:none;" target="_blank">Termos de Privacidade</a></label>
        </div>

        <button id="btnSubmit" type="submit" name="submit" class="btn-cadastrar">CADASTRAR-SE</button>
      </form>
    </div>

    <div class="image-section">
      
      <img src="\Programacao_TCC_Avena\img\imgCadastro.png" alt="Ilustração de cadastro">
    </div>
  </main>
  <script src="../js/cadastro.js"></script>
</body>
  
</html>


<?php


  error_reporting(E_ALL);
  ini_set('display_errors', 1);


  if(isset($_POST['submit'])){
    
    //print_r('Nome: ' . $_POST['nome']);
    //print_r('<br>');
    //print_r('Email: ' . $_POST['email']);
    //print_r('<br>');
    //print_r('Senha: ' . $_POST['senha']);
    //print_r('<br>');
    //print_r('Tipo: ' . $_POST['tipo']);
    //print_r('<br>');
    //print_r('<hr>');

    //if ($_POST['tipo'] == 'profissional'){
    //  print_r('É profissional');
    //}else{
    //  print_r('Não é profissional, é contratante!');
    //}

    include_once(__DIR__ . '/../php/conexao.php');

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    //print_r('Teste conexão: ' . $dbUsername);

    if ($_POST['tipo'] == 'profissional') {
    // verifica se já existe email na tabela prestadora
    $check = mysqli_query($conexao, "SELECT * FROM prestadora WHERE email = '$email'");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>mostrarModal('Esse e-mail já está cadastrado!');</script>";
    } else {
        $result = mysqli_query($conexao, "INSERT INTO prestadora(nome,email,senha) VALUES ('$nome','$email','$senha')");
        if ($result) {
            echo "<script>mostrarModal('Cadastro realizado com sucesso!');</script>";
            header('Location: \Programacao_TCC_Avena\html\login.php'); 
            //echo "Cadastro realizado com sucesso!";
        } else {
            echo "<script>mostrarModal('Erro ao cadastrar');</script>";
        }
    }
} else {
    // verifica se já existe email na tabela cliente
    $check = mysqli_query($conexao, "SELECT * FROM cliente WHERE email = '$email'");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>mostrarModal('Esse e-mail já está cadastrado!');</script>";
    } else {
        $result = mysqli_query($conexao, "INSERT INTO cliente(nome,email,senha) VALUES ('$nome','$email','$senha')");
        if ($result) {
            echo "<script>mostrarModal('Cadastro realizado com sucesso!');</script>";
            header('Location: \Programacao_TCC_Avena\html\login.php'); 
            //echo "Cadastro realizado com sucesso!";
        } else {
            echo "<script>mostrarModal('Erro ao cadastrar');</script>";
        }
    }
}

  }
?>

<script>
document.addEventListener("DOMContentLoaded", () => { 
  const cookieBanner = document.getElementById("cookie-banner");
  const acceptBtn = document.getElementById("accept-cookies");
  const declineBtn = document.getElementById("decline-cookies");

  const userConsent = localStorage.getItem("cookieConsent");

  // Se o usuário ainda não aceitou, mostra o banner
  if (userConsent !== "accepted") {
    cookieBanner.style.display = "block";
  }

  // Botão de aceitar → salva e nunca mais mostra
  acceptBtn.addEventListener("click", () => {
    localStorage.setItem("cookieConsent", "accepted");
    cookieBanner.style.display = "none";
  });

  // Botão de recusar → esconde, mas volta depois de um tempo
  declineBtn.addEventListener("click", () => {
    cookieBanner.style.display = "none";

    // Banner volta após 10 segundos (pode ajustar)
    setTimeout(() => {
      if (localStorage.getItem("cookieConsent") !== "accepted") {
        cookieBanner.style.display = "block";
      }
    }, 10000);
  });
});
</script>