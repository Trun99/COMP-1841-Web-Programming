<?php
session_start();
require_once '../includes/DatabaseConnection.php';
require_once '../includes/DatabaseFunction.php'; // Thêm để sử dụng các hàm

// Lấy tất cả người dùng, modules, và câu hỏi
$users = getAllUsersBasic($pdo); // Gọi hàm getAllUsers từ DatabaseFunction.php
$modules = getAllModulesBasic($pdo); // Gọi hàm getAllModules từ DatabaseFunction.php
$questions = getAllQuestionsBasic($pdo); // Gọi hàm getAllQuestions từ DatabaseFunction.php

// Render view
ob_start();
include '../templates/addquestion.html.php'; 
include '../templates/questions.html.php';   
$content = ob_get_clean();

include '../templates/layout.html.php'; 
?>
