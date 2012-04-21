<?php

/**
 * Wrapper class for integrating CIMB Clicks payment method to your application.
 *
 * @author Leow Kah Thong <http://kahthong.com>
 * @copyright Leow Kah Thong 2012
 * @version 1.0
 */
class CimbClicks {

    const PAYMENT_MODE_DEV       = 'dev';
    const PAYMENT_MODE_PROD      = 'prod';
    const PAYMENT_FIELD_REQUIRED = true;
    const PAYMENT_FIELD_OPTIONAL = false;

    private $paymentUrlDev  = 'https://203.153.83.64/TIBSEPWeb/ePayment.do';
    private $paymentUrlProd = 'https://www.cimbclicks.com.my/TIBSEPWeb/ePayment.do';
    private $payeeId        = '';
    private $paymentMode    = '';

    /**
     * @access public
     * @param string $payeeId     Payee ID to set.
     * @param string $paymentMode Payment mode to use.
     */
    public function __construct($payeeId, $paymentMode = self::PAYMENT_MODE_PROD) {
        $this->setPayeeId($payeeId);
        $this->setPaymentMode($paymentMode);
    }

    /**
     * Generate the complete URL to request e-payment.
     *
     * @access public
     * @param array $vars Array of variables to pass.
     * @see validatPaymentRequest()
     * @return string Full URL to payment request.
     */
    public function getPaymentRequestUrl($vars) {
        // Validate given variables
        $paymentDetails = $this->validatePaymentRequest($vars);
        $url            = $this->getPaymentUrl($this->getPaymentMode()) . '?' . http_build_query($paymentDetails);

        return $url;
    }

    /**
     * Validate the e-payment variables.
     *
     * @access public
     * @param array   $vars Array of variables to validate.
     * @param boolean $vars (Optional) Set to true to remove any invalid variables.
     * @return array Validated variables.
     */
    public function validatePaymentRequest($vars, $filterVars = false) {
        $acceptedVars = array(
          'payeeId'          => self::PAYMENT_FIELD_REQUIRED, // Payee code for merchant (string 20)
          'billAccountNo'    => self::PAYMENT_FIELD_REQUIRED, // Eg: ABC123 (string 30)
          'billReferenceNo'  => self::PAYMENT_FIELD_REQUIRED, // (string 20)
          'billReferenceNo2' => self::PAYMENT_FIELD_OPTIONAL, // (string 20)
          'billReferenceNo3' => self::PAYMENT_FIELD_OPTIONAL, // (string 20)
          'billReferenceNo4' => self::PAYMENT_FIELD_OPTIONAL, // (string 20)
          'amount'           => self::PAYMENT_FIELD_REQUIRED, // Payment amount, eg: "100.20" (float 13)
        );

        // Remove any invalid variables.
        if ($filterVars) {
            $notListedVars = array_values(array_diff(array_keys($vars), array_keys($acceptedVars)));
            foreach ($notListedVars as $notListedVar) {
                unset($vars[$notListedVar]);
            }
        }

        // Check for required variables. Throw error if not set.
        foreach ($acceptedVars as $key => $val) {
            if (
              (isset($vars[$key]) && ($val == self::PAYMENT_FIELD_REQUIRED) && !$vars[$key]) ||
              ($val == self::PAYMENT_FIELD_REQUIRED && !isset($vars[$key]))
            ) {
                trigger_error("Parameter <em>$key</em> is required for e-payment request.");
            }
        }

        return $vars;
    }

    /**
     * Set the Payee ID.
     *
     * @access public
     * @param string $payeeId Payee ID.
     */
    public function setPayeeId($payeeId) {
        $this->payeeId = $payeeId;
    }

    /**
     * Get Payee ID.
     *
     * @access public
     * @return string Payee ID.
     */
    public function getPayeeId() {
        return $this->payeeId;
    }

    /**
     * Set payment mode.
     *
     * @access public
     * @param string $paymentMode Payment mode to set. Accepted values are; PAYMENT_MODE_DEV or PAYMENT_MODE_PROD.
     */
    public function setPaymentMode($paymentMode) {
        $this->paymentMode = $paymentMode;
    }

    /**
     * Get the payment mode.
     *
     * @access public
     * @return string Payment mode.
     */
    public function getPaymentMode() {
        return $this->paymentMode;
    }

    /**
     * Returns the URL to use for requesting e-payment.
     *
     * @access public
     * @return string CIMB Clicks e-payment URL.
     */
    public function getPaymentUrl() {
        $paymentMode = $this->getPaymentMode();

        if ($paymentMode == self::PAYMENT_MODE_DEV) {
            return $this->paymentUrlDev;
        } else {
            return $this->paymentUrlProd;
        }
    }

    /**
     * Get the response returned by CIMB Clicks (from $_GET superglobal).
     * This function is just here for reference. You would get from $_GET anyway with your own variables passed.
     *
     * @access public
     * @return array Response returned by CIMB Clicks server.
     */
    public function getPaymentResponse() {
        // Parameters returned are:
        // [Merchant RedirectURL]?[Parameters sent by Merchant]&paymentRefNo=xxxxxxxx&tranStatus=S&tranDesc=Successful
        $response = isset($_GET) ? $_GET : array();
        return $response;
    }

}
