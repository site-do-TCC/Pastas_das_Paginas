<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once(__DIR__ . '/../php/conexao.php');
mysqli_set_charset($conexao, "utf8mb4");

 //Verifica se foi passado um ID na URL
if (!isset($_GET['id_curso'])) {
    header("Location: cursos.php");
    exit;
}

$id_curso = intval($_GET['id_curso']);
$sqlcurso = "SELECT * FROM curso WHERE id_curso = $id_curso";
$resultadocurso = mysqli_query($conexao, $sqlcurso);

$prof = mysqli_fetch_assoc($resultadocurso);
$logado = isset($_SESSION['email']);


if (!empty($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conexao->prepare("SELECT nome FROM prestadora WHERE id_usuario = ?");
    if ($stmt === false) {

    } else {
        $stmt->bind_param("i", $id_usuario);
        $executou = $stmt->execute();
        if ($executou) {
            $resultado = $stmt->get_result();
            if ($resultado && $resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                if (!empty($row['nome'])) {
                    $nome = $row['nome'];
                    
                }
            } else {
                
            }
        } else {
            
        }
        $stmt->close();
        
    }
    $stmt = $conexao->prepare("SELECT imgperfil FROM prestadora WHERE id_usuario = ?");
    if ($stmt === false) {

    } else {
        $stmt->bind_param("i", $id_usuario);
        $executou = $stmt->execute();
        if ($executou) {
           
            $resultado = $stmt->get_result();
            if ($resultado && $resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                if (!empty($row['imgperfil'])) {
                    $img = $row['imgperfil'];
                    
                }
            } else {
                
            }
        } else {
            
        }
        $stmt->close();
        
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($prof['Nome']) ?> - Avena</title>
    <link rel="stylesheet" href="../css/cursosDetalhes.css">
</head>
<body>





 <header>
    <nav>
      <div class="logo">
        <a href="Pagina_Inicial.html">
          <img src="../img/logoAvena.png" alt="Logo Avena">
        </a>
      </div>

      <div class="perfil-area">
        <span class="nome"  style="color:#f5f5f5;"><?php echo $nome ?></span>

       
        <img src="<?php echo $img?>" alt="Foto de perfil" class="perfil-foto">

        
      </div>
    </nav>
  </header>




    <main class="container">
        <h2><?= htmlspecialchars($prof['Nome']) ?></h2>

        <section class="conteudo">
            <div class="caixa">
                <h3>Descrição Geral</h3>
                <p><?= nl2br(htmlspecialchars($prof['DescricaoGeral'])) ?></p>
            </div>

            <div class="caixa">
                <h3>Você vai Aprender</h3>
                <ul>
                    <?php
                        // Exemplo: você salva os tópicos separados por ponto e vírgula no banco
                        $itens = explode(';', $prof['Aprender']);
                        foreach ($itens as $item) {
                            echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                        }
                    ?>
                </ul>
            </div>
        </section>

        <div class="info">
            <p><strong>Carga Horária:</strong> <?= htmlspecialchars($prof['TempoTotal']) ?> horas</p>
            <p><strong>Nível:</strong> <?= htmlspecialchars($prof['Nivel']) ?></p>
        </div>

        <div class="acoes">
            <a href="<?= htmlspecialchars($prof['video'])?>" target="_blank"><button class="btn">Iniciar Curso</button><a>
        </div>
    </main>
</body>
</html>