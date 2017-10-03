<?php

namespace Invoice;

use Invoice\Exceptions\IOException;

use Invoice\PrizeNo\SupperPrizeNo;
use Invoice\PrizeNo\SpecialPrizeNo;
use Invoice\PrizeNo\FirstPrizeNo;
use Invoice\PrizeNo\SecondPrizeNo;
use Invoice\PrizeNo\ThirdPrizeNo;
use Invoice\PrizeNo\FourthPrizeNo;
use Invoice\PrizeNo\FifthPrizeNo;
use Invoice\PrizeNo\SixthPrizeNo;

class PrizeNo {

    public $invoiceStartDate = null;

    public $invoiceEndDate = null;

    public $prizeNos = array();

    public $prizeAmount = array();

    public function __construct($winningList) {
        if (!is_array($winningList) || empty($winningList)) {
            throw new IOException('winning number list is error');
        }

        if (!isset($winningList['code']) || $winningList['code'] != 200) {
            throw new IOException('code is error. '.$winningList['code']);
        }

        $this->handle($winningList);
    }

    public function getInvoiceStartDate() {
        return $this->invoiceStartDate;
    }

    public function getInvoiceEndDate() {
        return $this->invoiceEndDate;
    }

    public function getPrizeNos() {
        return $this->prizeNos;
    }

    public function isWinning($number) {
        foreach ($this->prizeNos as $obj) {
            if ($obj->isWinning($number)) {
                return true;
            }
        }

        return false;
    }

    public function getWinningPrizeAmount($number) {
        foreach ($this->prizeNos as $obj) {
            if ($obj->isWinning($number)) {
                return $obj->getPrizeAmount();
            }
        }

        return 0;
    }

    private function handle($winningList) {

        if (isset($winningList['superPrizeNo']) && !empty($winningList['superPrizeNo'])) {
            $this->prizeNos[] = new SupperPrizeNo($winningList['superPrizeNo'], $winningList['superPrizeAmt']);
        }

        if (isset($winningList['spcPrizeNo']) && !empty($winningList['spcPrizeNo'])) {
            $this->prizeNos[] = new SpecialPrizeNo($winningList['spcPrizeNo'], $winningList['spcPrizeAmt']);
        }

        if (isset($winningList['spcPrizeNo2']) && !empty($winningList['spcPrizeNo2'])) {
            $this->prizeNos[] = new SpecialPrizeNo($winningList['spcPrizeNo2'], $winningList['spcPrizeAmt']);
        }

        if (isset($winningList['spcPrizeNo3']) && !empty($winningList['spcPrizeNo3'])) {
            $this->prizeNos[] = new SpecialPrizeNo($winningList['spcPrizeNo3'], $winningList['spcPrizeAmt']);
        }

        for ($i = 1; $i <= 10; $i++) {
            $key = 'firstPrizeNo'.$i;
            if (isset($winningList[$key]) && !empty($winningList[$key])) {
                $val = $winningList[$key];
                $this->prizeNos[] = new FirstPrizeNo($val, $winningList['firstPrizeAmt']);
                $this->prizeNos[] = new SecondPrizeNo($val, $winningList['secondPrizeAmt']);
                $this->prizeNos[] = new ThirdPrizeNo($val, $winningList['thirdPrizeAmt']);
                $this->prizeNos[] = new FourthPrizeNo($val, $winningList['fourthPrizeAmt']);
                $this->prizeNos[] = new FifthPrizeNo($val, $winningList['fifthPrizeAmt']);
                $this->prizeNos[] = new SixthPrizeNo($val, $winningList['sixthPrizeAmt']);
            }
        }

        for ($i = 1; $i <= 6; $i++) {
            $key = 'sixthPrizeNo'.$i;
            if (isset($winningList[$key]) && !empty($winningList[$key])) {
                $this->prizeNos[] = new SixthPrizeNo($winningList[$key], $winningList['sixthPrizeAmt']);
            }
        }
    }
}
