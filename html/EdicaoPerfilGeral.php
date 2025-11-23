<?php
// coloque isto NO INÍCIO do EdicaoPerfilGeral.php
mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once(__DIR__ . '/../php/conexao.php');

if($_SESSION['tipo'] === 'cliente'){
    $href= "..\html\bemVindoCliente.php";
}elseif($_SESSION['tipo'] === 'profissional'){
     $href= "..\html\bemVindoPrestadora.php";
}
    

if (!isset($conexao) || !($conexao instanceof mysqli)) {
    die("Erro interno: conexão inválida.");
}

if (isset($_POST['excluir'])) {
    if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo'])) {
        header("Location: ../html/Pagina_Inicial.html");
        exit;
    }

    $id = (int) $_SESSION['id_usuario'];
    $tipoSessao = $_SESSION['tipo']; 

    error_log("EXCLUSÃO COMPLETA - Tipo: " . $tipoSessao . ", ID: " . $id);

    // Começa transação
    mysqli_begin_transaction($conexao);

    try {
        // DESABILITA FKs temporariamente
        mysqli_query($conexao, "SET FOREIGN_KEY_CHECKS = 0");

        if ($tipoSessao === 'cliente' || $tipoSessao === 'profissional') {
            
            $tipoBanco = ($tipoSessao === 'profissional') ? 'prestadora' : 'cliente';
            error_log("Excluindo conta do tipo: " . $tipoBanco . " (sessão: " . $tipoSessao . ")");

            // ORDEM CORRETA DE EXCLUSÃO:

            // 1. NOTIFICAÇÕES - Primeiro porque pode referenciar outras tabelas
            $stmt = $conexao->prepare("DELETE FROM notificacoes WHERE id_usuario = ?");
            if (!$stmt) throw new Exception("Prepare notificacoes falhou: " . $conexao->error);
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) throw new Exception("Erro ao deletar notificacoes: " . $stmt->error);
            $stmt->close();
            error_log("Notificações excluídas");

            // 2. AVALIAÇÕES - Onde o usuário é avaliador ou avaliado
            $stmt = $conexao->prepare("
                DELETE FROM avaliacoes 
                WHERE (avaliador_tipo = ? AND avaliador_id = ?)
                   OR (avaliado_tipo = ? AND avaliado_id = ?)
            ");
            if (!$stmt) throw new Exception("Prepare avaliacoes falhou: " . $conexao->error);
            $stmt->bind_param("sisi", $tipoBanco, $id, $tipoBanco, $id);
            if (!$stmt->execute()) throw new Exception("Erro ao deletar avaliacoes: " . $stmt->error);
            $stmt->close();
            error_log("Avaliações excluídas");

            // 3. AGENDA - Eventos do usuário
            $stmt = $conexao->prepare("DELETE FROM agenda WHERE id_usuario = ?");
            if (!$stmt) throw new Exception("Prepare agenda falhou: " . $conexao->error);
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) throw new Exception("Erro ao deletar agenda: " . $stmt->error);
            $stmt->close();
            error_log("Agenda excluída");

            // 4. SOLICITAÇÕES - Dependendo do tipo de usuário
            if ($tipoSessao === 'cliente') {
                // Cliente é contratante
                $stmt = $conexao->prepare("DELETE FROM solicitacoes WHERE id_contratante = ?");
            } else {
                // Profissional é prestadora
                $stmt = $conexao->prepare("DELETE FROM solicitacoes WHERE id_prestadora = ?");
            }
            
            if (!$stmt) throw new Exception("Prepare solicitacoes falhou: " . $conexao->error);
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) throw new Exception("Erro ao deletar solicitacoes: " . $stmt->error);
            $stmt->close();
            error_log("Solicitações excluídas");

            // 5. USUÁRIO PRINCIPAL - Por último, depois de excluir todos os relacionados
            if ($tipoSessao === 'cliente') {
                $stmt = $conexao->prepare("DELETE FROM cliente WHERE id_usuario = ?");
            } else {
                $stmt = $conexao->prepare("DELETE FROM prestadora WHERE id_usuario = ?");
            }
            
            if (!$stmt) throw new Exception("Prepare tabela principal falhou: " . $conexao->error);
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) throw new Exception("Erro ao deletar usuário principal: " . $stmt->error);
            
            $affected = $stmt->affected_rows;
            $stmt->close();
            
            if ($affected === 0) {
                throw new Exception("Usuário não encontrado na tabela principal.");
            }
            
            error_log("Usuário principal excluído - " . $affected . " linha(s) afetada(s)");
        }

        // RE-HABILITA FKs
        mysqli_query($conexao, "SET FOREIGN_KEY_CHECKS = 1");

        // Commit e logout
        mysqli_commit($conexao);
        
        // Limpa todas as variáveis de sessão
        $_SESSION = array();
        session_destroy();

        error_log("✅ CONTA EXCLUÍDA COM SUCESSO - ID: " . $id . ", Tipo: " . $tipoSessao);
        header("Location: ../html/Pagina_Inicial.html");
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conexao);
        
        // Re-habilita FKs em caso de erro também
        mysqli_query($conexao, "SET FOREIGN_KEY_CHECKS = 1");
        
        error_log("❌ ERRO EXCLUIR CONTA - " . $e->getMessage());
        
        echo "<script>
        alert('Erro ao excluir conta: " . addslashes($e->getMessage()) . "');
        window.location.href = '../html/Pagina_Inicial.html?erro_exclusao=1';
        </script>";
        exit;
    }
}
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



