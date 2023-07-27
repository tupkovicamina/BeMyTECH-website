<?php

require_once "BaseService.php";
require_once __DIR__."/../dao/SupplierDao.class.php";

class SupplierService extends BaseService {
    public function __construct() {
        parent::__construct(new SupplierDao);
    }

    public function get_supplier_by_name($supplierName) {
        return $this->dao->get_supplier_by_name($supplierName);
    }
}
?>