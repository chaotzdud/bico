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

$userId = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("
        SELECT s.id, s.title, s.description, s.price, s.location, s.created_at, s.image, u.fname AS publisher
        FROM services s
        JOIN users u ON s.publisher_id = u.id
        WHERE s.publisher_id != ?
        ORDER BY s.created_at DESC
    ");
    $stmt->execute([$userId]);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'services' => $services]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao listar serviços']);
}
