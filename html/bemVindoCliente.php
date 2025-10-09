<?php
    session_start();
    
    //print_r($_SESSION);

    if((!isset($_SESSION['email']) == true) || (!isset($_SESSION['senha']) == true || $_SESSION['tipo'] == 'profissional')){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: \Programacao_TCC_Avena\html\login.php');
        }else{
            $logado = $_SESSION['email'];
        }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem vindo cliente</title>
    <link rel="stylesheet" href="../css/bemVindoCliente.css">
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
 
  <nav id="menulogin" class="hidden">
    <ul>
      <li><a href="sobre.html"><span class="quemSomos">Quem somos<span></a></li>
      <li><a href="cadastro.php">Cadastrar-se</a></li>
      <hr>
      <li><a href="contato.html">Seja um Parceiro</a></li>
      <li><a href="suporte.html">Suporte</a></li>
    </ul>
  </nav>

    
  <main class="container">
    <h1>Bem vindo de volta</h1>
    <p class="subtitle">Encontre prestadoras de serviços qualificadas para as suas necessidades.</p>

    <div class="buttons">
      <button class="btn pink">
        <span class="icon"></span>
        Buscar Serviços
      </button>

      <button class="btn purple">
        <span class="icon"></span>
        Minha Agenda
      </button>

      <button class="btn purple">
        <span class="icon"></span>
        Mensagens
      </button>

      <button class="btn pink">
        <span class="icon"></span>
        Avaliações
      </button>
    </div>
  </main>

    <?php
    
    echo "<h1>Bem vindo<ul>$logado</ul></h1>";
    
    ?>

    <a href="\Programacao_TCC_Avena\php\sair.php">Deslogar</a>

    <main class="login-container">
  <div class="login-card">
        <div class="login-form">
</body>
</html>