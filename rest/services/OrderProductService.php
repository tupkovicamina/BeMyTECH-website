<?php

require_once "BaseService.php";
require_once __DIR__."/../dao/OrderProductDao.class.php";

class OrderProductService extends BaseService {
    public function __construct() {
        parent::__construct(new OrderProductDao);
    }
}
?>