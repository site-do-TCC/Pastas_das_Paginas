<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include_once(__DIR__ . '/../php/conexao.php');

// Pega os valores enviados pela URL
$search_servico = isset($_GET['search_servico']) ? trim($_GET['search_servico']) : '';
$search_localizacao = isset($_GET['search_localizacao']) ? trim($_GET['search_localizacao']) : '';

// Monta a query base
$sql = "SELECT * FROM prestadora WHERE 1=1";

// Adiciona os filtros se foram preenchidos
if (!empty($search_servico)) {
  $sql .= " AND (empresa_servicos LIKE '%$search_servico%' OR empresa_servicos LIKE '%$search_servico%')";
}

if (!empty($search_localizacao)) {
  $sql .= " AND (empresa_localizacao LIKE '%$search_localizacao%' OR empresa_localizacao LIKE '%$search_localizacao%')";
}

// Executa e conta resultados
$resultado = mysqli_query($conexao, $sql);
$total = mysqli_num_rows($resultado);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profissionais Próximas | Avena</title>
  <link rel="stylesheet" href="\Programacao_TCC_Avena\css\busca.css">
</head>
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

  <!-- Cabeçalho -->
  <header class="header">
    <div class="logo">
      <img src="\Programacao_TCC_Avena\img\logoAvena.png" alt="Logo Avena" href="\Programacao_TCC_Avena\html\Pagina_Inicial.html">
    </div>
    <div class="search-bar">
      <div>
        <input 
          type="text" 
          placeholder="Manicure" 
          class="search-input" 
          id="search_servico" 
          name="search_servico" 
          value="<?= htmlspecialchars($search_servico) ?>"
        >
        <span class="divider"></span> /
        <input 
          type="text" 
          placeholder="Cidade ou Estado" 
          class="location-input" 
          id="search_localizacao" 
          name="search_localizacao" 
          value="<?= htmlspecialchars($search_localizacao) ?>"
        >
      </div>
      <button onclick="ProcurarServico()" class="pesquisa-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
        </svg>  
      </button>
    </div>
  </header>

  <!-- Caminho de navegação -->
  <nav class="breadcrumb">
    <a href="\Programacao_TCC_Avena\html\Pagina_Inicial.html">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
        <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
      </svg>
    </a> / 
    <a href="\Programacao_TCC_Avena\html\busca.php">Busca</a>
  </nav>

  <main class="container">
    <div class="header-section">
      <h2>PROFISSIONAIS PRÓXIMAS</h2>
      <span class="count"><?= $total ?> Profissionais</span>
    </div>

    <!-- Cards dinâmicos -->
    <section class="cards-container">
      <?php if ($total > 0) { ?>
        <?php while ($prof = mysqli_fetch_assoc($resultado)) { ?>
          <a href="\Programacao_TCC_Avena\html\servico.php?id_usuario=<?= $prof['id_usuario']?>" class="cards-link">
          <div class="card">
            <div class="card-img">
              <img src="<?= $prof['banner1'] ?>" alt="<?= $prof['nome'] ?>">
              <button class="heart-btn">🤍</button>
            </div>
            <div class="card-info">
              <h3><?= $prof['nome'] ?></h3>
              <p><?= $prof['empresa_localizacao'] ?> </p>
              <p><?= $prof['empresa_servicos'] ?> </p>
        <!-- 
              <div class="stars"></div>
        -->
            </div>
          </div>
        </a>
        <?php } ?>
      <?php } else { ?>
        <p style="text-align:center; margin-top:30px;">Nenhum profissional encontrado 😔</p>
      <?php } ?>  
    </section>
  </main>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const hearts = document.querySelectorAll(".heart-btn");
      hearts.forEach(btn => {
        btn.addEventListener("click", () => {
          btn.textContent = btn.textContent === "🤍" ? "❤️" : "🤍";
        });
      });
    });

    // Pesquisa
    const search_servico = document.getElementById('search_servico');
    const search_localizacao = document.getElementById('search_localizacao');

    document.addEventListener("keydown", function(event) {
      if (event.key === "Enter") {
        ProcurarServico();
      }
    });

    function ProcurarServico() {
      const servico = encodeURIComponent(search_servico.value);
      const local = encodeURIComponent(search_localizacao.value);
      window.location = `busca.php?search_servico=${servico}&search_localizacao=${local}`;
    }
  </script>
</body>
<script src="\Programacao_TCC_Avena\js\cookies.js"></script>
</html>
