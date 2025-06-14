<?php
require_once '../includes/session.php';
require_once '../../db/connect.php';

startSecureSession();
header('Content-Type: application/json');

if (!isUserLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT id, title, description, price, location FROM services WHERE id = ? AND publisher_id = ?");
    $stmt->execute([$id, $userId]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($service) {
        echo json_encode(['success' => true, 'service' => $service]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Serviço não encontrado']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar serviço']);
}
