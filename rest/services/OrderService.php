<?php

require_once "BaseService.php";
require_once "OrderProductService.php";
require_once "CartService.php";
require_once __DIR__."/../dao/OrderDao.class.php";

class OrderService extends BaseService {

    private $order_product_service; 
    private $cart_service;

    public function __construct() {
        parent::__construct(new OrderDao);
        $this->order_product_service = new OrderProductService();
        $this->cart_service = new CartService();
    }

    public function get_by_user_id($user_id){
        return $this->dao->get_by_user_id($user_id);
    }

    public function add($entity) {
        $order = $entity;
        unset($order['product_id']);
        unset($order['quantity']);
        $added_order = $this->dao->add($order);

        $order_product = $entity;
        unset($order_product['user_id']);
        unset($order_product['order_date']);
        unset($order_product['address_id']);
        $order_product['order_id'] = $added_order['id'];

        return $this->order_product_service->add($order_product);
        
    }

    public function add_by_user_id($entity) {
        $order = $entity[0];
        unset($order['product_id']);
        unset($order['quantity']);
        unset($order['product_name']);
        unset($order['product_price']);
        unset($order['product_image']);
        unset($order['id']);
        $added_order = $this->dao->add($order);

        foreach ($entity as $order_product) {
            unset($order_product['user_id']);
            unset($order_product['order_date']);
            unset($order_product['address_id']);
            unset($order_product['product_name']);
            unset($order_product['product_price']);
            unset($order_product['product_image']);
            unset($order_product['id']);    
            $order_product['order_id'] = $added_order['id'];
            $this->order_product_service->add($order_product);
        } 
        
        $user_id = $added_order['user_id'];

        return $this->cart_service->delete_by_user_id($user_id);
        
    }
}
?>