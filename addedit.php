<?php
    session_start();

    include('Helpers/SqlHelper.php');
    include('Controllers/CategoryController.php');

    if (!isset($_SESSION['user_id'])) {
        header("Location:login.php");
    }

    $editMode = false;

    $categoryController = new \Controllers\CategoryController();
    $sqlHelper = new \Helpers\SqlHelper();
    $category_object = null;

    if(isset($_GET['id'])) {
        $editMode = true;

        $category = $sqlHelper->getCategoryById($_GET["id"]);
        $category_object = $categoryController->createCategoryFromRequest($category);
    }

    $categoriesData = $sqlHelper->getAllCategories();
    $categoriesList = $categoryController->buildCategoryList($categoriesData);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        <?php
            if ($editMode) {
                echo 'Edit Category';
            } else {
                echo 'Add Category';
            }
        ?>
    </title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
    <div class="page-wrapper">
        <form action="/view.php" method="POST">
            <div class="category-information">
                <div class="form-element category title">
                    <label for="title">Category Title:</label>
                    <input type="text" name="title"
                        <?php
                            if ($editMode) {
                                echo 'value="' .$category_object->title. '"';
                            } 
                        ?>
                    >
                 </div>
                <div class="form-element category description">
                    <label for="description">Category Description:</label>
                    <input type="text" name="description"
                        <?php
                            if ($editMode) {
                                echo 'value="' .$category_object->description. '"';
                            } 
                        ?>
                    >
                </div>
                <?php
                    if (!$editMode) {
                        $noParentId = 0;
                        $categoryParentDropdown='';
                        $categoryParentDropdown.= '<div class="form-element category parent"><label for="parent">Category Parent</label><select name="parent">';

                        foreach ($categoriesList as $category) {
                            $categoryParentDropdown.= '<option value="' .$category->id. '">' .$category->title. '</option>';
                        }

                        $categoryParentDropdown.= '<option value="0">No category</option>';

                        $categoryParentDropdown.= '</select></div>';

                        echo $categoryParentDropdown;
                    } else {
                        $categoryIdField='';
                        $categoryIdField.= '<input type="hidden" name="id" value="' .$category_object->id. '">';
                        echo $categoryIdField;
                    }
                ?> 
                
                <input type="hidden" name="mode" 
                    <?php
                        if ($editMode) {
                            echo 'value="edit"';
                        } else {
                            echo 'value="add"';
                        }
                    ?>
                >
            </div>
            <input class="button link" type="submit" value="Submit">
        </form>
    </div>
</body>
</html>