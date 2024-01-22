<?php

declare(strict_types=1);

namespace src;

use src\Product;
use src\Invoice;

class Order
{ 
    public function __construct(
        protected Product $product,
        protected Invoice $invoice
    )
    {}

    public function teszt(){
        return $this->product->name; 
    }
    
}