<body>
<header>
    <nav>
        <div class="logo">
            <a href="\Programacao_TCC_Avena\html\Pagina_Inicial.html"><img src="\Programacao_TCC_Avena\img\logoAvena.png" alt="Logo Avena"></a>
        </div>

    </nav>
</header>




 <!-- Mensagem -->
    <div id="modalErro" class="modal">
        <div class="modal-content">
            <p id="mensagemErro">...</p>
            <button onclick="fecharModal()">OK</button>
        </div>
    </div>


    <!-- Modal de Confirmação -->
<div id="modalConfirmar" class="modal">
  <div class="modal-content">
      <p id="mensagemConfirmar">Tem certeza que deseja excluir sua conta?</p>

      <div class="modal-buttons">
          <button id="btnConfirmar" class="btn-confirmar">Excluir</button>
          <button id="btnCancelar" class="btn-cancelar">Cancelar</button>
      </div>
  </div>
</div>

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

    <!-- 
========================
// BOTÃO VOLTAR
========================
-->
<style>

  .arrow-animated {
     position: relative;
    left: 30px;
    color: #917ba4;
    width: 30px;  
    height:30px; 
    animation: floatLeft 1.6s ease-in-out infinite;~
  }

  @keyframes floatLeft {
    0%   { transform: translateX(0); }
    50%  { transform: translateX(-2px); }
    100% { transform: translateX(0); }
  }

</style>
<a href=<?= $href?> class="arrow">
<svg xmlns="http://www.w3.org/2000/svg" 
     width="20" height="20" fill="currentColor" 
     class="bi bi-arrow-left arrow-animated"
     viewBox="0 0 16 16">
  <path fill-rule="evenodd" 
        d="M5.854 4.146a.5.5 0 0 1 0 .708L3.707 7H14.5a.5.5 0 0 1 0 1H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0z"/>
</svg>
</a>
<!-- 
========================
// BOTÃO VOLTAR
========================
-->


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

    <button type="button" onclick="confirmarExclusao()" class="btn-excluir">
  EXCLUIR CONTA
</button>

    <button class="btn-salvar" name="salvar" id="salvar">SALVAR ALTERAÇÕES</button>
</div>
<a href="\Programacao_TCC_Avena\php\sair.php" class="btn-deslogar">DESLOGAR</a>
</div>



<!-- ===============================
   <script src="../js/cadastro.js"></script> 
    <script src="\Programacao_TCC_Avena\js\EdicaoPerfil.js"></script>
 =============================== -->
<script src="\Programacao_TCC_Avena\js\EdicaoPerfilCliente.js"></script>
<script src="\Programacao_TCC_Avena\js\cookies.js"></script> 
</body>
</html>



<?php

if (!isset($conexao) || !($conexao instanceof mysqli)) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        mostrarModal('Erro interno: conexão inválida com o banco de dados.');
    });
    </script>";
    exit;
}

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo'])) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        mostrarModal('Sessão inválida. Faça login novamente.');
        window.location.href = '\login.php';
    });
    </script>";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

if (isset($_POST['salvar'])) {

    if ($_SESSION['tipo'] == 'cliente') {

        // Atualiza foto
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

        // EMAIL JÁ EXISTE
        if (!empty($email)) {
            $sqlCheckEmail = "SELECT id_usuario FROM cliente WHERE email = ? AND id_usuario != ?";
            $stmtCheck = $conexao->prepare($sqlCheckEmail);
            $stmtCheck->bind_param("si", $email, $id_usuario);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    mostrarModal('Este e-mail já está cadastrado em outra conta.');
                });
                </script>";
                $stmtCheck->close();
                exit;
            }
            $stmtCheck->close();
        }

        // UPDATE
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
            echo '<script>window.location.href = "\bbemVindoCliente.php";</script>';

            if (!empty($email)) $_SESSION['email'] = $email;
            if (!empty($senha)) $_SESSION['senha'] = $senha;
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                mostrarModal('Erro ao atualizar: " . addslashes($stmt->error) . "');
            });
            </script>";
        }

        $stmt->close();
    }

    // PROFISSIONAL
    if ($_SESSION['tipo'] == 'profissional') {

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

        $senha = $_POST['senha'] ?? '';
        $localizacao = $_POST['localizacao'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';

        // EMAIL EXISTENTE
        if (!empty($email)) {
            $sqlCheckEmail = "SELECT id_usuario FROM prestadora WHERE email = ? AND id_usuario != ?";
            $stmtCheck = $conexao->prepare($sqlCheckEmail);
            $stmtCheck->bind_param("si", $email, $id_usuario);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    mostrarModal('Este e-mail já está cadastrado em outra conta.');
                });
                </script>";
                $stmtCheck->close();
                exit;
            }
            $stmtCheck->close();
        }

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
            echo '<script>window.location.href = "\bbemVindoPrestadora.php";</script>';

            if (!empty($email)) $_SESSION['email'] = $email;
            if (!empty($senha)) $_SESSION['senha'] = $senha;
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                mostrarModal('Erro ao atualizar: " . addslashes($stmt->error) . "');
            });
            </script>";
        }

        $stmt->close();
    }
}



?>