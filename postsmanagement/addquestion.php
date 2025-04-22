<?php
session_start();
require_once '../includes/DatabaseConnection.php';
require_once '../includes/DatabaseFunction.php';  //make sure UserExxists includ

// Đảm bảo rằng người dùng đã đăng nhập
$user_id = $_SESSION['user_id'] ?? null;  // Lấy user_id từ session
if (!$user_id) {
    die("Error: User is not logged in.");
}

// Kiểm tra nếu người dùng tồn tại trong cơ sở dữ liệu
if (!userExists($pdo, $user_id)) {
    die("Error: User does not exist in the database.");
}

// Kiểm tra xem yêu cầu có phải là POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $module_id = $_POST['module_id'] ?? null;

    // Xử lý hình ảnh nếu có
    $imagePath = uploadImage($_FILES['image'] ?? null);

    // Thêm câu hỏi vào cơ sở dữ liệu
    insertQuestion($pdo, $title, $content, $user_id, $module_id, $imagePath);

    // Chuyển hướng về trang community.php sau khi thêm câu hỏi
    header('Location: ../corephpfiles/community.php');
    exit;
} else {
    // Lấy danh sách người dùng và module
    $users = $pdo->query('SELECT id, username FROM users')->fetchAll();
    $modules = $pdo->query('SELECT id, name FROM modules')->fetchAll();

    // Hiển thị form thêm câu hỏi
    ob_start();
    include '../templates/addquestion.html.php';
    $content = ob_get_clean();

    include '../templates/layout.html.php';
}
