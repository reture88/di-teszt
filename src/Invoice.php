<?php

declare(strict_types=1);

namespace src;

use src\Log;

class Invoice
{ 
    public function __construct(
        protected Log $log
    )
    {}
    
}