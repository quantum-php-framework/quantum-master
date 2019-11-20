<?php
namespace FedEx\RateService\ComplexType;

use FedEx\AbstractComplexType;

/**
 * NaftaCommodityDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Rate Service
 *
 * @property \FedEx\RateService\SimpleType\NaftaPreferenceCriterionCode|string $PreferenceCriterion
 * @property \FedEx\RateService\SimpleType\NaftaProducerDeterminationCode|string $ProducerDetermination
 * @property string $ProducerId
 * @property \FedEx\RateService\SimpleType\NaftaNetCostMethodCode|string $NetCostMethod
 * @property DateRange $NetCostDateRange

 */
class NaftaCommodityDetail extends AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'NaftaCommodityDetail';

    /**
     * Defined by NAFTA regulations.
     *
     * @param \FedEx\RateService\SimpleType\NaftaPreferenceCriterionCode|string $preferenceCriterion
     * @return $this
     */
    public function setPreferenceCriterion($preferenceCriterion)
    {
        $this->values['PreferenceCriterion'] = $preferenceCriterion;
        return $this;
    }

    /**
     * Defined by NAFTA regulations.
     *
     * @param \FedEx\RateService\SimpleType\NaftaProducerDeterminationCode|string $producerDetermination
     * @return $this
     */
    public function setProducerDetermination($producerDetermination)
    {
        $this->values['ProducerDetermination'] = $producerDetermination;
        return $this;
    }

    /**
     * Identification of which producer is associated with this commodity (if multiple producers are used in a single shipment).
     *
     * @param string $producerId
     * @return $this
     */
    public function setProducerId($producerId)
    {
        $this->values['ProducerId'] = $producerId;
        return $this;
    }

    /**
     * Set NetCostMethod
     *
     * @param \FedEx\RateService\SimpleType\NaftaNetCostMethodCode|string $netCostMethod
     * @return $this
     */
    public function setNetCostMethod($netCostMethod)
    {
        $this->values['NetCostMethod'] = $netCostMethod;
        return $this;
    }

    /**
     * Date range over which RVC net cost was calculated.
     *
     * @param DateRange $netCostDateRange
     * @return $this
     */
    public function setNetCostDateRange(DateRange $netCostDateRange)
    {
        $this->values['NetCostDateRange'] = $netCostDateRange;
        return $this;
    }
}
