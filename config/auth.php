<?php
session_start();

function login($username, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        return true;
    }

    return false;
}

function is_logged_in() {
    return isset($_SESSION['user']);
}


function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}