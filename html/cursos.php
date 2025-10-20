<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();
    include_once(__DIR__ . '/../php/conexao.php');

    if((!isset($_SESSION['email']) == true) || (!isset($_SESSION['senha']) == true || $_SESSION['tipo'] == 'cliente')){
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: \Programacao_TCC_Avena\html\login.php');
        }else{
            $logado = $_SESSION['email'];
        }

    $sql = "SELECT * FROM prestadora WHERE email = '$logado'";
    
    $resultado = mysqli_query($conexao, $sql);
    $total = mysqli_num_rows($resultado);
    $prof = mysqli_fetch_assoc($resultado);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos - Avena</title>
    <link rel="stylesheet" href="\Programacao_TCC_Avena\css\cursos.css">
</head>
<body>
    <header class="topo">
        <div class="logo">
            <a href="\Programacao_TCC_Avena\html\Pagina_Inicial.html"> 
                <img src="../img/logoAvena.png" alt="Logo Avena" >
            </a>
        </div>
        <div class="perfil">
            <span><?= $prof['nome'];?></span>
            <img src="<?= $prof['imgperfil']?>" alt="Foto de perfil">
        </div>
    </header>

    <main>
        <section class="cursos">
            <h2>Cursos</h2>
            <div class="cards">
                <div class="card">
                    <img src="../img/gestao-tempo.jpeg" alt="Gestão de Tempo">
                    <h3>Gestão de Tempo</h3>
                    <p>Diga adeus à bagunça! Gerencie seus horários com eficiência e produtividade.</p>
                    <button><a href='cursosDetalhes.php?id_curso=1' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="../img/marketing-digital.jpg" alt="Marketing Digital">
                    <h3>Marketing Digital</h3>
                    <p>Aprenda estratégias práticas para atrair novos clientes e fortalecer sua presença online.</p>
                    <button><a href='cursosDetalhes.php?id_curso=2' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="../img/atendimento-cliente.jpg" alt="Atendimento ao Cliente">
                    <h3>Atendimento ao Cliente</h3>
                    <p>Encante cada cliente com comunicação eficaz e postura profissional diferenciada.</p>
                    <button><a href='cursosDetalhes.php?id_curso=3' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="../img/gestao-financeira.jpeg" alt="Gestão Financeira">
                    <h3>Gestão Financeira</h3>
                    <p>Organize seus ganhos, controle seus gastos e conquiste estabilidade financeira.</p>
                    <button><a href='cursosDetalhes.php?id_curso=4' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>
            </div>
        </section>
    </main>
</body>
</html>