<?php

use PHPUnit\Framework\TestCase;
use Invoice\PrizeNo;

final class PrizeNoTest extends TestCase
{
    protected $invoice;

    protected function setUp()
    {
        $this->invoice = array(
            'code' => 200,
            'invoYm' => '10608',
            'superPrizeNo' => '33612092',
            'spcPrizeNo' => '06840705',
            'firstPrizeNo1' => '12182003',
            'firstPrizeNo2' => '48794532',
            'firstPrizeNo3' => '77127885',
            'firstPrizeNo4' => '',
            'sixthPrizeNo1' => '136',
            'sixthPrizeNo2' => '873',
            'sixthPrizeNo3' => '474',
            'superPrizeAmt' => '10000000',
            'spcPrizeAmt' => '02000000',
            'firstPrizeAmt' => '00200000',
            'secondPrizeAmt' => '00040000',
            'thirdPrizeAmt' => '00010000',
            'fourthPrizeAmt' => '00004000',
            'fifthPrizeAmt' => '00001000',
            'sixthPrizeAmt' => '00000200',
        );
    }

    public function testWinningSuperPrizeNo() {
        $number = '33612092';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(10000000, $prizeNo->getWinningPrizeAmount($number));
    }

    public function testWinningSpecialPrizeNo() {
        $number = '06840705';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(2000000, $prizeNo->getWinningPrizeAmount($number));
    }

    public function testWinningOtherPrizeNo() {
        $number = '12182003';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(200000, $prizeNo->getWinningPrizeAmount($number));

        $number = '92182003';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(40000, $prizeNo->getWinningPrizeAmount($number));

        $number = '00182003';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(10000, $prizeNo->getWinningPrizeAmount($number));

        $number = '00082003';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(4000, $prizeNo->getWinningPrizeAmount($number));

        $number = '00002003';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(1000, $prizeNo->getWinningPrizeAmount($number));

        $number = '00000003';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(200, $prizeNo->getWinningPrizeAmount($number));

        $number = '00000103';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(0, $prizeNo->getWinningPrizeAmount($number));

        $number = '99999474';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(200, $prizeNo->getWinningPrizeAmount($number));
    }

    public function setDown() {
        unset($this->invoice);
    }
}