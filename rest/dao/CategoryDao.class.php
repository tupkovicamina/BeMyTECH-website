<?php

require_once  "BaseDao.class.php";

class CategoryDao extends BaseDao{

    public function __construct(){
        parent::__construct("categories");
    }

    public function get_category_by_name($categoryName) {
        return $this->query("SELECT * FROM categories WHERE name = :name", ['name' => $categoryName]);
    }
}

?>