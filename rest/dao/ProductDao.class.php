<?php

require_once  "BaseDao.class.php";

class ProductDao extends BaseDao{

    public function __construct(){
        parent::__construct("products");
    }

    public function get_by_id($id){
        return $this->query("SELECT p.id as id, p.name as name, p.description as description, p.price as price," .
        " p.category_id as category_id, p.supplier_id as supplier_id, " . 
        "p.image as image, c.name as category, s.name as supplier ".
        "FROM products p ".
        "JOIN categories c ON p.category_id = c.id ".
        "JOIN suppliers s ON s.id = p.supplier_id ".
        "WHERE p.id=:id",
        ['id' => $id]);

    }

    public function get_by_product_name($name) {
        return $this->query("SELECT * FROM products WHERE LOWER(products.name) LIKE CONCAT('%',LOWER(:name) ,'%') ", ['name' => $name]);
    }

    public function get_by_exact_product_name($name2) {
        return $this->query("SELECT * FROM products WHERE products.name=:name2 ", ['name2' => $name2]);
    }

    public function get_by_category_id($category_id) {
        return $this->query("SELECT * FROM products WHERE category_id=:category_id",
        ['category_id' => $category_id]);
    }

    public function get_by_supplier_id($supplier_id) {
        return $this->query("SELECT * FROM products WHERE supplier_id=:supplier_id",
        ['supplier_id' => $supplier_id]);
    }

    public function get_by_price_asc() {
        return $this->query("SELECT * FROM products ORDER BY price ASC", []);
    }

    public function get_by_price_desc() {
        return $this->query("SELECT * FROM products ORDER BY price DESC", []);
    }

    public function get_by_category_and_supplier($category_id, $supplier_id) {
        return $this->query("SELECT * FROM products WHERE category_id=:category_id" .
                            " AND supplier_id=:supplier_id",
                            ['category_id' => $category_id, 'supplier_id' => $supplier_id]);
    }

    public function get_by_category_and_supplier_price_asc($category_id, $supplier_id) {
        return $this->query("SELECT * FROM products WHERE category_id=:category_id" .
                            " AND supplier_id=:supplier_id ORDER BY price ASC",
                            ['category_id' => $category_id, 'supplier_id' => $supplier_id]);
    }

    public function get_by_category_and_supplier_price_desc($category_id, $supplier_id) {
        return $this->query("SELECT * FROM products WHERE category_id=:category_id" .
                            " AND supplier_id=:supplier_id ORDER BY price DESC",
                            ['category_id' => $category_id, 'supplier_id' => $supplier_id]);
        
    }

    public function get_by_category_and_price_asc($category_id) {
        return $this->query("SELECT * FROM products WHERE category_id=:category_id" .
                            " ORDER BY price ASC", ['category_id' => $category_id]);
    }

    public function get_by_category_and_price_desc($category_id) {
        return $this->query("SELECT * FROM products WHERE category_id=:category_id" .
                            " ORDER BY price DESC", ['category_id' => $category_id]);
    }

    public function get_by_supplier_and_price_asc($supplier_id) {
        return $this->query("SELECT * FROM products WHERE supplier_id=:supplier_id" .
                                            " ORDER BY price ASC", ['supplier_id' => $supplier_id]);
    }

    public function get_by_supplier_and_price_desc($supplier_id) {
        return $this->query("SELECT * FROM products WHERE supplier_id=:supplier_id" .
                                            " ORDER BY price DESC", ['supplier_id' => $supplier_id]);
    }


}


?>