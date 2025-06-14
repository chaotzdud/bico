<?php
require_once '../includes/session.php';
require_once '../../db/connect.php';

startSecureSession();
header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT s.id, s.title, s.description, s.price, s.location, s.created_at, s.image, u.fname AS publisher
        FROM services s
        JOIN users u ON s.publisher_id = u.id
        WHERE s.id = ?
    ");
    $stmt->execute([$id]);
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
