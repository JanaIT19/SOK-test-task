<?php
namespace Controllers;

require_once('Helpers/SqlHelper.php');
require_once('Models/Category.php');

use Models\Category;

class CategoryController 
{
    /**
     * @param array
     * @return Category
     */
    public function createCategoryFromRequest(array &$response) {
        $keys = array_keys($response);

        $newCategory = new Category();
        
        foreach ($keys as $key) {
            if (property_exists($newCategory, $key)) {
                $newCategory->{$key} = $response[$key];    
            }
        }

        return $newCategory;
    }

    /**
     * @param array
     * @return array<Category>
     */
    public function buildCategoryList(array &$rawData) {
        $categoriesList = [];

        if ($rawData) {
            foreach ($rawData as $row) {
                $category = $this->createCategoryFromRequest($row);
                array_push($categoriesList, $category); 
            }
        }

        return $categoriesList;
    }

    /**
     * @param array<Category>
     * @return array
     */
    public function buildCategoryTree(array &$categories, $root = 0) {
        $branch = array();
    
        foreach ($categories as $category) {
          if ($category->parent_id == $root) {
              $children = $this->buildCategoryTree($categories, $category->id);
              if ($children && $children !== NULL) {
                  array_push($category->children, $children); 
              }
              $branch[$category->id] = $category;
              unset($categories[$category->id]);
          }
        }

        return $branch;
    }

    /**
     * @param string
     * @param array<Category>
     * @return string
     */
    public function buildCategoryTreeHTML(string $path, array &$categories, $root = 0) {
        $result = "<ul>";
    
        foreach ($categories as $category) {
            if ($category->parent_id == $root) {
                $result.= '<li><a class="menu-link info" href="/view.php?id=' .$category->id. '">' .$category->title. '</a>';
                $result.= '<a class="menu-link edit" href="/addedit.php?id=' .$category->id. '">Edit</a>';
                $result.= '<a class="menu-link delete" href="'.$path. '?id=' .$category->id. '">Delete</a>';

                if (count($category->children) > 0) {
                    $formattedCategoryChildren = reset($category->children);
                    $result.= $this->buildCategoryTreeHTML($path, $formattedCategoryChildren, $category->id); 
                }
                $result.= "</li>";
            }
        }
        $result.= "</ul>";

        return $result;
    }

    /**
     * @param int
     * @param array
     * @return array
     */
    public function getCategoryTreeChildren(int $parentId, array $queue, array $output = [], bool $needToSave = false)
    {
        if (count($queue) === 0) {
            return $output;
        }

        $category = array_shift($queue);
        if ($category instanceof Category) {
          if ($category->parent_id === $parentId) {
            $needToSave = true;
          }  

          if ($needToSave) {
            $output[] = $category->id;
          }
        }
        
        foreach ($category->children[0] ?? [] as $child) {
            $queue[] = $child;
        }

        return $this->getCategoryTreeChildren($parentId, $queue, $output, $needToSave);
    }
}
 
?>