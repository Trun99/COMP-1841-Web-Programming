<?php
require_once '../includes/DatabaseConnection.php';
require_once '../includes/DatabaseFunction.php';

// Lấy danh sách câu hỏi từ hàm đã tạo
$questions = getAllQuestions($pdo);

ob_start();
include '../templates/questions.html.php';
$content = ob_get_clean();

include '../templates/layout.html.php';
