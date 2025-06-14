<?php
require_once '../includes/session.php';
require_once '../../db/connect.php';

startSecureSession();

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "Dados inválidos ou não recebidos."]);
    http_response_code(400);
    exit;
}

$email = $data['email'];
$password = $data['password'];

try {
    $stmt = $conn->prepare("SELECT id, fname, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['fname'];
        echo json_encode(["success" => true]);
        exit;
    }

    echo json_encode(["success" => false, "message" => "Credenciais inválidas."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erro no login."]);
}
