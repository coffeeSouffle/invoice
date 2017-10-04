<?php

use PHPUnit\Framework\TestCase;
use Invoice\PrizeNo;
use Invoice\Exceptions\IOException;

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

        $number = '9';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals(0, $prizeNo->getWinningPrizeAmount($number));
    }

    public function testConstructError() {
        
        $this->expectException(Invoice\Exceptions\IOException::class);
        $codeErrorInvoice = array(
            'code' => 400,
        );
        $prizeNo = new Invoice\PrizeNo($codeErrorInvoice);
    }

    public function testConstructDataFormatError() {
        $this->expectException(Invoice\Exceptions\IOException::class);
        $prizeNo = new Invoice\PrizeNo(array());
    }

    public function testGetPrizeNos() {
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $result = array(
            'superPrizeNo' => array(
                '33612092',
            ),
            'spcPrizeNo' => array(
                '06840705',
            ),
            'firstPrizeNo' => array(
                '12182003',
                '48794532',
                '77127885',
            ),
            'sixthPrizeNo' => array(
                '136',
                '873',
                '474',
            ),
        );
        $this->assertEquals($result, $prizeNo->getPrizeNos());
    }

    public function testIsWinning() {
        $number = '99999474';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertTrue($prizeNo->isWinning($number));

        $number = '';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertFalse($prizeNo->isWinning($number));

        $number = '99999475';
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertFalse($prizeNo->isWinning($number));
    }

    public function testGetInvoiceStartDate() {
        $date = new DateTime('2017-06-30 16:00:00', new DateTimeZone('UTC'));
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals($date, $prizeNo->getInvoiceStartDate());
    }

    public function testGetInvoiceEndDate() {
        $date = new DateTime('2017-08-30 16:00:00', new DateTimeZone('UTC'));
        $prizeNo = new Invoice\PrizeNo($this->invoice);
        $this->assertEquals($date, $prizeNo->getInvoiceEndDate());
    }

    public function testHandleAmountIsNotNumber() {
        try {
            $invoice = $this->invoice;
            $invoice['sixthPrizeAmt'] = 'string';
            $prizeNo = new Invoice\PrizeNo($invoice);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('sixthPrizeAmt is error. string', $e->getMessage());
        }
    }

    public function testHandleAmountIsZero() {
        try {
            $invoice = $this->invoice;
            $invoice['sixthPrizeAmt'] = '00000000';
            $prizeNo = new Invoice\PrizeNo($invoice);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('sixthPrizeAmt is error. 00000000', $e->getMessage());
        }
    }

    public function testHandleSuperPrizeNoIsError() {
        try {
            $invoice = $this->invoice;
            $invoice['superPrizeNo'] = '336120921';
            $prizeNo = new Invoice\PrizeNo($invoice);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('superPrizeNo is error. 336120921', $e->getMessage());
        }
    }

    public function testHandleSpecialPrizeNoIsError() {
        try {
            $invoice = $this->invoice;
            $invoice['spcPrizeNo'] = '068407051';
            $prizeNo = new Invoice\PrizeNo($invoice);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('spcPrizeNo is error. 068407051', $e->getMessage());
        }
    }

    public function testHandleFirstPrizeNoIsError() {
        try {
            $invoice = $this->invoice;
            $invoice['firstPrizeNo3'] = '771278851';
            $prizeNo = new Invoice\PrizeNo($invoice);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('firstPrizeNo3 is error. 771278851', $e->getMessage());
        }
    }

    public function testHandleSixthPrizeNoIsError() {
        try {
            $invoice = $this->invoice;
            $invoice['sixthPrizeNo3'] = '4741';
            $prizeNo = new Invoice\PrizeNo($invoice);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('sixthPrizeNo3 is error. 4741', $e->getMessage());
        }
    }

    public function testHandlePrizeAmountNotSet() {
        try {
            $tmp = $this->invoice;
            unset($tmp['superPrizeAmt']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('superPrizeAmt is empty.', $e->getMessage());
        }

        try {
            $tmp = $this->invoice;
            unset($tmp['spcPrizeAmt']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('spcPrizeAmt is empty.', $e->getMessage());
        }

        try {
            $tmp = $this->invoice;
            unset($tmp['firstPrizeAmt']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('firstPrizeAmt is empty.', $e->getMessage());
        }

        try {
            $tmp = $this->invoice;
            unset($tmp['secondPrizeAmt']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('secondPrizeAmt is empty.', $e->getMessage());
        }

        try {
            $tmp = $this->invoice;
            unset($tmp['thirdPrizeAmt']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('thirdPrizeAmt is empty.', $e->getMessage());
        }

        try {
            $tmp = $this->invoice;
            unset($tmp['fourthPrizeAmt']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('fourthPrizeAmt is empty.', $e->getMessage());
        }

        try {
            $tmp = $this->invoice;
            unset($tmp['fifthPrizeAmt']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('fifthPrizeAmt is empty.', $e->getMessage());
        }

        try {
            $tmp = $this->invoice;
            unset($tmp['sixthPrizeAmt']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('sixthPrizeAmt is empty.', $e->getMessage());
        }
    }

    public function testPrizeNosNotSet() {
        try {
            $tmp = $this->invoice;
            unset($tmp['superPrizeNo']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('superPrizeNo is empty.', $e->getMessage());
        }

        try {
            $tmp = $this->invoice;
            unset($tmp['spcPrizeNo']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('spcPrizeNo is empty.', $e->getMessage());
        }

        try {
            $tmp = $this->invoice;
            unset($tmp['firstPrizeNo1']);
            unset($tmp['firstPrizeNo2']);
            unset($tmp['firstPrizeNo3']);
            $prizeNo = new Invoice\PrizeNo($tmp);
        } catch (Invoice\Exceptions\IOException $e) {
            $this->assertEquals('firstPrizeNo is empty.', $e->getMessage());
        }
    }

    public function setDown() {
        unset($this->invoice);
    }
}