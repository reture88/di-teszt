<?php

namespace src\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends \Exception implements NotFoundExceptionInterface {}