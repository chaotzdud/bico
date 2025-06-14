<?php
require_once 'backend/includes/session.php';

startSecureSession();

if (isUserLoggedIn()) {
    header('Location: frontend/pages/home.html');
} else {
    header('Location: frontend/pages/login.html');
}
exit();