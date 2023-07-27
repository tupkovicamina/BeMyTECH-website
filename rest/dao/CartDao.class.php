<?php

require_once  "BaseDao.class.php";

class CartDao extends BaseDao{

    public function __construct(){
        parent::__construct("carts");
    }

    public function get_by_user_id($user_id) {
        return $this->query("SELECT c.id as id, c.user_id as user_id, c.quantity as quantity, c.product_id as product_id,".
        " p.name as product_name, p.price as product_price, p.image as product_image ".
        "FROM carts  c ".
        "JOIN products p on c.product_id = p.id ".
        "WHERE user_id=:user_id", ['user_id' => $user_id]);
    
    }

    public function delete_by_user_id($user_id) {
        return $this->query("DELETE FROM carts WHERE user_id=:user_id", ['user_id' => $user_id]);
    }

    public function delete_by_user_and_product($user_id, $product_id) {
        return $this->query("DELETE FROM carts WHERE user_id=:user_id" . 
                            " AND product_id=:product_id", ['user_id' => $user_id, 'product_id' => $product_id]);
    }

    public function delete_by_product_id($product_id) {
        return $this->query("DELETE FROM carts WHERE product_id=:product_id",['product_id' => $product_id]);
    }
}

?>