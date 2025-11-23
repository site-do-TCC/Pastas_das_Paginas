
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

session_start();
mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once(__DIR__ . '/../php/conexao.php');

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

// EXCLUSÃO DE CONTA
// chamada: colocar dentro da sua página onde você já tem session e $conexao
if (isset($_POST['excluir'])) {
    if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['tipo'])) {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){mostrarModal('Sessão inválida.');});</script>";
        exit;
    }

    $id = (int) $_SESSION['id_usuario'];
    $tipo = $_SESSION['tipo']; // 'cliente' ou 'profissional' (ou 'prestadora' conforme seu sistema)

    // iniciar transação
    mysqli_begin_transaction($conexao);

    try {
        if ($tipo === 'cliente') {
            // avaliações (avaliador ou avaliado)
            $stmt = $conexao->prepare("
                DELETE FROM avaliacoes
                WHERE (avaliador_tipo = 'cliente' AND avaliador_id = ?)
                   OR (avaliado_tipo   = 'cliente' AND avaliado_id = ?)
            ");
            $stmt->bind_param("ii", $id, $id);
            $stmt->execute();
            $stmt->close();

            // agenda
            $stmt = $conexao->prepare("DELETE FROM agenda WHERE id_usuario = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // notificacoes
            $stmt = $conexao->prepare("DELETE FROM notificacoes WHERE id_usuario = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // solicitacoes (quando é contratante)
            $stmt = $conexao->prepare("DELETE FROM solicitacoes WHERE id_contratante = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // por fim apagar cliente
            $stmt = $conexao->prepare("DELETE FROM cliente WHERE id_usuario = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception("Não foi possível apagar cliente (não encontrado).");
            }
            $stmt->close();

        } else { // prestadora / profissional
            // avaliações
            $stmt = $conexao->prepare("
                DELETE FROM avaliacoes
                WHERE (avaliador_tipo = 'prestadora' AND avaliador_id = ?)
                   OR (avaliado_tipo   = 'prestadora' AND avaliado_id = ?)
            ");
            $stmt->bind_param("ii", $id, $id);
            $stmt->execute();
            $stmt->close();

            // agenda
            $stmt = $conexao->prepare("DELETE FROM agenda WHERE id_usuario = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // solicitacoes onde é prestadora
            $stmt = $conexao->prepare("DELETE FROM solicitacoes WHERE id_prestadora = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // notificacoes (verifique se há notificações armazenadas pra prestadora também)
            $stmt = $conexao->prepare("DELETE FROM notificacoes WHERE id_usuario = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // apagar prestadora
            $stmt = $conexao->prepare("DELETE FROM prestadora WHERE id_usuario = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception("Não foi possível apagar prestadora (não encontrada).");
            }
            $stmt->close();
        }

        // tudo ok -> commit
        mysqli_commit($conexao);

        // encerrar sessão e redirecionar
        session_destroy();
        echo "<script>document.addEventListener('DOMContentLoaded',function(){mostrarModal('Conta excluída com sucesso. Redirecionando...'); setTimeout(function(){window.location.href='../html/Pagina_Inicial.html';},1200);});</script>";
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conexao);
        $msg = addslashes($e->getMessage() . ' | MySQL: ' . mysqli_error($conexao));
        echo "<script>document.addEventListener('DOMContentLoaded',function(){mostrarModal('Erro ao excluir conta: {$msg}');});</script>";
        // não exit aqui se você quiser continuar execução
    }
}

?>