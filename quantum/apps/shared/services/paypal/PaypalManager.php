<?php
/**
 * Mailer
*/


class PaypalCredentialsManager
{
    const SANDBOX_CLIENT_ID = "AczSDTuAdGZmARXx92Lp74Yn9zs11eT2XZNHOrDGnRKlz3jiOAi8fPtArCOawO2mRA2naEM_jOVpIE-H";
    const SANDBOX_CLIENT_SECRET = "EPIjxBeuxPMtnWLiQ-ab8OkTzA-t0KxMKPQTpO1iajqhYL3M3EbNcSE4c09btFfIsj6xTJoluniMVqcw";

    const PRODUCTION_CLIENT_ID = "AXG5pIk2rqW3VSmlFDpIZTy0e6KeZD9ACMJAIzqwLcvHZUwUq74Nfi2uDpzkJX4gNb0PNaWLDX7a1Wo2-H";
    const PRODUCTION_CLIENT_SECRET = "EGa3tEBTFAaBVk6CK61ZjwtznHmfcGW7vMHuFbjr_hTxaqWklgoeA6ptYSOj1KwC9oj3rbx-paeGbCR5";


    public function __construct($isProduction = true)
    {
        $this->isProduction = $isProduction;
    }

    public function getClientId()
    {
        if ($this->isProduction)
            return self::PRODUCTION_CLIENT_ID;
        else
            return self::SANDBOX_CLIENT_ID;
    }

    public function getClientSecret()
    {
        if ($this->isProduction)
            return self::PRODUCTION_CLIENT_SECRET;
        else
            return self::SANDBOX_CLIENT_SECRET;
    }
}


class PaypalManager
{



  	public function __construct()
    {

        //$this->isProduction = QM::config()->isProductionEnvironment();

        $this->isProduction = true;

        $credentials = new PaypalCredentialsManager($this->isProduction);

        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $credentials->getClientId(),     // ClientID
                $credentials->getClientSecret()     // ClientSecret
            )
        );
    }



    public function createPaymentCall()
    {

        // After Step 2
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal('1.00');
        $amount->setCurrency('USD');

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl("https://example.com/your_redirect_url.html")
            ->setCancelUrl("https://example.com/your_cancel_url.html");

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);



        //Make a Create Call
        // After Step 3
        try {
            $payment->create($this->apiContext);

            dd($payment);
            echo $payment;

            echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n";
        }catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode(); // Prints the Error Code
            echo $ex->getData(); // Prints the detailed error message
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }

}

?>