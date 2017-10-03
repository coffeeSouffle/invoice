<?php

namespace Invoice\Interfaces;

Interface PrizeNoInterface {
    public function getInvoiceNumber();

    public function getPrizeAmount();

    public function isWinning($number);
}
