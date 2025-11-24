
<?php


session_start();


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Perfil</title>
    <link rel="stylesheet" href="/Programacao_TCC_Avena/css/header_nav.css">
    <link rel="stylesheet" href="/Programacao_TCC_Avena/css/EdicaoPerfil.css">

</head>



<body class="fixed-header-page">
<?php include_once(__DIR__ . '/../php/header_nav.php'); ?>


<!-- body já aberto acima com classe fixed-header-page -->
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



    <div class="headerPerfil">



        <div class="meuPerfil">
            <img src="\Programacao_TCC_Avena\img\meuPerfil.png" alt="Meu Perfil">
        </div>

    <form method="POST" enctype="multipart/form-data" action="EdicaoPerfilGeral.php">



        <div class="adicionarFoto">
            <!-- Input escondido -->
            <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*" hidden>

            <!-- Círculo clicável -->
            <label for="fotoPerfil" class="circuloUpload">
                <img id="previewFoto" src="/Programacao_TCC_Avena/img/adicionarFoto.png" alt="Adicionar Foto">
            </label>

            <div class="linha"></div>
        </div>



    </div>
    <!-- Container principal do formulário -->
    <div class="Formulario">

        <!-- Início do formulário -->
        

            <!-- Duas colunas: esquerda e direita -->
            <div class="form-container" style="display: flex; gap: 40px;">

                <!-- Coluna da esquerda -->
                <div class="colunaForm1">


                    <div class="campo">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Seu email pessoal" >
                    </div>

                    <div class="campo">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Sua senha da conta" >
                    </div>
            </div>
                <!-- Coluna da direita -->
                <div class="colunaForm2">
                    <div class="campo">
                        <label for="name">Nome</label>
                        <input type="name" id="facebook" name="nome" placeholder="Seu nome pessoal" >
                    </div>

                    <div class="campo">
                        <label for="localizacao">Localização</label>
                        <input type="text" id="localizacao" name="localizacao" placeholder="Seu local de atuação" >
                    </div>

                </div>

            </div> <!-- Fim das colunas -->
    </div>
<div class="botoes">
    <button class="btn-excluir" name="excluir" id="excluir">EXCLUIR CONTA</button>
    <button class="btn-salvar" name="salvar" id="salvar">SALVAR ALTERAÇÕES</button>
</div>
<a href="\Programacao_TCC_Avena\php\sair.php" class="btn-deslogar">DESLOGAR</a>
</div>


</body>
<script rel="preload" src="\Programacao_TCC_Avena\js\EdicaoPerfilCliente.js"></script>
<script src="../js/cadastro.js"></script>
<script src="\Programacao_TCC_Avena\js\cookies.js"></script> 
</html>



<?php

session_start();
mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once(__DIR__ . '/../php/conexao.php');

if (!isset($conexao) || !($conexao instanceof mysqli)) {
    die("❌ Erro: variável \$conexao não é uma instância válida de mysqli.<br>");
}

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo'])) {
    die("Sessão inválida. Faça login novamente.");
}

$id_usuario = $_SESSION['id_usuario'];

