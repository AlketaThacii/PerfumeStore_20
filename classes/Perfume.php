<?php

require_once __DIR__ . '/Products.php';

class Perfume extends Product{
    private $brand;

    public function __construct($id,$name,$price,$category,$brand){
        parent::__construct($id,$name,$price,$category);
         $this->brand=$brand;
    }

    public function getBrand() {
    return $this->brand;
    }

    public function setBrand($brand) {
        $this->brand=$brand;
    }

}