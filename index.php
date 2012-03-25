<?php

require_once './CimbClicks.class.php';
$cimbclicks = new CimbClicks();
$cimbclicks->setEPaymentMode(CimbClicks::EPAYMENT_MODE_DEV);

?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" version="XHTML+RDFa 1.0" dir="ltr">

<head profile="http://www.w3.org/1999/xhtml/vocab">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
  <form action="<?php echo $cimbclicks->getEPaymentUrl(); ?>" method="post">
    <input type="text" name="payeeId" value="PAYEEID" />
    <input type="text" name="billAccountNo" value="ABC0000000001" />
    <input type="text" name="billReferenceNo" value="XYZ0000000001" />
    <input type="text" name="billReferenceNo2" value="" />
    <input type="text" name="billReferenceNo3" value="" />
    <input type="text" name="billReferenceNo4" value="" />
    <input type="text" name="amount" value="1.00" />
    <input type="submit" value="Submit" />
  </form>
</body>

</html>