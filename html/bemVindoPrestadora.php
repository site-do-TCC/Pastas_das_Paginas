<?php
session_start();
include("conexao.php");

// Simulação: depois você coloca o ID da prestadora logada via login
$id_prestadora = $_SESSION['id_prestadora'] ?? 1;

$sql = "SELECT nome, foto FROM prestadora WHERE id_prestadora = $id_prestadora";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $prestadora = $result->fetch_assoc();
    $nome = $prestadora['nome'];
    $foto = $prestadora['foto'] ?: "../img/perfil.png";
} else {
    $nome = "Usuário";
    $foto = "../img/perfil.png";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel da Prestadora - Avena</title>
  <link rel="stylesheet" href="../css/bemVindoPrestadora.css">
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
        <span class="nome"><?php echo htmlspecialchars($nome); ?></span>
        <img src="<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil" class="perfil-foto">
        <button class="menu-icon" id="menu-btn">&#9776;</button>
      </div>
    </nav>
  </header>

  <main class="conteudo">
    <h2>Bem vinda de volta, <?php echo htmlspecialchars($nome); ?>!</h2>
    <p>Gerencie seus serviços e encontre novas oportunidades hoje.</p>

    <div class="botoes">
      <a href="editarPerfil.php" class="btn editar-perfil">⚙️ Editar Perfil</a>
      <a href="editarServicos.php" class="btn editar-servicos">🖋️ Editar Serviços</a>
      <a href="mensagens.php" class="btn mensagens">💬 Mensagens</a>
      <a href="avaliacoes.php" class="btn avaliacoes">⭐ Avaliações</a>
      <a href="cursos.php" class="btn cursos">🎓 Cursos</a>
      <a href="agenda.php" class="btn agenda">📅 Minha Agenda</a>
    </div>
  </main>

  <script>
  const menuBtn = document.getElementById("menu-btn");
  menuBtn.addEventListener("click", () => alert("Menu lateral em construção..."));
  </script>
</body>
</html>
