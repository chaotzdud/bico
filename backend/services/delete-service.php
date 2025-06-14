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

$data = json_decode(file_get_contents('php://input'), true);
$id = (int)($data['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ? AND publisher_id = ?");
    $stmt->execute([$id, $userId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Serviço deletado']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Serviço não encontrado ou sem permissão']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao deletar serviço']);
}
