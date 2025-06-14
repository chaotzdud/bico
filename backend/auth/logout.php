<?php
require_once '../includes/session.php';

startSecureSession();
session_unset();
session_destroy();

echo json_encode(["success" => true]);