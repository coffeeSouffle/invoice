<?php

namespace Invoice\Exceptions;

class IOException extends \Exception {
    public function __construct($message = "", $code = 0, $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }
}
