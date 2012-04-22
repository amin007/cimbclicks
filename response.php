<?php
require_once './CimbClicks.class.php';
$cimbClicks = new CimbClicks('PAYEE_ID');
$response = $cimbClicks->getPaymentResponse();
var_dump($response);  // Use this to debug