<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once(__DIR__ . '/../php/conexao.php');

 //Verifica se foi passado um ID na URL
if (!isset($_GET['id_curso'])) {
    header("Location: cursos.php");
    exit;
}

$id_curso = intval($_GET['id_curso']);
$sql = "SELECT * FROM curso WHERE id_curso = $id_curso";
$resultado = mysqli_query($conexao, $sql);

$prof = mysqli_fetch_assoc($resultado);
$logado = isset($_SESSION['email']);
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
    <header class="topo">
        <div class="logo">
            <img src="../imagens/logo-avena.png" alt="Logo Avena">
            <h1>AVENA</h1>
        </div>
        <div class="perfil">
            <span>Geisa</span>
            <img src="../imagens/foto-perfil.jpg" alt="Foto de perfil">
        </div>
    </header>

    <main class="container">
        <h2><?=$prof['Nome'] ?></h2>

        <section class="conteudo">
            <div class="caixa">
                <h3>Descrição Geral</h3>
                <p><?= nl2br(htmlspecialchars($prof['descricao_geral'])) ?></p>
            </div>

            <div class="caixa">
                <h3>Você vai Aprender</h3>
                <ul>
                    <?php
                        // Exemplo: você salva os tópicos separados por ponto e vírgula no banco
                        $itens = explode(';', $prof['conteudo']);
                        foreach ($itens as $item) {
                            echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                        }
                    ?>
                </ul>
            </div>
        </section>

        <div class="info">
            <p><strong>Carga Horária:</strong> <?= htmlspecialchars($prof['carga_horaria']) ?> horas</p>
            <p><strong>Nível:</strong> <?= htmlspecialchars($prof['nivel']) ?></p>
        </div>

        <div class="acoes">
            <button class="btn" onclick="window.location.href='../videos/<?= htmlspecialchars($curso['video_url']) ?>'">Iniciar Curso</button>
        </div>
    </main>
</body>
</html>