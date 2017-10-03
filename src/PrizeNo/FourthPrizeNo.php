<?php

namespace Invoice\PrizeNo;

use Invoice\Interfaces\PrizeNoInterface;
use Invoice\Exceptions\IOException;

class FourthPrizeNo implements PrizeNoInterface {
    
    public $invoiceNumber = '';

    public $prizeAmount = 0;

    public $checkNumber = '';

    public function __construct($invoiceNumber, $prizeAmount) {
        if (!preg_match('/^[0-9]{3}([0-9]{5})$/', $invoiceNumber, $match)) {
            throw new IOException('Fourth Prize No is error.'.$invoiceNumber);
        }

        if (!preg_match('/^[0-9]*$/', $prizeAmount) && $prizeAmount <= 0) {
            throw new IOException('Prize Amount is error.'.$prizeAmount);
        }

        $this->invoiceNumber = $invoiceNumber;
        $this->prizeAmount = (int)$prizeAmount;

        $this->checkNumber = $match[1];
    }

    public function getInvoiceNumber() {
        return $this->invoiceNumber;
    }

    public function getPrizeAmount() {
        return $this->prizeAmount;
    }

    public function isWinning($number) {
        if (preg_match('/^[0-9]{3}([0-9]{5})$/', $number, $match) && $match[1] == $this->checkNumber) {
            return true;
        }

        return false;
    }
}
