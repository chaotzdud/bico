<?php
require_once '../../db/connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Nenhum dado recebido."]);
    http_response_code(400);
    exit;
}

$fname = $data['fname'] ?? null;
$lname = $data['lname'] ?? null;
$dbirth = $data['dbirth'] ?? null;
$cpf = $data['cpf'] ?? null;
$email = $data['email'] ?? null;
$password = isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null;

if (!$fname || !$lname || !$dbirth || !$cpf || !$email || !$password) {
    echo json_encode(["success" => false, "message" => "Campos incompletos."]);
    http_response_code(422);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO users (fname, lname, dbirth, cpf, email, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$fname, $lname, $dbirth, $cpf, $email, $password]);
    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(["success" => false, "message" => "CPF ou e-mail já cadastrado."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao cadastrar usuário."]);
    }
}
