<?php
  if(isset($_POST['submit'])){
    print_r('Nome: ' . $_POST['nome']);
    print_r('<br>');
    print_r('Email: ' . $_POST['email']);
    print_r('<br>');
    print_r('Senha: ' . $_POST['senha']);
    print_r('<br>');
    print_r('Tipo: ' . $_POST['tipo']);
    print_r('<br>');
    print_r('<hr>');

    //if ($_POST['tipo'] == 'profissional'){
    //  print_r('É profissional');
    //}else{
    //  print_r('Não é profissional, é contratante!');
    //}

    include_once('C:\USBWebserver\root\Programacao_TCC_Avena\php\conexao.php');

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    print_r('Teste conexão: ' . $dbUsername);

    if ($_POST['tipo'] == 'profissional'){
      $result = mysqli_query($conexao, "INSERT INTO prestadora(nome,email,senha) VALUES ('$nome','$email','$senha')");
    }else{
      $result = mysqli_query($conexao, "INSERT INTO cliente(nome,email,senha) VALUES ('$nome','$email','$senha')");
    }
  }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Avena</title>
  <link rel="stylesheet" href="\Programacao_TCC_Avena\css\cadastro.css">
</head>
<body>
  <header>
    <div class="logo">
      <a href="Pagina_Inicial.html"><img src="\Programacao_TCC_Avena\img\logoAvena.png" alt="Logo Avena"></a>
    </div>
  </header>

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
          <label for="termos">Termos de Privacidade</label>
        </div>

        <button id="btnSubmit" type="submit" name="submit" class="btn-cadastrar">CADASTRAR-SE</button>
      </form>
    </div>

    <div class="image-section">
      
      <img src="\Programacao_TCC_Avena\img\imgCadastro.png" alt="Ilustração de cadastro">
    </div>
  </main>
</body>
  <script src="\js\cadastro.js"></script>
</html>