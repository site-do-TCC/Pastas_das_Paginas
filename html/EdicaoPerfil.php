
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
            <img src="\Programacao_TCC_Avena\img\logoAvena.png" alt="Logo Avena" 
            href="\Programacao_TCC_Avena\html\Pagina_Inicial.html">
        </div>
        <div class="menu">

            <button class="menu-icon" id="menu-btn">&#9776;</button>
        </div>
    </nav>
</header>


<body>


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
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" placeholder="Nome de sua empresa" required>
                    </div>

                    <div class="campo">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" placeholder="Contato da empresa" required>
                    </div>

                    <div class="campo">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="Email da empresa" required>
                    </div>







                </div>

                <!-- Coluna da direita -->
                <div class="colunaForm2">

                    <div class="campo">
                        <label for="localizacao">Localização</label>
                        <input type="text" id="localizacao" name="localizacao" placeholder="Sua região de atuação" required>
                    </div>

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
    <div class="container">

        <!-- Coluna da esquerda -->
        <div class="coluna-esquerda">
            <label for="biografia">Biografia</label>
            <textarea id="biografia" name="biografia" placeholder="Descrição do seu serviço, local de atuação, observações, etc..."></textarea>

            <label for="servicos">Serviços e Valores</label>
            <textarea id="servicos" name="servicos" placeholder="Pequeno detalhamento dos serviços prestados e valores a serem cobrados"></textarea>

            <div class="botoes">
                <button class="btn-excluir" name="excluir" id="excluir">EXCLUIR CONTA</button>
                <button class="btn-salvar" name="salvar" id="salvar">SALVAR ALTERAÇÕES</button>
            </div>
        </div>

        <!-- Coluna da direitaa -->
        <div class="coluna-direita">
            <h4>Suas Fotos</h4>
                <!-- Banner 1 -->
                
  <div class="fotos-container">  
    
    <label for="Banner1" class="foto">
      <input type="file" id="Banner1" name="Banner1" accept="image/*" required hidden>
      <img id="previewBanner1" src="" alt="Banner 1" style="display:none;">
      <span class="lixeira">🗑</span>
    </label>
    <!-- Banner 2 -->
    <label for="Banner2" class="foto">
      <input type="file" id="Banner2" name="Banner2" accept="image/*" required hidden>
      <img id="previewBanner2" src="" alt="Banner 2" style="display:none;">
      <span class="lixeira">🗑</span>
    </label>
    
    <!-- Banner 3 -->
    
        <label for="Banner3" class="foto" id="banner3">
        <input type="file" id="Banner3" name="Banner3" accept="image/*" required hidden>
        <img id="previewBanner3" src="" alt="Banner 3" style="display:none;">
        <span class="lixeira">🗑</span>
    </label>
    </form>
    <a href="\Programacao_TCC_Avena\php\sair.php">Deslogar</a>
    
  </div>
</div>
        </div>

    </div>


    <br>
    <br>
    <br>
    <br>
    

