<?php
require_once '../includes/session.php';
startSecureSession();

echo json_encode([
    "loggedIn" => isUserLoggedIn(),
    "user_id" => $_SESSION['user_id'] ?? null,
    "user_name" => $_SESSION['user_name'] ?? null
]);