if (isset($_POST['salvar'])) {

    if ($_SESSION['tipo'] == 'cliente') {

        // Atualiza a imagem
        if (isset($_FILES['fotoPerfil']) && !empty($_FILES['fotoPerfil']['name'])) {
            $uploadDirRel = "../ImgPerfilCliente/";
            $uploadDirAbs = __DIR__ . "/../ImgPerfilCliente/";
            if (!is_dir($uploadDirAbs)) {
                mkdir($uploadDirAbs, 0755, true);
            }

            $extensao = pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION);
            $nomeArquivo = "perfil_" . $id_usuario . "." . $extensao;
            $caminhoDestinoRel = $uploadDirRel . $nomeArquivo;
            $caminhoDestinoAbs = $uploadDirAbs . $nomeArquivo;

            if (move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $caminhoDestinoAbs)) {
                $sqlUpdateImg = "UPDATE cliente SET imgperfil = ? WHERE id_usuario = ?";
                $stmtImg = $conexao->prepare($sqlUpdateImg);
                $stmtImg->bind_param("si", $caminhoDestinoRel, $id_usuario);
                $stmtImg->execute();
                $stmtImg->close();
            }
        }

        // Campos
        $senha = $_POST['senha'] ?? '';
        $localizacao = $_POST['localizacao'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';

        // ⚠️ Verifica se o e-mail já existe em outro usuário
        if (!empty($email)) {
            $sqlCheckEmail = "SELECT id_usuario FROM cliente WHERE email = ? AND id_usuario != ?";
            $stmtCheck = $conexao->prepare($sqlCheckEmail);
            $stmtCheck->bind_param("si", $email, $id_usuario);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo "❌ Este e-mail já está cadastrado em outra conta.<br>";
                $stmtCheck->close();
                exit;
            }
            $stmtCheck->close();
        }

        // UPDATE protegido com CASE WHEN
        $sql = "UPDATE cliente SET
            senha = CASE WHEN ? = '' THEN senha ELSE ? END,
            cliente_localizacao = CASE WHEN ? = '' THEN cliente_localizacao ELSE ? END,
            nome = CASE WHEN ? = '' THEN nome ELSE ? END,
            email = CASE WHEN ? = '' THEN email ELSE ? END
        WHERE id_usuario = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssssssi", 
            $senha, $senha,
            $localizacao, $localizacao,
            $nome, $nome,
            $email, $email,
            $id_usuario
        );

        if ($stmt->execute()) {
            echo "✅ Dados atualizados com sucesso!<br>";

            // Atualiza sessão
            if (!empty($email)) $_SESSION['email'] = $email;
            if (!empty($senha)) $_SESSION['senha'] = $senha;
        } else {
            echo "Erro ao atualizar: " . $stmt->error;
        }

        $stmt->close();
    } 

    //---------------------------------------
    // Parte da profissional
    //---------------------------------------

    if ($_SESSION['tipo'] == 'profissional') {

        // Atualiza a imagem
        if (isset($_FILES['fotoPerfil']) && !empty($_FILES['fotoPerfil']['name'])) {
            $uploadDirRel = "../ImgPerfilPrestadoras/";
            $uploadDirAbs = __DIR__ . "/../ImgPerfilPrestadoras/";
            if (!is_dir($uploadDirAbs)) {
                mkdir($uploadDirAbs, 0755, true);
            }

            $extensao = pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION);
            $nomeArquivo = "perfil_" . $id_usuario . "." . $extensao;
            $caminhoDestinoRel = $uploadDirRel . $nomeArquivo;
            $caminhoDestinoAbs = $uploadDirAbs . $nomeArquivo;

            if (move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $caminhoDestinoAbs)) {
                $sqlUpdateImg = "UPDATE prestadora SET imgperfil = ? WHERE id_usuario = ?";
                $stmtImg = $conexao->prepare($sqlUpdateImg);
                $stmtImg->bind_param("si", $caminhoDestinoRel, $id_usuario);
                $stmtImg->execute();
                $stmtImg->close();
            }
        }

        // Campos
        $senha = $_POST['senha'] ?? '';
        $localizacao = $_POST['localizacao'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';

        // ⚠️ Verifica se o e-mail já existe em outra prestadora
        if (!empty($email)) {
            $sqlCheckEmail = "SELECT id_usuario FROM prestadora WHERE email = ? AND id_usuario != ?";
            $stmtCheck = $conexao->prepare($sqlCheckEmail);
            $stmtCheck->bind_param("si", $email, $id_usuario);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo "❌ Este e-mail já está cadastrado em outra conta.<br>";
                $stmtCheck->close();
                exit;
            }
            $stmtCheck->close();
        }

        // UPDATE
        $sql = "UPDATE prestadora SET
            senha = CASE WHEN ? = '' THEN senha ELSE ? END,
            empresa_localizacao = CASE WHEN ? = '' THEN empresa_localizacao ELSE ? END,
            nome = CASE WHEN ? = '' THEN nome ELSE ? END,
            email = CASE WHEN ? = '' THEN email ELSE ? END
        WHERE id_usuario = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssssssi", 
            $senha, $senha,
            $localizacao, $localizacao,
            $nome, $nome,
            $email, $email,
            $id_usuario
        );

        if ($stmt->execute()) {
            echo "✅ Dados atualizados com sucesso!<br>";

            if (!empty($email)) $_SESSION['email'] = $email;
            if (!empty($senha)) $_SESSION['senha'] = $senha;
        } else {
            echo "Erro ao atualizar: " . $stmt->error;
        }

        $stmt->close();
    }
}
//Excluir conta
if (isset($_POST['excluir'])) { if ($_SESSION['tipo'] == 'cliente') { 
    $sqlDelete = "DELETE FROM cliente WHERE id_usuario = ?"; $stmtDelete = $conexao->prepare($sqlDelete); $stmtDelete->bind_param("i", $id_usuario); 
    if ($stmtDelete->execute()){
        echo "✅ Conta excluída com sucesso!<br>"; 
        session_destroy(); 
        echo "<script>window.location.href='../html/Pagina_Inicial.html';</script>"; 
        exit; 
    } 
    else{
       echo "Erro ao excluir conta: " . $stmtDelete->error; 
    } 
    $stmtDelete->close(); 
} if ($_SESSION['tipo'] == 'profissional') { 
    $sqlDelete = "DELETE FROM prestadora WHERE id_usuario = ?"; 
    $stmtDelete = $conexao->prepare($sqlDelete); 
    $stmtDelete->bind_param("i", $id_usuario); 
    if ($stmtDelete->execute()) { 
        echo "✅ Conta excluída com sucesso!<br>"; 
        session_destroy(); 
        echo "<script>window.location.href='../html/Pagina_Inicial.html';</script>"; 
        exit; 
} else{ 
    echo "Erro ao excluir conta: " . $stmtDelete->error; } $stmtDelete->close(); 
} 

}
?>