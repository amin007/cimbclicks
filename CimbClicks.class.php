<?php

/**
 * Wrapper class for integrating CIMB Clicks payment method to your application.
 *
 * @author Leow Kah Thong <http://kahthong.com>
 */
class CimbClicks {

  // Class constants definition.
  const EPAYMENT_MODE_DEV = 'dev';
  const EPAYMENT_MODE_PROD = 'prod';
  const EPAYMENT_VARS_REQUIRED = TRUE;
  const EPAYMENT_VARS_OPTIONAL = FALSE;

  // Class global variables.
  private $cimb_epayment_url_dev = 'https://203.153.83.64/TIBSEPWeb/ePayment.do';
  private $cimb_epayment_url_prod = 'https://www.cimbclicks.com.my/TIBSEPWeb/ePayment.do';
  private $epayment_mode, $payee_id = '';

  /**
   * Create a new CIMB Clicks object.
   *
   * @param string $payee_id Payee ID to set.
   */
  function __construct($payee_id = '') {
    // By default, set to production mode.
    $this->setEPaymentMode(self::EPAYMENT_MODE_PROD);

    if ($payee_id) {
      $this->setPayeeId($payee_id);
    }
  }

  /**
   * Generate the complete URL to request e-payment.
   *
   * @param array $vars Array of variables to pass. Refer to validateEPaymentRequest() function to see list of variables.
   * @return string Full URL to payment request.
   */
  function generateEPaymentRequestUrl($vars) {
    // Validate given variables.
    $payment_details = $this->validateEPaymentRequest($vars);
    $url = $this->getEPaymentUrl($this->getEPaymentMode()) . '?' . http_build_query($payment_details);

    return $url;
  }

  /**
   * Validate the e-payment variables.
   *
   * @param array $vars Array of variables to validate.
   * @return array Validated variables.
   */
  public function validateEPaymentRequest($vars) {
    $accepted_vars = array(
      'payeeId' => self::EPAYMENT_VARS_REQUIRED, // Payee code for merchant (string 20)
      'billAccountNo' => self::EPAYMENT_VARS_REQUIRED, // Eg: ABC123 (string 30)
      'billReferenceNo' => self::EPAYMENT_VARS_REQUIRED, // (string 20)
      'billReferenceNo2' => self::EPAYMENT_VARS_OPTIONAL, // (string 20)
      'billReferenceNo3' => self::EPAYMENT_VARS_OPTIONAL, // (string 20)
      'billReferenceNo4' => self::EPAYMENT_VARS_OPTIONAL, // (string 20)
      'amount' => self::EPAYMENT_VARS_REQUIRED, // Payment amount, eg: "100.20" (float 13)
    );

//    // Remove any invalid variables.
//    $not_listed_vars = array_values(array_diff(array_keys($vars), array_keys($accepted_vars)));
//    foreach ($not_listed_vars as $not_listed_var) {
//      unset($vars[$not_listed_var]);
//    }

    // Check for required variables. Throw error if not set.
    foreach ($accepted_vars as $key => $val) {
      if (
        (isset($vars[$key]) && ($val == self::EPAYMENT_VARS_REQUIRED) && !$vars[$key]) ||
        ($val == self::EPAYMENT_VARS_REQUIRED && !isset($vars[$key]))
      ) {
        trigger_error("Parameter <em>$key</em> is required for e-payment request.", E_USER_NOTICE);
      }
    }

    return $vars;
  }

  /**
   * @todo
   */
  public function verifyTransaction() {
    /*
     * Verify transaction via Payment Reference No. (Web Service Query)
     * - Transaction date
     * - Payee Id
     * - Payment Reference No.
     * https://202.165.12.201/my.epayment.web.services/services/EPaymentTransactionSOAP
     * https://www.cimbclicks.com.my/my.epayment.web.services/services/EPaymentTransactionSOAP
     *
     * Paramters for Request (verifyTransViaPaymentRefNo):
     * - auditNo            - Unique trace number for transaction (int 30)
     * - transactionDate    - yyyyMMdd (string 8)
     * - paymentReferenceNo - CIMB payment reference number (int 16)
     * - payeeID            - Merchant Payee ID provided by CIMB (string 20)
     *
     * Paramters for Reply (verifyTransViaPaymentRefNoResponse):
     * - auditNo              - Unique trace number for transaction (int 30)
     * - transactionTimestamp - (Optional) yyyyMMddHHmmss (string 14)
     * - transactionStatus    - S - Success
     *                          F - Failed
     *                          U - Unknown, internal system error, treat as failed
     *                          D - Transaction not processed
     * - paymentReferenceNo   - CIMB payment reference number (int 16)
     * - payeeID              - Merchant Payee ID provided by CIMB (string 20)
     * - amount               - (Optional) Transaction amount is required, if Reference Number is not provided. (float 15)
     */

    return;
  }

  /**
   * Set the Payee ID.
   * @param string $payee_id Payee ID.
   */
  function setPayeeId($payee_id) {
    $this->payee_id = $payee_id;
  }

  /**
   * Get Payee ID.
   * @return string Payee ID.
   */
  function getPayeeId() {
    return $this->payee_id;
  }

  /**
   * Returns the URL to use for requesting e-payment.
   *
   * @param string $mode (Optional) E-payment mode. Defaults to production mode.
   * @return string CIMB Clicks e-payment URL.
   */
  function getEPaymentUrl($mode = '') {
    // If not set, attempt to get the already set mode.
    if (!$mode) {
      $mode = $this->getEPaymentMode();
    }

    switch ($mode) {
      case self::EPAYMENT_MODE_DEV:
        return $this->cimb_epayment_url_dev;
        break;
      default:
        return $this->cimb_epayment_url_prod;
    }
  }

  /**
   * Get the response returned by CIMB Clicks (from $_POST superglobal).
   * @return array Response returned by CIMB Clicks server.
   */
  public function getEPaymentResponse() {
    // Parameters returned are:
    // [Merchant RedirectURL]?[Parameters sent by Merchant]&paymentRefNo=xxxxxxxx&tranStatus=S&tranDesc=Successful
    $raw_response = isset($_POST) ? $_POST : array();

    // @todo: Re-verify transaction

    return $raw_response;
  }

  /**
   * Set payment mode.
   * @param string $mode (Optional) Payment mode to set. Defaults to production.
   */
  public function setEPaymentMode($mode = self::EPAYMENT_MODE_PROD) {
    $this->epayment_mode = $mode;
  }

  /**
   * Get the payment mode.
   * @return string Payment mode.
   */
  public function getEPaymentMode() {
    return $this->epayment_mode;
  }

}
