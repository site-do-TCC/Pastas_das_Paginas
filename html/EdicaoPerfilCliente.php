
<?php
session_start();

include_once(__DIR__ . '/../php/conexao.php');
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

    <form method="POST" enctype="multipart/form-data" action="EdicaoPerfil.php">



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
<script rel="preload" src="\Programacao_TCC_Avena\js\EdicaoPerfil.js"></script>
<script src="../js/cadastro.js"></script>
<script src="\Programacao_TCC_Avena\js\cookies.js"></script> 
</html>

<?php
    
error_reporting(E_ALL);
ini_set('display_errors', 1);



if (isset($_POST['salvar'])) {
    include_once(__DIR__ . '/../php/conexao.php');

    // Recupera da sessão
    $email = $_SESSION['email'];
    $senha = $_SESSION['senha'];

    // Busca o ID do usuário logado
    $sql = "SELECT id_usuario FROM cliente WHERE email = '$email' AND senha = '$senha'";
    $result = $conexao->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_usuario = $row['id_usuario'];

        echo "ID do usuário logado: " . $id_usuario;
        // Agora você pode usar esse $id_usuario pra salvar imagem, atualizar perfil etc.
    } else {
        echo "Usuário não encontrado.";
    }

    //Salvamento da imagem de perfil
    if (isset($_FILES['fotoPerfil']) && !empty($_FILES['fotoPerfil']['name'])) {

    $extensao = pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = "perfil_" . $id_usuario . "." . $extensao;
    $caminhoDestino = "../ImgPerfilCliente/" . $nomeArquivo;


    // Move o arquivo
    $resultado = move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $caminhoDestino);

    if ($resultado) {

        //Salva o arquivo no banco de dados
        echo "Upload realizado com sucesso!";
        // Caminho salvo no banco (ajuste conforme a estrutura do seu projeto)
        $caminhoBanco = $caminhoDestino;

        $sqlUpdate = "UPDATE cliente SET imgperfil = '$caminhoBanco' WHERE id_usuario = '$id_usuario'";
        if ($conexao->query($sqlUpdate)) {
            echo "Caminho salvo no banco com sucesso!";
        } else {
            echo "Erro ao salvar no banco: " . $conexao->error;
        }
        // FIM -----------------------------------------


    }else {
            echo "Erro no upload";
    }
   }
   //Fim do salvamento da imagem de perfil


//-----------------------------------------------------------------------------------------------------------------



   



        






$cliente_telefone = $_POST['telefone'];
$cliente_localizacao = $_POST['localizacao'];
$cliente_facebook = $_POST['facebook'];
$cliente_instagram = $_POST['instagram'];




$sql = "UPDATE cliente SET 
            cliente_telefone='$cliente_telefone',
            cliente_localizacao='$cliente_localizacao',
            cliente_facebook='$cliente_facebook',
            cliente_instagram='$cliente_instagram',
        WHERE id_usuario='$id_usuario'";

if (mysqli_query($conexao, $sql)) {
    $sql = "UPDATE cliente SET passou_cadastro = 1 WHERE id_usuario = $id_usuario";
    mysqli_query($conexao, $sql);
} else {
    echo "Erro ao atualizar: " . mysqli_error($conexao);
}
    
}

?>