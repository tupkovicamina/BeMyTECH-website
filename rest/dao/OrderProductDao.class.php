<?php

require_once  "BaseDao.class.php";

class OrderProductDao extends BaseDao {

    public function __construct(){
        parent::__construct("order_products");
    }

}

?>