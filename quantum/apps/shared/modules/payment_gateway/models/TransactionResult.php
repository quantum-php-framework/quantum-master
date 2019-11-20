<?

namespace PaymentGateway\Entities;

/**
 * Class TransactionResult
 * @package PaymentGateway\Entities
 */
class TransactionResult
{
    /**
     * @var
     */
    private $success;

    /**
     * @var
     */
    public $transaction_id;
    /**
     * @var
     */
    public $approval_code;
    /**
     * @var
     */
    public $message;

    /**
     * @var array
     */
    public $errors;

    /**
     * @var
     */
    public $masked_card_number;

    /**
     * @var
     */
    public $status_message;

    /**
     * @var
     */
    public $approval_message;

    /**
     * @var
     */
    public $avs_response;

    /**
     * @var
     */
    public $csc_response;

    /**
     * @var
     */
    public $external_transaction_id;

    /**
     * @var
     */
    public $response_code;

    /**
     * TransactionResult constructor.
     * @param $success
     * @param $response_code
     * @param $message
     * @param array $errors
     */
    private function __construct($success, $response_code, $message, $errors = [])
    {
        $this->response_code = $response_code;
        $this->message = $message;
        $this->success = $success;
        $this->errors = $errors;
    }

    /**
     * @param $code
     * @param $message
     * @return TransactionResult
     */
    public static function ok($code, $message)
    {
        $r = new TransactionResult(true, $code, $message);

        return $r;
    }

    /**
     * @param $code
     * @param $message
     * @param $errors
     * @return TransactionResult
     */
    public static function fail($code, $message, $errors = [])
    {
        $r = new TransactionResult(false, $code, $message, $errors);

        return $r;
    }

    /**
     * @return bool
     */
    public function wasOk()
    {
        return $this->success === true;
    }

    /**
     * @return bool
     */
    public function failed()
    {
        return $this->success === false;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * @param mixed $transaction_id
     */
    public function setTransactionId($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }

    /**
     * @return mixed
     */
    public function getApprovalCode()
    {
        return $this->approval_code;
    }

    /**
     * @param mixed $approval_code
     */
    public function setApprovalCode($approval_code)
    {
        $this->approval_code = $approval_code;
    }

    /**
     * @return mixed
     */
    public function getMaskedCardNumber()
    {
        return $this->masked_card_number;
    }

    /**
     * @param mixed $masked_card_number
     */
    public function setMaskedCardNumber($masked_card_number)
    {
        $this->masked_card_number = $masked_card_number;
    }

    /**
     * @return mixed
     */
    public function getStatusMessage()
    {
        return $this->status_message;
    }

    /**
     * @param mixed $status_message
     */
    public function setStatusMessage($status_message)
    {
        $this->status_message = $status_message;
    }

    /**
     * @return mixed
     */
    public function getApprovalMessage()
    {
        return $this->approval_message;
    }

    /**
     * @param mixed $approval_message
     */
    public function setApprovalMessage($approval_message)
    {
        $this->approval_message = $approval_message;
    }

    /**
     * @return mixed
     */
    public function getAvsResponse()
    {
        return $this->avs_response;
    }

    /**
     * @param mixed $avs_response
     */
    public function setAvsResponse($avs_response)
    {
        $this->avs_response = $avs_response;
    }

    /**
     * @return mixed
     */
    public function getCscResponse()
    {
        return $this->csc_response;
    }

    /**
     * @param mixed $csc_response
     */
    public function setCscResponse($csc_response)
    {
        $this->csc_response = $csc_response;
    }

    /**
     * @return mixed
     */
    public function getExternalTransactionId()
    {
        return $this->external_transaction_id;
    }

    /**
     * @param mixed $external_transaction_id
     */
    public function setExternalTransactionId($external_transaction_id)
    {
        $this->external_transaction_id = $external_transaction_id;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param mixed $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @param mixed $response_code
     */
    public function setResponseCode($response_code)
    {
        $this->response_code = $response_code;
    }

    /**
     * @return $response_code
     */
    public function getResponseCode()
    {
        return $this->response_code;
    }

}