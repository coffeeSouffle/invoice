<?php

namespace Invoice;

use Invoice\Exceptions\IOException;

class PrizeNo {

    public $invoiceStartDate = null;

    public $invoiceEndDate = null;

    public $numberOfPeriods = '';

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

        if (!preg_match('/^[0-9]{8}$/', $number)) {
            return false;
        }

        foreach ($this->prizeNos as $prizeNo => $invoiceNumbers) {
            foreach ($invoiceNumbers as $invoiceNumber) {
                if ($this->$prizeNo($number, $invoiceNumber) > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getWinningPrizeAmount($number) {
        if (!preg_match('/^[0-9]{8}$/', $number)) {
            return 0;
        }

        foreach ($this->prizeNos as $prizeNo => $invoiceNumbers) {
            foreach ($invoiceNumbers as $invoiceNumber) {
                $prizeAmount = $this->$prizeNo($number, $invoiceNumber);
                if ($prizeAmount > 0) {
                    return $prizeAmount;
                }
            }
        }

        return 0;
    }

    private function handle($winningList) {
        foreach ($winningList as $key => $val) {

            if ($key == 'invoYm' && preg_match('/^([0-9]{3})(0[1-9]|10|11|12)$/', $val, $match)) {
                $year = 1911 + $match[1];
                $peroidsLastMonth = $match[2];
                $peroidsFirstMonth = str_pad(($peroidsLastMonth - 1), 2, '0', STR_PAD_LEFT);
                $this->numberOfPeriods = $val;

                $taipeiTimeZone = new \DateTimeZone('Asia/Taipei');
                $UTC = new \DateTimeZone('UTC');

                $startDate = new \DateTime("{$year}-{$peroidsFirstMonth}-01", $taipeiTimeZone);
                $startDate->setTimezone($UTC);
                $this->invoiceStartDate = $startDate;

                $lastDate = new \DateTime("{$year}-{$peroidsLastMonth}-".cal_days_in_month(CAL_GREGORIAN, $peroidsLastMonth, $year), $taipeiTimeZone);
                $lastDate->setTimezone($UTC);
                $this->invoiceEndDate = $lastDate;
            }

            if (preg_match('/PrizeAmt$/', $key)) {
                if ($val <= 0 || !preg_match('/^[0-9]+$/', $val)) {
                    throw new IOException("{$key} is error. {$val}");
                }

                $this->prizeAmount[$key] = (int)$val;
            }

            if (preg_match('/^superPrizeNo$/', $key) && !empty($val)) {
                
                if (!preg_match('/^[0-9]{8}$/', $val)) {
                    throw new IOException("{$key} is error. {$val}");
                }

                $this->prizeNos[$key][] = $val;
            }

            if (preg_match('/^(spcPrizeNo)[23]?$/', $key, $match) && !empty($val)) {
                
                if (!preg_match('/^[0-9]{8}$/', $val)) {
                    throw new IOException("{$key} is error. {$val}");
                }

                $this->prizeNos[$match[1]][] = $val;
            }

            if (preg_match('/^(firstPrizeNo)(?:[1-9]|10)$/', $key, $match) && !empty($val)) {

                if (!preg_match('/^[0-9]{8}$/', $val)) {
                    throw new IOException("{$key} is error. {$val}");
                }

                $this->prizeNos[$match[1]][] = $val;
            }

            if (preg_match('/^(sixthPrizeNo)(?:[1-6])$/', $key, $match) && !empty($val)) {

                if (!preg_match('/^[0-9]{3}$/', $val)) {
                    throw new IOException("{$key} is error. {$val}");
                }

                $this->prizeNos[$match[1]][] = $val;
            }
        }

        if (!isset($this->prizeNos['superPrizeNo'])) {
            throw new IOException("superPrizeNo is empty.");
        }

        if (!isset($this->prizeNos['spcPrizeNo'])) {
            throw new IOException("spcPrizeNo is empty.");
        }

        if (!isset($this->prizeNos['firstPrizeNo'])) {
            throw new IOException("firstPrizeNo is empty.");
        }

        if (!isset($this->prizeAmount['superPrizeAmt'])) {
            throw new IOException("superPrizeAmt is empty.");
        }

        if (!isset($this->prizeAmount['spcPrizeAmt'])) {
            throw new IOException("spcPrizeAmt is empty.");
        }

        if (!isset($this->prizeAmount['firstPrizeAmt'])) {
            throw new IOException("firstPrizeAmt is empty.");
        }

        if (!isset($this->prizeAmount['secondPrizeAmt'])) {
            throw new IOException("secondPrizeAmt is empty.");
        }

        if (!isset($this->prizeAmount['thirdPrizeAmt'])) {
            throw new IOException("thirdPrizeAmt is empty.");
        }

        if (!isset($this->prizeAmount['fourthPrizeAmt'])) {
            throw new IOException("fourthPrizeAmt is empty.");
        }

        if (!isset($this->prizeAmount['fifthPrizeAmt'])) {
            throw new IOException("fifthPrizeAmt is empty.");
        }

        if (!isset($this->prizeAmount['sixthPrizeAmt'])) {
            throw new IOException("sixthPrizeAmt is empty.");
        }
    }

    private function superPrizeNo($number, $invoiceNumber) {
        if ($number == $invoiceNumber) {
            return $this->prizeAmount['superPrizeAmt'];
        }

        return 0;
    }

    private function spcPrizeNo($number, $invoiceNumber) {
        if ($number == $invoiceNumber) {
            return $this->prizeAmount['spcPrizeAmt'];
        }

        return 0;
    }

    private function firstPrizeNo($number, $invoiceNumber) {
        $num = (string)abs($number - $invoiceNumber);

        if ($num == 0) {
            return $this->prizeAmount['firstPrizeAmt'];
        }

        $flag = 0;
        for ($i = strlen($num) - 1; $i >= 0; $i--) {
            if ($num[$i] == '0') {
                $flag++;
            } else {
                break;
            }
        }

        switch ($flag) {
            case 7:
                return $this->prizeAmount['secondPrizeAmt'];
            case 6:
                return $this->prizeAmount['thirdPrizeAmt'];
            case 5:
                return $this->prizeAmount['fourthPrizeAmt'];
            case 4:
                return $this->prizeAmount['fifthPrizeAmt'];
            case 3:
                return $this->prizeAmount['sixthPrizeAmt'];
        }

        return 0;
    }

    private function sixthPrizeNo($number, $invoiceNumber) {
        $num = abs($number - $invoiceNumber)%1000;

        if ($num == 0) {
            return $this->prizeAmount['sixthPrizeAmt'];
        }

        return 0;
    }
}
