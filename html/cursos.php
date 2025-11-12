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
                    <p>Aprenda a organizar seu tempo e tarefas de forma eficiente, aumentando sua produtividade e reduzindo o estresse. Desenvolva hábitos que ajudam a manter foco e disciplina no dia a dia pessoal e profissional.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="../img/marketing-digital.jpg" alt="Marketing Digital">
                    <h3>Marketing Digital</h3>
                    <p>Este curso apresenta conceitos e estratégias de marketing, ensinando como promover produtos e serviços, planejar campanhas e se comunicar de forma eficaz com diferentes públicos.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="../img/atendimento-cliente.jpg" alt="Atendimento ao Cliente">
                    <h3>Atendimento ao Cliente</h3>
                    <p>Este curso prepara você para se destacar no atendimento, ensinando técnicas para interagir de forma cordial, eficiente e profissional com clientes e público em geral. Aprenda a lidar com diferentes situações, manter a empatia e transmitir confiança em cada atendimento.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="../img/gestao-financeira.jpeg" alt="Educação Financeira">
                    <h3>Educação Financeira </h3>
                    <p>Desenvolva habilidades essenciais para controlar suas finanças pessoais. Aprenda a planejar gastos, poupar de forma inteligente e tomar decisões financeiras conscientes para alcançar seus objetivos.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Comunicação Escrita">
                    <h3>Comunicação Escrita </h3>
                    <p>Aprenda a escrever de forma clara, objetiva e adequada para o ambiente profissional. Este curso capacita você a transmitir ideias com eficiência, evitando mal-entendidos e melhorando sua imagem no trabalho ou nos estudos.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Manicure e Pedicure ">
                    <h3>Manicure e Pedicure </h3>
                    <p>Aprenda técnicas profissionais de manicure e pedicure, com foco em estética, higiene e satisfação do cliente. </p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Maquiagem Profissional ">
                    <h3>Maquiagem Profissional </h3>
                    <p>Aprenda a criar maquiagens sofisticadas e adequadas para diferentes ocasiões. Desenvolva habilidades práticas em técnicas, cores e produtos, garantindo resultados profissionais e clientes satisfeitos.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Trancista ">
                    <h3>Trancista</h3>
                    <p>Capacite-se para atuar como trancista profissional, dominando técnicas de tranças e penteados modernos, além de oferecer um atendimento de qualidade e cuidar da saúde capilar dos clientes.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Inclusividade">
                    <h3>Inclusividade</h3>
                    <p>Aprenda a promover diversidade e inclusão no atendimento ao público. Desenvolva atitudes que valorizam a pluralidade e combatem preconceitos, tornando-se um profissional mais consciente e preparado.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Empreendedorismo e Inovação">
                    <h3>Empreendedorismo e Inovação</h3>
                    <p>Descubra como transformar ideias em negócios de sucesso. Este curso ensina conceitos de empreendedorismo, inovação e planejamento estratégico, preparando você para identificar oportunidades e criar soluções criativas.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Boas Práticas de Manipulação de Alimentos">
                    <h3>Boas Práticas de Manipulação de Alimentos</h3>
                    <p>Aprenda a manusear alimentos de forma segura, garantindo higiene e prevenção de contaminações. Ideal para quem atua na área de alimentação e deseja oferecer serviços com qualidade e segurança.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Congelamento de Alimentos">
                    <h3>Congelamento de Alimentos</h3>
                    <p>Aprenda técnicas corretas de congelamento e conservação de alimentos, mantendo qualidade, sabor e valor nutricional. Ideal para profissionais da área de alimentação que buscam segurança e eficiência no manuseio de alimentos.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Resiliência">
                    <h3>Resiliência</h3>
                    <p>Desenvolva a capacidade de superar desafios, mantendo equilíbrio emocional e foco nos objetivos. Este curso ensina técnicas para lidar com situações adversas e fortalecer sua postura pessoal e profissional.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Postura e Imagem Profissional">
                    <h3>Postura e Imagem Profissional </h3>
                    <p>Aprenda a transmitir uma imagem profissional positiva, aprimorando postura, etiqueta, comunicação e aparência, essenciais para se destacar-se.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>

                <div class="card">
                    <img src="" alt="Análise de Balanços">
                    <h3>Análise de Balanços</h3>
                    <p>Aprenda a interpretar demonstrações financeiras e indicadores contábeis para tomar decisões estratégicas baseadas em dados concretos.</p>
                    <button><a href='#' style="text-decoration:none; color:#fff">Ver Detalhes</a></button>
                </div>
            </div>
        </section>
    </main>
</body>
</html>