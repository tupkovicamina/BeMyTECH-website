<?php

require_once  "BaseDao.class.php";

class OrderDao extends BaseDao{

    public function __construct(){
        parent::__construct("orders");
    }

    public function get_by_user_id($user_id) {
        return $this->query("SELECT o.order_date, group_concat(concat(' ',p.name, ' (', op.quantity, 'pcs)')) as 'products_bought' ".
        "FROM orders o " .
        "JOIN order_products op ON op.order_id = o.id " .
        "JOIN products p ON p.id = op.product_id " .
        "WHERE o.user_id = :user_id " .
        "GROUP BY o.id ".
        "ORDER BY o.id DESC ".
        "LIMIT 5;", ['user_id' => $user_id]);
    }
    
}

?>