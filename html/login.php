<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Esse é o login</title>
  <link rel="stylesheet" href="\Programacao_TCC_Avena\css\Login.css">
</head>

<body>
  <header>
  <nav>
     <div class="logo">
       <a href="Pagina_Inicial.html">
        <img src="\Programacao_TCC_Avena\img\logoAvena.png" alt="Logo Avena" href="Pagina_Inicial.html">
        </a>
      </div>
      <div class="menu">
        <button class="menu-icon" id="menu-btn">&#9776;</button>
      </div>  
  </nav>
</header>
 
  <nav id="menu-login" class="hidden">
    <ul>
      <li><a href="sobre.html"><span class="quemSomos">Quem somos<span></a></li>
      <li><a href="cadastro.php">Cadastrar-se</a></li>
      <hr>
      <li><a href="contato.html">Seja um Parceiro</a></li>
      <li><a href="suporte.html">Suporte</a></li>
    </ul>
  </nav>


<main class="login-container">
  <div class="login-card">
        <div class="login-form">

<!-- tela de erro

<div id="alertaErro" class="alerta">
  <div class="alerta-box">
    <p>E-mail ou senha inválidos.<br>
    <strong>Verifique seus dados e tente novamente!</strong></p>
    <button id="fecharAlerta">OK</button>
  </div>
</div>

-->
      <form action=" \Programacao_TCC_Avena\php\validarLogin.php" method="POST">
        <div class="mb-3">
          <label for="email">E-mail</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="senha">Senha</label>
          <div class="input-group">
          <input type="password" name="senha" id="senha" class="form-control" required>
          
         </div>
         
        <label for="tipo">Entrar como</label>
        <select id="tipo" name="tipo" required>
          <option value="">Selecione...</option>
          <option value="profissional">Profissional</option>
          <option value="contratante">Contratante</option>
        </select>

        </div>
        <a href="#" class="forgot">Esqueceu sua senha? </a>
        <button type="submit" class="btn-login" name="submit" >ENTRAR</button>
        <p class="signup">Ainda não está no Avena? <a href="cadastro.php">Crie uma Conta.</a></p>
      </form>
    </div>

     <div class="login-image">
        <img src="\Programacao_TCC_Avena\img\mulher cabelo preto.jpeg" alt="Ilustração login">
      </div>
    </div>
</main>

 

</body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

<script src="\Programacao_TCC_Avena\js\login.js"></script>
</html>