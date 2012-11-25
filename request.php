<?php
require_once './CimbClicks.class.php';
$cimbClicks = new CimbClicks('TBPRSBEPAY');
$cimbClicks->setPaymentMode(CimbClicks::PAYMENT_MODE_DEV);
?>
<!doctype html>
<html>
<head>
    <title>CIMB Clicks example - Request</title>
</head>
<body>
    <form action="<?php echo $cimbClicks->getPaymentUrl(); ?>" method="get">
        <input type="text" name="payeeId" value="<?php echo $cimbClicks->getPayeeId(); ?>" />
        <input type="text" name="billAccountNo" value="ABC0000000001" />
        <input type="text" name="billReferenceNo" value="XYZ0000000001" />
        <input type="text" name="billReferenceNo2" value="" />
        <input type="text" name="billReferenceNo3" value="" />
        <input type="text" name="billReferenceNo4" value="" />
        <input type="text" name="amount" value="1.00" />
        <input type="submit" value="Pay with CIMB Clicks" />
    </form>
</body>
</html>