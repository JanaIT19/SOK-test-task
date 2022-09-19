<?php
    session_start();

    include('Helpers/SqlHelper.php');
    include('Controllers/CategoryController.php');

    if (!isset($_SESSION['user_id'])) {
        header("Location:login.php");
    }

    $categoryController = new \Controllers\CategoryController();
    $sqlHelper = new \Helpers\SqlHelper();
    $category_object = null;


    if(isset($_POST["mode"])) {
        if($_POST["mode"] == "add") {
            if (isset($_POST['title']) && $_POST['description'] && $_POST['parent'] >= 0) {
                $newCategoryId = $sqlHelper->insertCategory($_POST['title'], $_POST['description'], $_POST['parent']);
                $category = $sqlHelper->getCategoryById($newCategoryId);
                $category_object = $categoryController->createCategoryFromRequest($category);
            }
        } else {
            if (isset($_POST['title']) && $_POST['description'] && $_POST['id']) {
                $sqlHelper->updateCategory($_POST['id'], $_POST['title'], $_POST['description']);
                $category = $sqlHelper->getCategoryById($_POST["id"]);
                $category_object = $categoryController->createCategoryFromRequest($category);
            }
        }
    }

    if(isset($_GET["id"])) {
        $category = $sqlHelper->getCategoryById($_GET["id"]);
        $category_object = $categoryController->createCategoryFromRequest($category);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        <?php
            if($category_object) {
                echo $category_object->title;
            }  else {
                echo "Warning";
            }  
        ?>
    </title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="page-wrapper"> 
        <h2 class="category-title">
            <?php
                if($category_object) {
                    echo $category_object->title;
                }
             ?>
        </h2>
        <p class="category-description">
            <?php
                if($category_object) {
                    echo $category_object->description;
                }
             ?>
        </p>
        <a class="button link back-link" href="/index.php">Go back to menu</a>
    </div> 
</body>
</html>
