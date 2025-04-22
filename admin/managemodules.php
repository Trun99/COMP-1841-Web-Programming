<?php
session_start();

require_once '../includes/DatabaseConnection.php';
require_once '../includes/DatabaseFunction.php'; // Thêm để sử dụng các hàm

// Restrict to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    echo 'Access denied. Admins only.';
    exit;
}

// Add module
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $modulename = $_POST['modulename'] ?? '';

    if (!empty($modulename)) {
        addModule($pdo, $modulename); // Gọi hàm từ DatabaseFunction.php
    }

    header('Location: managemodules.php');
    exit;
}

// Delete module and related questions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'] ?? 0;
    deleteModule($pdo, $id); // Gọi hàm từ DatabaseFunction.php
    header('Location: managemodules.php');
    exit;
}

// Fetch all modules
$modules = getAllModules($pdo); // Gọi hàm từ DatabaseFunction.php

// Render view
ob_start();
include '../templates/managemodules.html.php';
$content = ob_get_clean();
include '../templates/layout.html.php';
?>
