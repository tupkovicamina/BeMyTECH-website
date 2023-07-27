<?php

require_once "BaseDao.class.php";

class UserDao extends BaseDao {

    public function __construct(){
        parent::__construct("users");
    }

    public function get_user_by_email($email){
        return $this->query("SELECT * FROM users WHERE email_address = :email_address", ['email_address' => $email]);
    }

}

?>