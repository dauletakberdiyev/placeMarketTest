<?php

namespace App\DTO;

class ProductDTO{
    public $name;
    public $price;
    public $picture;
    public $desc;

    public function __construct(string $name, int $price, string $picture, string $desc)
    {
        $this->name = $name;
        $this->price = $price;
        $this->picture = $picture;
        $this->desc = $desc;
    }
}   
?>