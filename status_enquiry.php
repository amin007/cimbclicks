<?php
require_once './CimbClicks.class.php';
$cimbClicks = new CimbClicks('TBPRSBEPAY');
echo $cimbClicks->getStatusEnquiry(array(
    'payeeId' => $cimbClicks->getPayeeId(),
    'billAccountNo' => '12092909364173',
    'billReferenceNo' => 'TAN GEK HONG',
    'billReferenceNo2' => '760202-10-5196',
    'billReferenceNo3' => '',
    'billReferenceNo4' => '',
    'amount' => '710.00',
));