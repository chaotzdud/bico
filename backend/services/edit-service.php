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
$title = trim($data['title'] ?? '');
$description = trim($data['description'] ?? '');
$price = $data['price'] ?? '';
$location = $data['location'] ?? '';
$userId = $_SESSION['user_id'];

$fields = [];
$params = [];

if (!empty($title)) {
    $fields[] = 'title = ?';
    $params[] = $title;
}
if (!empty($description)) {
    $fields[] = 'description = ?';
    $params[] = $description;
}
if ($price !== '' && is_numeric($price)) {
    $fields[] = 'price = ?';
    $params[] = $price;
}
if (!empty($location)) {
    $fields[] = 'location = ?';
    $params[] = $location;
}

if (empty($fields)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nenhum campo para atualizar']);
    exit;
}

$params[] = $id;
$params[] = $userId;

$sql = "UPDATE services SET " . implode(', ', $fields) . " WHERE id = ? AND publisher_id = ?";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Serviço atualizado']);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Serviço não encontrado ou sem permissão']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar serviço']);
}
