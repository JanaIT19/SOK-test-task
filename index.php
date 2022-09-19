<?php
    session_start();

    include('Helpers/SqlHelper.php');
    include('Controllers/CategoryController.php');

    $sqlHelper = new \Helpers\SqlHelper();
    $categoryController = new \Controllers\CategoryController();
    $path = $_SERVER['PHP_SELF'];

    $categoriesData = $sqlHelper->getAllCategories();
    $categoriesList = $categoryController->buildCategoryList($categoriesData);
    $categoryTree = $categoryController->buildCategoryTree($categoriesList);


    if (isset($_POST['username']) && $_POST['password']) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = $sqlHelper->getUserByUsername($username);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    
        $_SESSION['user_id'] = $user['id'];
    }

    if(isset($_GET['id'])) {
        $categoriesToDelete = [];

        foreach($categoryTree as $branch_key => $branch_value) {
            $queue = [$branch_value];
            $categoriesToDelete += $categoryController->getCategoryTreeChildren($_GET['id'], $queue);
        }

        array_push($categoriesToDelete, (int) $_GET['id']); 

        foreach($categoriesToDelete as $category) {
            $sqlHelper->deleteCategoryById($category);
            $sec = "1";
            header("Refresh: $sec; url=$path");
        }
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location:login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        Home
    </title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="page-wrapper">
        <div class="navbar-wrapper">
            <?php
                echo $categoryController->buildCategoryTreeHTML($path, $categoryTree);
            ?>
        </div>
        <div class="button-wrapper">
            <a class="button link createnew-link" href="/addedit.php">Create new category</a>
        </div>
    </div>
</body>
</html>