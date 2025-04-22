<?php
session_start();

require_once '../includes/DatabaseConnection.php';
require_once '../includes/DatabaseFunction.php'; // Thêm dòng này để dùng các hàm PDO

// Restrict to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo 'Access denied. Admins only.';
    exit;
}

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    if (!empty($username) && !empty($email)) {
        addUser($pdo, $username, $email); // Gọi từ DatabaseFunction.php
    }
    header('Location: manageusers.php');
    exit;
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'] ?? 0;
    deleteUser($pdo, $id); // Gọi từ DatabaseFunction.php
    header('Location: manageusers.php');
    exit;
}

// Get data
$users = getAllUsers($pdo); // Gọi từ DatabaseFunction.php

// Render template
ob_start();
include '../templates/manageusers.html.php';
$content = ob_get_clean();
include '../templates/layout.html.php';
