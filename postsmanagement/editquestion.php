<?php
session_start();
require_once '../includes/DatabaseConnection.php';
require_once '../includes/DatabaseFunction.php';  // Bao gồm file chứa các function

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: ../corephpfiles/community.php');
    exit;
}

// Lấy thông tin người dùng và modules
$users = $pdo->query('SELECT id, username FROM users')->fetchAll();
$modules = $pdo->query('SELECT id, name FROM modules')->fetchAll();

// Lấy câu hỏi từ ID
$stmt = $pdo->prepare('SELECT * FROM questions WHERE id = ?');
$stmt->execute([$id]);
$question = $stmt->fetch();

if (!$question) {
    echo "Question not found.";
    exit;
}

// Xử lý nếu yêu cầu là POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $user_id = $_SESSION['user_id'];
    $module_id = $_POST['module_id'] ?? null;

    // Kiểm tra và xử lý ảnh
    $imagePath = uploadImageEQ($_FILES['image'] ?? null);  // Sử dụng hàm uploadImage từ DatabaseFunction.php

    // Cập nhật câu hỏi
    updateQuestion($pdo, $id, $title, $content, $user_id, $module_id, $imagePath);  // Sử dụng hàm updateQuestion

    // Chuyển hướng sau khi cập nhật
    header('Location: ../corephpfiles/community.php');
    exit;
}

// Render form chỉnh sửa câu hỏi
ob_start();
include '../templates/editquestion.html.php';
$content = ob_get_clean();

include '../templates/layout.html.php';
