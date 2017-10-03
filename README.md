# invoice

- 發票兌獎小程式

## Example

```php
use Invoice\PrizeNo;

$invoiceNumber = array(
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

$prizeNo = new PrizeNo($invoiceNumber);

$prizeAmount = $prizeNo->getWinningPrizeAmount('33612093');
```