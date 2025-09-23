


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="\Programacao_TCC_Avena\css\recuperaSenha.css">
</head>
<body>

    <!-- Mensagem -->
    <div id="modalErro" class="modal">
        <div class="modal-content">
            <p id="mensagemErro">E-mail não encontrado!</p>
            <button onclick="fecharModal()">OK</button>
        </div>
    </div>

    <form action="\Programacao_TCC_Avena\html\recuperaSenha.php" method="POST">
        <div class="mb-3">
          <label for="email">E-mail</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>
         
        <label for="tipo">Tipo de cadastro</label>
        <select id="tipo" name="tipo" required>
          <option value="">Selecione...</option>
          <option value="profissional">Profissional</option>
          <option value="contratante">Contratante</option>
        </select>

        </div>
        <button type="submit" class="btn-login" name="submit" >ENTRAR</button>
        <p class="signup">Ainda não está no Avena? <a href="cadastro.php">Crie uma Conta.</a></p>
      </form>
</body>
<script src="\Programacao_TCC_Avena\js\recuperaSenha.js"></script>
</html>

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../phpMailer/Exception.php';
require __DIR__ . '/../phpMailer/PHPMailer.php';
require __DIR__ . '/../phpMailer/SMTP.php';

include_once('C:\usbwebserver_v8.6.5\usbwebserver\root\Programacao_TCC_Avena\php\conexao.php');

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $tipo  = $_POST['tipo'];

    // Gera a nova senha
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $NovaSenha = substr(str_shuffle($caracteres), 0, 5);

    $update = false;

    if ($tipo == 'profissional') {
        $check = mysqli_query($conexao, "SELECT * FROM prestadora WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $update = mysqli_query($conexao, "UPDATE prestadora SET senha = '$NovaSenha' WHERE email = '$email'");
        }
    } else {
        $check = mysqli_query($conexao, "SELECT * FROM cliente WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $update = mysqli_query($conexao, "UPDATE cliente SET senha = '$NovaSenha' WHERE email = '$email'");
        }
    }

    if ($update) {
        

        // Instância da classe PHPMailer
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        try {
            // Configurações do servidor
            $mail->isSMTP();
            $mail->SMTPAuth   = true;
            $mail->Username   = 'singularitysolutions.connect@gmail.com';
            $mail->Password   = 'esbcztzdlcojplyj'; // App Password
            $mail->SMTPSecure = 'tls';
            $mail->Host       = 'smtp.gmail.com';
            $mail->Port       = 587;

            // Ignorar verificação de certificado (se precisar)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true
                ]
            ];

            // Remetente
            $mail->setFrom('singularitysolutions.connect@gmail.com', 'Singularity Solutions');

            // Destinatário
            $mail->addAddress($email);

            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de senha - Singularity Solutions';
            $mail->Body    = '<b>Sua nova senha é:</b><br><br>' . $NovaSenha .
                             '<br><br> Para alterar sua senha, acesse as configurações do seu perfil e escolha <b>alterar senha</b>.';
            $mail->AltBody = 'Sua nova senha é: ' . $NovaSenha .
                             '. Para alterar sua senha, acesse as configurações do seu perfil e escolha "alterar senha".';

            // Enviar
            $mail->send();
            echo "<script>mostrarModal('Senha atualizada, verifique o e-mail para mais informações!');</script>";

        } catch (Exception $e) {
            echo "<script>mostrarModal('Esse e-mail não está cadastrado!');</script>";
        }
    } else {
        echo "<script>mostrarModal('Esse e-mail não está cadastrado!');</script>";
    }
}
?>