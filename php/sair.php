<?php
     session_start();
     unset($_SESSION['email']);
     unset($_SESSION['senha']);
     unset($_SESSION['id_usuario']);
     header('Location: \Programacao_TCC_Avena\html\login.php');

?>