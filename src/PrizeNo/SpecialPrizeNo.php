<?php

namespace Invoice\PrizeNo;

use Invoice\Interfaces\PrizeNoInterface;
use Invoice\Exceptions\IOException;

class SpecialPrizeNo implements PrizeNoInterface {
    
    public $invoiceNumber = '';

    public $prizeAmount = 0;

    public function __construct($invoiceNumber, $prizeAmount) {
        if (!preg_match('/^[0-9]{8}$/', $invoiceNumber)) {
            throw new IOException('Special Prize No is error.'.$invoiceNumber);
        }

        if (!preg_match('/^[0-9]*$/', $prizeAmount) && $prizeAmount <= 0) {
            throw new IOException('Prize Amount is error.'.$prizeAmount);
        }

        $this->invoiceNumber = $invoiceNumber;
        $this->prizeAmount = (int)$prizeAmount;
    }

    public function getInvoiceNumber() {
        return $this->invoiceNumber;
    }

    public function getPrizeAmount() {
        return $this->prizeAmount;
    }

    public function isWinning($number) {
        if (preg_match('/^[0-9]{8}$/', $number) && $number == $this->invoiceNumber) {
            return true;
        }

        return false;
    }
}
