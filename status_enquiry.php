<?php
require_once './CimbClicks.class.php';
$cimbClicks = new CimbClicks('PAYEE_ID');
echo $cimbClicks->getStatusEnquiry(array(
    'payeeId' => $cimbClicks->getPayeeId(),
    'billAccountNo' => '<Bill Account Number>',
    'billReferenceNo' => '<Bill Reference Number>',
    'billReferenceNo2' => '<Bill Reference Number 2>',
    'billReferenceNo3' => '<Bill Reference Number 3>',
    'billReferenceNo4' => '<Bill Reference Number 4>',
    'amount' => '<Amount>',
));