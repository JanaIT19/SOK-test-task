<?php

namespace Models;

class Category 
{
    public int $id;
    public int $parent_id;
    public string $title;
    public string $description;
    public array $children;

    public function __construct() 
    {
        $this->children = [];
    }
}
?>