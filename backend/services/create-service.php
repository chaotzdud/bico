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

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$location = trim($_POST['location'] ?? '');
$price = floatval($_POST['price'] ?? 0);

if (
    !$title || !$description || !$location || !$price ||
    !isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK
) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios, incluindo a imagem']);
    exit;
}

$uploadDir = __DIR__ . '/../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$imageName = uniqid('img_') . '_' . basename($_FILES['image']['name']);
$imagePath = $uploadDir . $imageName;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar imagem']);
    exit;
}

$imageURL = '/bico/backend/uploads/' . $imageName;
$userId = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("INSERT INTO services (title, description, location, price, image, publisher_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $location, $price, $imageURL, $userId]);
    echo json_encode(['success' => true, 'message' => 'Serviço criado com sucesso']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro ao criar serviço']);
}
