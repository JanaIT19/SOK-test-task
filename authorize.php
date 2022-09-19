<?php
    include('Helpers/SqlHelper.php');

    if (isset($_POST['username']) && $_POST['password']) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sqlHelper = new \Helpers\SqlHelper();
        $user = $sqlHelper->getUserByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        
        session_start();
        $_SESSION['user_id'] = $user['id'];

    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
?>