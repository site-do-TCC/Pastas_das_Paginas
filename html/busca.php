<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profissionais Próximas | Avena</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- Cabeçalho -->
  <header class="header">
    <div class="logo">
      <img src="logo-avena.png" alt="Logo Avena">
      <h1>AVENA</h1>
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

  <!-- Conteúdo principal -->
  <main class="container">
    <div class="header-section">
      <h2>PROFISSIONAIS PRÓXIMAS</h2>
      <span class="count">180 Profissionais</span>
      <button class="filter-btn">⚙️ Filtros</button>
    </div>

    <!-- Lista de profissionais (gerada dinamicamente) -->
    <section class="cards-container">
      <!-- Exemplo de card - será gerado via loop -->
      <div class="card">
        <div class="card-img">
          <img src="img/studio.jpg" alt="Studio Geisa">
          <span class="heart">❤️</span>
        </div>
        <div class="card-info">
          <h3>Studio Geisa</h3>
          <p>Itaquaquecetuba, São Paulo</p>
          <div class="stars">⭐⭐⭐⭐☆</div>
        </div>
      </div>

      <div class="card">
        <div class="card-img">
          <img src="img/rayssa.jpg" alt="Rayssa Nails">
          <span class="heart">🤍</span>
        </div>
        <div class="card-info">
          <h3>Rayssa Nails</h3>
          <p>Suzano, São Paulo</p>
          <div class="stars">⭐⭐⭐☆☆</div>
        </div>
      </div>

      <div class="card">
        <div class="card-img">
          <img src="img/rosana.jpg" alt="Rosana Style">
          <span class="heart">🤍</span>
        </div>
        <div class="card-info">
          <h3>Rosana Style</h3>
          <p>Arujá, São Paulo</p>
          <div class="stars">⭐⭐⭐☆☆</div>
        </div>
      </div>
      <!-- Mais cards gerados automaticamente -->
    </section>
  </main>
</body>
</html>