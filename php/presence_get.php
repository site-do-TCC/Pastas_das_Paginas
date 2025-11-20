<?php
session_start();
require_once __DIR__ . '/conexao.php';
header('Content-Type: application/json; charset=utf-8');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conexao->set_charset('utf8mb4');
date_default_timezone_set('America/Sao_Paulo');

function resolveCurrentUser()
{
    $tipo = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : null; // 'cliente' ou 'profissional'
    $idPrest = (int) (isset($_SESSION['id_prestadora']) ? $_SESSION['id_prestadora'] : (isset($_SESSION['prestadora']['id_usuario']) ? $_SESSION['prestadora']['id_usuario'] : 0));
    $idCli   = (int) (isset($_SESSION['id_cliente']) ? $_SESSION['id_cliente'] : (isset($_SESSION['cliente']['id_usuario']) ? $_SESSION['cliente']['id_usuario'] : 0));

    if ($tipo === 'cliente') {
        return ['role' => 'cliente', 'id' => $idCli];
    }
    if ($tipo === 'profissional') {
        return ['role' => 'prestadora', 'id' => $idPrest];
    }
    // fallback: se tiver só cliente
    if ($idCli && !$idPrest) return ['role' => 'cliente', 'id' => $idCli];
    if ($idPrest && !$idCli) return ['role' => 'prestadora', 'id' => $idPrest];
    // ambiguo ou não logado
    return ['role' => null, 'id' => 0];
}

try {
    $otherId = isset($_GET['other_id']) ? (int) $_GET['other_id'] : 0;
    if ($otherId <= 0) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'erro' => 'other_id inválido']);
        exit;
    }

    $current = resolveCurrentUser();
    if (!$current['id'] || !$current['role']) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'erro' => 'Usuário não autenticado']);
        exit;
    }

    // papel do "outro" é oposto
    $otherRole = $current['role'] === 'cliente' ? 'prestadora' : 'cliente';

    // Garante tabela
    $conexao->query("CREATE TABLE IF NOT EXISTS presence (
        user_id INT NOT NULL,
        role ENUM('cliente','prestadora') NOT NULL,
        last_active DATETIME DEFAULT CURRENT_TIMESTAMP,
        typing_until DATETIME DEFAULT NULL,
        PRIMARY KEY(user_id, role)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $stmt = $conexao->prepare("SELECT last_active, typing_until FROM presence WHERE user_id = ? AND role = ? LIMIT 1");
    $stmt->bind_param('is', $otherId, $otherRole);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    $now = new DateTime('now');
    $typing = false;
    $online = false;

    if ($row) {
        if (!empty($row['typing_until'])) {
            $typingUntil = new DateTime($row['typing_until']);
            if ($typingUntil > $now) {
                $typing = true;
            }
        }
        if (!empty($row['last_active'])) {
            $lastActive = new DateTime($row['last_active']);
            $diff = $now->getTimestamp() - $lastActive->getTimestamp();
            // considera online se pingou nos últimos 60s
            $online = ($diff <= 60);
        }
    }

    echo json_encode(['ok' => true, 'typing' => $typing, 'online' => $online]);
    exit;

} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'erro' => $e->getMessage()]);
    exit;
}
