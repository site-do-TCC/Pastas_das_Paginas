<?php
// Ativa debug de erros (remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sessão e conexão
session_start();

// Inclui a conexão (arquivo deve estar em Programacao_TCC_avena/php/conexao.php)
include_once('../php/conexao.php');

// Valor padrão
$nome = "Usuário";

// DEBUG: ver sessão (descomente se precisar)
# echo '<pre>'; print_r($_SESSION); echo '</pre>';

// Verifica se conexão existe
if (!isset($conexao)) {
    // Se faltar conexão, mostra mensagem clara e encerra
    die("Erro: conexão com o banco não encontrada. Verifique ../php/conexao.php");
}

// Se houver id_cliente (que no seu banco é id_usuario), busca o nome
if (!empty($_SESSION['id_cliente'])) {
    // Pegamos o id que você já colocou na sessão (no validarLogin.php, deve ter sido: $_SESSION['id_cliente'] = $dados['id_usuario'])
    $idCliente = (int) $_SESSION['id_cliente'];

    // Prepara a query. Use a coluna correta no WHERE: id_usuario
    $stmt = $conexao->prepare("SELECT nome FROM cliente WHERE id_usuario = ?");
    if ($stmt === false) {
        // Se prepare falhar, opcionalmente mostrar erro (remova em produção)
        // echo "Erro no prepare: " . $conexao->error;
    } else {
        $stmt->bind_param("i", $idCliente);
        $executou = $stmt->execute();
        if ($executou) {
            $resultado = $stmt->get_result();
            if ($resultado && $resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                if (!empty($row['nome'])) {
                    $nome = $row['nome'];
                }
            } else {
                // Nenhum registro encontrado para esse id (debug opcional)
                // echo "Nenhum cliente encontrado para id_usuario = $idCliente";
            }
        } else {
            // Erro na execução (debug opcional)
            // echo "Erro ao executar query: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel - Avena</title>

  <!-- Bootstrap (opcional, como no seu login) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

  <!-- Seu CSS (ajuste caminho se necessário) -->
  <link rel="stylesheet" href="../css/Login.css">
  <link rel="stylesheet" href="../css/bemVindoCliente.css"> <!-- se tiver CSS separado -->
</head>
<body>

  <!-- NAV / TOPO (mesma base do login) -->
  <header>
    <nav>
      <div class="logo">
        <a href="Pagina_Inicial.html">
          <img src="../img/logoAvena.png" alt="Logo Avena">
        </a>
      </div>

      <div class="perfil-area">
        <span class="nome"><?php echo htmlspecialchars($nome); ?></span>

        <!-- foto de perfil -->
        <img src="../img/perfil.png" alt="Foto de perfil" class="perfil-foto">

        <!-- botão do menu (igual ao login) -->
        <button class="menu-icon" id="menu-btn">&#9776;</button>
      </div>
    </nav>
  </header>

  <!-- Menu lateral (igual ao login) -->
  <nav id="menulogin" class="hidden">
    <ul>
      <li><a href="sobre.html"><span class="quemSomos">Quem somos</span></a></li>
      <li><a href="cadastro.php">Cadastrar-se</a></li>
      <hr>
      <li><a href="contato.html">Seja um Parceiro</a></li>
      <li><a href="suporte.html">Suporte</a></li>
    </ul>
  </nav>

  <!-- Conteúdo principal -->
  <main class="conteudo">
    <div class="container">
      <h2>Bem-vindo de volta, <?php echo htmlspecialchars($nome); ?>!</h2>
      <p>Encontre prestadoras de serviços qualificadas para as suas necessidades.</p>

      <div class="botoes">
        <a href="busca.php" class="btn buscar">🔍 Buscar Serviços</a>
        <a href="agenda.php" class="btn agenda">📅 Minha Agenda</a>
        <a href="contato.html" class="btn mensagens">💬 Mensagens</a>
        <a href="avaliacoes.php" class="btn avaliacoes">⭐ Minhas Avaliações</a>
      </div>
    </div>
  </main>

  <script src="../js/login.js"></script> 
  <script>
   
   (<?php echo json_encode($_SESSION); ?>);
  </script>
</body>
</html>
