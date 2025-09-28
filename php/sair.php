<?php
     session_start();
     unset($_SESSION['email']);
     unset($_SESSION['senha']);
     header('Location: \Programacao_TCC_Avena\html\login.php');
?>