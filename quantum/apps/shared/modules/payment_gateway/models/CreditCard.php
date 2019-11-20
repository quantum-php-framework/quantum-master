<?

namespace PaymentGateway\Entities;

/**
 * Class CreditCard
 * @package PaymentGateway\Entities
 */
class CreditCard
{
    /**
     * @var
     */
    public $number;
    /**
     * @var
     */
    public $expiration_month;
    /**
     * @var
     */
    public $expiration_year;
    /**
     * @var
     */
    public $security_code;



    /**
     * CreditCard constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getExpirationMonth()
    {
        return $this->expiration_month;
    }

    /**
     * @param mixed $expiration_month
     */
    public function setExpirationMonth($expiration_month)
    {
        $this->expiration_month = $expiration_month;
    }

    /**
     * @return mixed
     */
    public function getExpirationYear()
    {
        return $this->expiration_year;
    }

    /**
     * @param mixed $expiration_year
     */
    public function setExpirationYear($expiration_year)
    {
        $this->expiration_year = $expiration_year;
    }

    /**
     * @return mixed
     */
    public function getSecurityCode()
    {
        return $this->security_code;
    }

    /**
     * @param mixed $security_code
     */
    public function setSecurityCode($security_code)
    {
        $this->security_code = $security_code;
    }



}