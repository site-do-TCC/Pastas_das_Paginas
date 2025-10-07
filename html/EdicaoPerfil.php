
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
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="campo">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" required>
                    </div>

                    <div class="campo">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required>
                    </div>





                    <div class="campoSenha">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>

                </div>

                <!-- Coluna da direita -->
                <div class="colunaForm2">

                    <div class="campo">
                        <label for="localizacao">Localização</label>
                        <input type="text" id="localizacao" name="localizacao" required>
                    </div>

                    <div class="campo">
                        <label for="facebook">Facebook</label>
                        <input type="url" id="facebook" name="facebook">
                    </div>

                    <div class="campo">
                        <label for="instagram">Instagram</label>
                        <input type="url" id="instagram" name="instagram">
                    </div>

                </div>

            </div> <!-- Fim das colunas -->

        

    </div>
    <div class="container">

        <!-- Coluna da esquerda -->
        <div class="coluna-esquerda">
            <label for="biografia">Biografia</label>
            <textarea id="biografia" name="biografia"></textarea>

            <label for="servicos">Serviços e Valores</label>
            <textarea id="servicos" name="servicos"></textarea>

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
        echo "Upload realizado com sucesso!";
    } else {
        echo "Erro no upload";
    }
   }
   //Fim do salvamento da imagem de perfil



   //Salvamento do banner 1
    if (isset($_FILES['Banner1']) && !empty($_FILES['Banner1']['name'])) {

    $extensao = pathinfo($_FILES['Banner1']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = "banner1_id_" . $id_usuario . "." . $extensao;
    $caminhoDestino = "../ImgPerfilPrestadoras/" . $nomeArquivo;


    // Move o arquivo
    $resultado = move_uploaded_file($_FILES['Banner1']['tmp_name'], $caminhoDestino);

    if ($resultado) {
        echo "Upload realizado com sucesso!";
    } else {
        echo "Erro no upload";
    }
   }








   

    
}
         
        
        





        //$nome = $_POST['nome'];
        //$telefone = $_POST['telefone'];
        //$email = $_POST['email'];
        //$senha = $_POST['senha'];
        //$localizacao = $_POST['localizacao'];
        //$facebook = $_POST['facebook'];
        //$instagram = $_POST['instagram'];
        //$biografia = $_POST['biografia'];
        //$servicos = $_POST['servicos'];
    



?>