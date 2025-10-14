<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profissionais Próximas | Avena</title>
  <link rel="stylesheet" href="\Programacao_TCC_Avena\css\busca.css">
</head>
<body>
  <!-- Cabeçalho -->
  <header class="header">
    <div class="logo">
      <img src="\Programacao_TCC_Avena\img\logoAvena.png" alt="Logo Avena">
    </div>
    <div class="search-bar">
      <input type="text" placeholder="Manicure" class="search-input">
      <span class="divider"></span>
      <input type="text" placeholder="Cidade ou Estado" class="location-input">
    </div>
    <button class="login-btn">ENTRAR</button>
  </header>

  <!-- Caminho de navegação -->
  <nav class="breadcrumb">
    <a href="#">Home</a> / 
    <a href="#">Busca</a>
  </nav>

  <main class="container">
    <?php
      include_once(__DIR__ . '/../php/conexao.php');
      $sql = "SELECT * FROM prestadora";
      $resultado = mysqli_query($conexao, $sql);
      $total = mysqli_num_rows($resultado);
    ?>

    <div class="header-section">
      <h2>PROFISSIONAIS PRÓXIMAS</h2>
      <span class="count"><?= $total ?> Profissionais</span>
      <button class="filter-btn">⚙️ Filtros</button>
    </div>

    <!-- Cards dinâmicos -->
    <section class="cards-container">
      <?php while ($prof = mysqli_fetch_assoc($resultado)) { ?>
        <div class="card">
          <div class="card-img">
            <img src="<?= $prof['banner1'] ?>" alt="<?= $prof['nome'] ?>">
            <button class="heart-btn">🤍</button>
          </div>
          <div class="card-info">
            <h3><?= $prof['nome'] ?></h3>
            <p><?= $prof['cidade'] ?>, <?= $prof['estado'] ?></p>
            <div class="stars"><?= str_repeat('⭐', $prof['avaliacao']) ?></div>
          </div>
        </div>
      <?php } ?>
    </section>
  </main>

  <script>
    // Exemplo simples: animação e troca do like
    document.addEventListener("DOMContentLoaded", () => {
      const hearts = document.querySelectorAll(".heart-btn");
      hearts.forEach(btn => {
        btn.addEventListener("click", () => {
          btn.textContent = btn.textContent === "🤍" ? "❤️" : "🤍";
        });
      });
    });
  </script>
</body>
</html>
