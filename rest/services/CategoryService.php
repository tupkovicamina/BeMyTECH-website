<?php

require_once "BaseService.php";
require_once __DIR__."/../dao/CategoryDao.class.php";

class CategoryService extends BaseService {
    public function __construct() {
        parent::__construct(new CategoryDao);
    }

    public function get_category_by_name($categoryName) {
        return $this->dao->get_category_by_name($categoryName);
    }
}
?>