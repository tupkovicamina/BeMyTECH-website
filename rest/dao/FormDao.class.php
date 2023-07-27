<?php

require_once  "BaseDao.class.php";

class FormDao extends BaseDao {

    public function __construct(){
        parent::__construct("forms");
    }
}

?>