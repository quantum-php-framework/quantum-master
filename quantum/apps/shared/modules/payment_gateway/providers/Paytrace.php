<?php


namespace PaymentGateway\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use PaymentGateway\Entities\Address;
use PaymentGateway\Entities\CreditCard;
use PaymentGateway\Entities\Payment;
use PaymentGateway\Entities\TransactionResult;


/**
 * Class Paytrace
 * @package PaymentGateway\Providers
 */
class Paytrace
{
    /**
     * @var
     */
    private $username;

    /**
     * @var
     */
    private $password;

    /**
     * Paytrace constructor.
     * @param string $username
     * @param string $password
     */
    function __construct($username = "quantumgateway", $password = "Ba47b2e9cd")
    {
        $this->setUsername($username);
        $this->setPassword($password);
    }

    /**
     * @param CreditCard $card
     * @param Payment $payment
     * @param Address $billing_address
     * @param Address $shipping_address
     * @return TransactionResult
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authorizeCreditCardPayment(CreditCard $card, Payment $payment, Address $billing_address, Address $shipping_address)
    {
        $auth_server = 'https://api.paytrace.com';

        $client = new Client();

        try
        {
            $auth_response = $client->request('POST', $auth_server.'/oauth/token', [
                'body' => http_build_query([
                    'grant_type' => 'password',
                    'username' => $this->getUsername(),
                    'password' => $this->getPassword()
                ])
            ]);
        }
        catch  (ConnectException $e)
        {
            return TransactionResult::fail('666', 'Connection Error', [$e->getMessage()]);
        }


        $json = qs($auth_response->getBody())->decodeJson();

        $token = $json->access_token;

        $sale_data = [
            'amount' => $payment->getAmount(),
            'credit_card' => [
                'number' => $card->getNumber(),
                'expiration_month' => $card->getExpirationMonth(),
                'expiration_year' => $card->getExpirationYear()
            ],
            'csc' => $card->getSecurityCode(),
            'shipping_address' => [
                'name' => $shipping_address->getName(),
                'street_address' => $shipping_address->getStreetAddress(),
                'street_address2' => $shipping_address->getStreetAddress2(),
                'city' => $shipping_address->getCity(),
                'state' => $shipping_address->getState(),
                'zip' => $shipping_address->getZip(),
                'country' => $shipping_address->getCountry()
            ],
            'billing_address' => [
                'name' => $billing_address->getName(),
                'street_address' => $billing_address->getStreetAddress(),
                'street_address2' => $billing_address->getStreetAddress2(),
                'city' => $billing_address->getCity(),
                'state' => $billing_address->getState(),
                'zip' => $billing_address->getZip(),
                'country' => $billing_address->getCountry()
            ],
            'invoice_id' => $payment->getInvoiceId()
        ];

        try
        {
            $transaction_response = $client->request('POST', $auth_server.'/v1/transactions/authorization/keyed', [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => $sale_data
            ]);
        }
        catch (ClientException $e)
        {
            $transaction_response = $e->getResponse()->getBody()->getContents();
        }

        $transaction_response = qs($transaction_response);

        if (!$transaction_response->isJson())
            return TransactionResult::fail('666', 'Unknown error: 00xA000001', [$transaction_response->toStdString()]);

        $json = $transaction_response->decodeJson();

        if (isset($json->success) && $json->success === true)
        {
            $r = TransactionResult::ok($json->response_code, $json->status_message);
        }
        else
        {
            $r = TransactionResult::fail(001, "Unknown Error");
        }

        if (isset($json->response_code))
        {
            $r->setResponseCode($json->response_code);
        }

        if (isset($json->status_message))
        {
            $r->setMessage($json->status_message);
            $r->setStatusMessage($json->status_message);
        }

        if (isset($json->transaction_id))
        {
            $r->setTransactionId($json->transaction_id);
        }

        if (isset($json->masked_card_number))
        {
            $r->setMaskedCardNumber($json->masked_card_number);
        }

        if (isset($json->approval_code))
        {
            $r->setApprovalCode($json->approval_code);
        }

        if (isset($json->approval_message))
        {
            $r->setApprovalMessage($json->approval_message);
        }

        if (isset($json->avs_response))
        {
            $r->setAvsResponse($json->avs_response);
        }

        if (isset($json->csc_response))
        {
            $r->setCscResponse($json->csc_response);
        }

        if (isset($json->external_transaction_id))
        {
            $r->setExternalTransactionId($json->external_transaction_id);
        }

        if (isset($json->errors))
        {
            $parsed_errors = new_vt();

            foreach ($json->errors as $error)
            {
                $parsed_errors->add($error[0]);
            }

            if (!$parsed_errors->isEmpty())
            {
                $r->setMessage($json->status_message.": ". $parsed_errors->toCommaSeparatedString());
                $r->setErrors($parsed_errors->getArray());
            }
        }

        return $r;
    }


    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }



}