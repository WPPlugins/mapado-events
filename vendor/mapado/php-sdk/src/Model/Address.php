<?php

namespace Mapado\Sdk\Model;

class Address
{
    /**
     * uuid
     *
     * @var string
     * @access private
     */
    private $uuid;

    /**
     * formattedAddress
     *
     * @var string
     * @access private
     */
    private $formattedAddress;

    /**
     * latitude
     *
     * @var float
     * @access private
     */
    private $latitude;

    /**
     * longitude
     *
     * @var float
     * @access private
     */
    private $longitude;
    
    /**
     * city
     * 
     * var string
     * @access private
     */
    private $city;

    /**
     * Get uuid.
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set uuid.
     *
     * @param string uuid the value to set.
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Get formattedAddress.
     *
     * @return string
     */
    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    /**
     * Set formattedAddress.
     *
     * @param string formattedAddress the value to set.
     */
    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;
        return $this;
    }

    /**
     * Get latitude.
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set latitude.
     *
     * @param float latitude the value to set.
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * Get longitude.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set longitude.
     *
     * @param float longitude the value to set.
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }
   
    /**
     * Set city.
     *
     * @param string city the value to set.
     * return self
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    
    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
}
