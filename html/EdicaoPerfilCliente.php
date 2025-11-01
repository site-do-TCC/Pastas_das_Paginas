
<?php
session_start();


?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Perfil</title>
    <link rel="stylesheet" href="\Programacao_TCC_Avena\css\EdicaoPerfil.css">

</head>



<header>
    <nav>
        <div class="logo">
            <a href="\Programacao_TCC_Avena\html\Pagina_Inicial.html"><img src="\Programacao_TCC_Avena\img\logoAvena.png" alt="Logo Avena"></a>
        </div>
        <div class="menu">

            <button class="menu-icon" id="menu-btn">&#9776;</button>
        </div>
    </nav>
</header>


<body>
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

    <form method="POST" enctype="multipart/form-data" action="EdicaoPerfilCliente.php">



        <div class="adicionarFoto">
            <!-- Input escondido -->
            <input type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*" hidden required>

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
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" placeholder="Contato da empresa" required>
                    </div>

                    <div class="campo">
                        <label for="localizacao">Localização</label>
                        <input type="text" id="localizacao" name="localizacao" placeholder="Sua região de atuação" required>
                    </div>
            </div>
                <!-- Coluna da direita -->
                <div class="colunaForm2">
                    <div class="campo">
                        <label for="facebook">Facebook</label>
                        <input type="url" id="facebook" name="facebook" placeholder="Coloque a url da conta">
                    </div>

                    <div class="campo">
                        <label for="instagram">Instagram</label>
                        <input type="text" id="instagram" name="instagram" placeholder="Coloque se @ ou a url">
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
// Prevent mysqli from throwing uncaught exceptions and show errors instead
mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once(__DIR__ . '/../php/conexao.php');

print_r($_SESSION);

if (isset($_POST['salvar'])) {

    // Recupera da sessão de forma segura
    $email = $_SESSION['email'] ?? null;
    $senha = $_SESSION['senha'] ?? null;

    if (!$email || !$senha) {
        echo "Sessão inválida. Faça login novamente.";
        exit;
    }

    // Busca o ID do usuário logado usando prepared statement
    $sqlSel = "SELECT id_usuario FROM cliente WHERE email = ? AND senha = ?";
    if (!($stmtSel = $conexao->prepare($sqlSel))) {
        echo "Erro no prepare SELECT: " . $conexao->error;
        exit;
    }
    $stmtSel->bind_param("ss", $email, $senha);
    if (!$stmtSel->execute()) {
        echo "Erro no execute SELECT: " . $stmtSel->error;
        $stmtSel->close();
        exit;
    }

    // try get_result(), fallback to bind_result if not available
    $result = $stmtSel->get_result();
    if ($result !== false) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_usuario = (int)$row['id_usuario'];
        } else {
            echo "Usuário não encontrado.";
            $stmtSel->close();
            exit;
        }
    } else {
        // fallback
        $stmtSel->store_result();
        if ($stmtSel->num_rows > 0) {
            $stmtSel->bind_result($id_usuario);
            $stmtSel->fetch();
            $id_usuario = (int)$id_usuario;
        } else {
            echo "Usuário não encontrado (fallback).";
            $stmtSel->close();
            exit;
        }
    }
    $stmtSel->close();

    // Salvamento da imagem de perfil (se enviada)
    if (isset($_FILES['fotoPerfil']) && !empty($_FILES['fotoPerfil']['name'])) {
        $uploadDirRel = "../ImgPerfilCliente/";
        $uploadDirAbs = __DIR__ . "/../ImgPerfilCliente/";
        if (!is_dir($uploadDirAbs)) {
            mkdir($uploadDirAbs, 0755, true);
        }

        $extensao = pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = "perfil_" . $id_usuario . "." . $extensao;
        $caminhoDestinoRel = $uploadDirRel . $nomeArquivo; // caminho relativo a salvar no banco
        $caminhoDestinoAbs = $uploadDirAbs . $nomeArquivo; // caminho físico para move_uploaded_file

        if (move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $caminhoDestinoAbs)) {
            $sqlUpdateImg = "UPDATE cliente SET imgperfil = ? WHERE id_usuario = ?";
            if ($stmtImg = $conexao->prepare($sqlUpdateImg)) {
                $stmtImg->bind_param("si", $caminhoDestinoRel, $id_usuario);
                if (!$stmtImg->execute()) {
                    echo "Erro ao salvar imagem no banco: " . $stmtImg->error . "<br>";
                }
                $stmtImg->close();
            } else {
                echo "Erro no prepare UPDATE imagem: " . $conexao->error . "<br>";
            }
        } else {
            echo "Erro no upload da imagem.<br>";
        }
    }

    // Recupera campos do formulário com default seguro
    $cliente_telefone = $_POST['telefone'] ?? '';
    $cliente_localizacao = $_POST['localizacao'] ?? '';
    $cliente_facebook = $_POST['facebook'] ?? '';
    $cliente_instagram = $_POST['instagram'] ?? '';

    // Atualiza os dados do cliente com prepared statement
    $sql = "INSERT INTO cliente SET 
        cliente_telefone = ?,
        cliente_localizacao = ?,
        cliente_facebook = ?,
        cliente_instagram = ?
        WHERE id_usuario = ?";
        echo $sql;

    if (!($stmt = $conexao->prepare($sql))) {
        echo "Erro ao executar UPDATE: " . $stmt->error . "<br>";
        exit;
    }

    if (!$stmt->bind_param("ssssi", $cliente_telefone, $cliente_localizacao, $cliente_facebook, $cliente_instagram, $id_usuario)) {
        echo "Erro no bind_param: " . $stmt->error;
        $stmt->close();
        exit;
    }

    if (!$stmt->execute()) {
        echo "Erro ao executar UPDATE: " . $stmt->error . "<br>";
        $stmt->close();
        exit;
    }

    // marca que passou cadastro
    $sql2 = "UPDATE cliente SET passou_cadastro = 1 WHERE id_usuario = ?";
    if ($stmt2 = $conexao->prepare($sql2)) {
        $stmt2->bind_param("i", $id_usuario);
        if (!$stmt2->execute()) {
            echo "Erro ao atualizar passou_cadastro: " . $stmt2->error . "<br>";
        }
        $stmt2->close();
    } else {
        echo "Erro no prepare UPDATE passou_cadastro: " . $conexao->error . "<br>";
    }

    echo "Dados atualizados com sucesso!<br>";

    $stmt->close();
}
?>