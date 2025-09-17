<?php
    if(isset($_POST['submit'])){



        
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
</head>
<body>
    <form action=" \Programacao_TCC_Avena\html\recuperaSenha.php" method="POST">
        <div class="mb-3">
          <label for="email">E-mail</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="senha">Senha</label>
          <div class="input-group">
          <input type="password" name="senha" id="senha" class="form-control" required>
          
         </div>
         

        </div>
        <button type="submit" class="btn-login" name="submit" >ENTRAR</button>
        <p class="signup">Ainda não está no Avena? <a href="cadastro.php">Crie uma Conta.</a></p>
      </form>
</body>
</html>