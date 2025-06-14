<?php
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}