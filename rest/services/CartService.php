<?php

require_once "BaseService.php";
require_once __DIR__."/../dao/CartDao.class.php";

class CartService extends BaseService {
    public function __construct() {
        parent::__construct(new CartDao);
    }

    public function get_by_user_id($user_id) {
        return $this->dao->get_by_user_id($user_id);
    }

    public function delete_by_user_id($user_id) {
        return $this->dao->delete_by_user_id($user_id);
    }

    public function delete_by_user_and_product($user_id, $product_id) {
        return $this->dao->delete_by_user_and_product($user_id, $product_id);
    }

    public function delete_by_product_id($product_id) {
        return $this->dao->delete_by_product_id($product_id);
    }

}
?>