</body>
<script src="../js/cadastro.js"></script>
<script rel="stylesheet" src="\Programacao_TCC_Avena\js\EdicaoPerfil.js"></script>

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
    $sql = "SELECT id_usuario FROM prestadora WHERE email = '$email' AND senha = '$senha'";
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
    $caminhoDestino = "../ImgPerfilPrestadoras/" . $nomeArquivo;


    // Move o arquivo
    $resultado = move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $caminhoDestino);

    if ($resultado) {

        //Salva o arquivo no banco de dados
        echo "Upload realizado com sucesso!";
        // Caminho salvo no banco (ajuste conforme a estrutura do seu projeto)
        $caminhoBanco = $caminhoDestino;

        $sqlUpdate = "UPDATE prestadora SET imgperfil = '$caminhoBanco' WHERE id_usuario = '$id_usuario'";
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



   //Salvamento do banner 1
    if (isset($_FILES['Banner1']) && !empty($_FILES['Banner1']['name'])) {

    $extensao = pathinfo($_FILES['Banner1']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = "banner1_id_" . $id_usuario . "." . $extensao;
    $caminhoDestino = "../ImgBannersPrestadoras/" . $nomeArquivo;


    // Move o arquivo
    $resultado = move_uploaded_file($_FILES['Banner1']['tmp_name'], $caminhoDestino);

    if ($resultado) {
         //Salva o arquivo no banco de dados
        //echo "Upload realizado com sucesso!";
        // Caminho salvo no banco (ajuste conforme a estrutura do seu projeto)
        
        $caminhoBanco = $caminhoDestino;

        $sqlUpdate = "UPDATE prestadora SET banner1 = '$caminhoBanco' WHERE id_usuario = '$id_usuario'";
        if ($conexao->query($sqlUpdate)) {
            echo "Caminho salvo no banco com sucesso!";
        } else {
            echo "Erro ao salvar no banco: " . $conexao->error;
        }
        // FIM -----------------------------------------
    } else {
        echo "Erro no upload";
    }
   }
    //Fim do salvamento do banner 1

//-----------------------------------------------------------------------------------------------------------------

   //Salvamento do banner 2
    if (isset($_FILES['Banner2']) && !empty($_FILES['Banner2']['name'])) {

    $extensao = pathinfo($_FILES['Banner2']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = "banner2_id_" . $id_usuario . "." . $extensao;
    $caminhoDestino = "../ImgBannersPrestadoras/" . $nomeArquivo;


    // Move o arquivo
    $resultado = move_uploaded_file($_FILES['Banner2']['tmp_name'], $caminhoDestino);
    if ($resultado) {
         //Salva o arquivo no banco de dados
        //echo "Upload realizado com sucesso!";
        // Caminho salvo no banco (ajuste conforme a estrutura do seu projeto)
        $caminhoBanco = $caminhoDestino;

        $sqlUpdate = "UPDATE prestadora SET banner2 = '$caminhoBanco' WHERE id_usuario = '$id_usuario'";
        if ($conexao->query($sqlUpdate)) {
            echo "Caminho salvo no banco com sucesso!";
        } else {
            echo "Erro ao salvar no banco: " . $conexao->error;
        }
        // FIM -----------------------------------------
    } else {
        echo "Erro no upload";
    }
   }
   //Fim do salvamento do banner 2

//-----------------------------------------------------------------------------------------------------------------


   //Salvamento do banner 3
    if (isset($_FILES['Banner3']) && !empty($_FILES['Banner3']['name'])) {

    $extensao = pathinfo($_FILES['Banner3']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = "banner3_id_" . $id_usuario . "." . $extensao;
    $caminhoDestino = "../ImgBannersPrestadoras/" . $nomeArquivo;

    // Move o arquivo
    $resultado = move_uploaded_file($_FILES['Banner3']['tmp_name'], $caminhoDestino);

    if ($resultado) {
         //Salva o arquivo no banco de dados
        //echo "Upload realizado com sucesso!";
        // Caminho salvo no banco (ajuste conforme a estrutura do seu projeto)
        $caminhoBanco = $caminhoDestino;

        $sqlUpdate = "UPDATE prestadora SET banner3 = '$caminhoBanco' WHERE id_usuario = '$id_usuario'";
        if ($conexao->query($sqlUpdate)) {
            //echo "Caminho salvo no banco com sucesso!";
        } else {
            //echo "Erro ao salvar no banco: " . $conexao->error;
        }
        // FIM -----------------------------------------
        
    } else {
        echo "Erro no upload";
    }
}

   //Fim do salvamento do banner 3


//-----------------------------------------------------------------------------------------------------------------

         

        





        $empresa_nome = $_POST['nome'];
        $empresa_telefone = $_POST['telefone'];
        $empresa_email = $_POST['email'];
        $empresa_localizacao = $_POST['localizacao'];
        $empresa_facebook = $_POST['facebook'];
        $empresa_instagram = $_POST['instagram'];
        $empresa_biografia = $_POST['biografia'];
        $empresa_servicos = $_POST['servicos'];
    

        $check = mysqli_query($conexao, "SELECT * FROM prestadora WHERE empresa_email = '$empresa_email'");
        
        if (mysqli_num_rows($check) >= 0) {
            $result = mysqli_query($conexao, "INSERT INTO prestadora(empresa_nome,empresa_telefone, empresa_email, empresa_localizacao, empresa_facebook, empresa_instagram, empresa_biografia, empresa_servicos) VALUES ('$empresa_nome','$empresa_telefone','$empresa_email', '$empresa_localizacao', '$empresa_facebook', '$empresa_instagram', '$empresa_biografia', '$empresa_servicos')");
            if ($result){
                echo "Deu certo";
            }
        }    
    
}

?>