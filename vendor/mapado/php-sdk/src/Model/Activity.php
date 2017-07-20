<?php

namespace Mapado\Sdk\Model;

use DateTime;

class Activity
{
    /**
     * uuid
     *
     * @var string
     * @access private
     */
    private $uuid;

    /**
     * title
     *
     * @var string
     * @access private
     */
    private $title;

    /**
     * description
     *
     * @var string
     * @access private
     */
    private $description;

    /**
     * shortDescription
     *
     * @var string
     * @access private
     */
    private $shortDescription;

    /**
     * simplePrice
     *
     * @var float
     * @access private
     */
    private $simplePrice;

    /**
     * urlList
     *
     * @var array
     * @access private
     */
    private $urlList;

    /**
     * emailList
     *
     * @var array
     * @access private
     */
    private $emailList;

    /**
     * phoneList
     *
     * @var array
     * @access private
     */
    private $phoneList;

    /**
     * formattedSchedule
     *
     * @var string
     * @access private
     */
    private $formattedSchedule;

    /**
     * firstDate
     *
     * @var DateTime
     * @access private
     */
    private $firstDate;

    /**
     * lastDate
     *
     * @var DateTime
     * @access private
     */
    private $lastDate;

    /**
     * canceled
     *
     * @var bool
     * @access private
     */
    private $canceled;

    /**
     * soldOut
     *
     * @var bool
     * @access private
     */
    private $soldOut;

    /**
     * shortDate
     *
     * @var mixed
     * @access private
     */
    private $shortDate;

    /**
     * imageList
     *
     * @var array
     * @access private
     */
    private $imageList;

    /**
     * address
     *
     * @var Address
     * @access private
     */
    private $address;

    /**
     * rubric
     *
     * @var Rubric
     * @access private
     */
    private $rubric;

    /**
     * frontPlaceName
     *
     * @var string
     * @access private
     */
    private $frontPlaceName;

    /**
     * priceList
     *
     * @var array
     * @access private
     */
    private $priceList;


    /**
     * imageUrlList
     *
     * @var array
     * @access private
     */
    private $imageUrlList;

    /**
     * links
     *
     * @var array
     * @access private
     */
    private $links;

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
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string title the value to set.
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string description the value to set.
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Getter for shortDescription
     *
     * return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Setter for shortDescription
     *
     * @param string $shortDescription
     * @return Activity
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    /**
     * Get simplePrice.
     *
     * @return float
     */
    public function getSimplePrice()
    {
        return $this->simplePrice;
    }

    /**
     * Set simplePrice.
     *
     * @param float simplePrice the value to set.
     */
    public function setSimplePrice($simplePrice)
    {
        $this->simplePrice = $simplePrice;
        return $this;
    }

    /**
     * Get urlList.
     *
     * @return array
     */
    public function getUrlList()
    {
        return $this->urlList;
    }

    /**
     * Set urlList.
     *
     * @param array urlList the value to set.
     */
    public function setUrlList(array $urlList)
    {
        $this->urlList = $urlList;
        return $this;
    }

    /**
     * Get emailList.
     *
     * @return array
     */
    public function getEmailList()
    {
        return $this->emailList;
    }

    /**
     * Set emailList.
     *
     * @param array emailList the value to set.
     */
    public function setEmailList(array $emailList)
    {
        $this->emailList = $emailList;
        return $this;
    }

    /**
     * Get phoneList.
     *
     * @return array
     */
    public function getPhoneList()
    {
        return $this->phoneList;
    }

    /**
     * Set phoneList.
     *
     * @param array phoneList the value to set.
     */
    public function setPhoneList(array $phoneList)
    {
        $this->phoneList = $phoneList;
        return $this;
    }

    /**
     * Get formattedSchedule.
     *
     * @return string
     */
    public function getFormattedSchedule()
    {
        return $this->formattedSchedule;
    }

    /**
     * Set formattedSchedule.
     *
     * @param string formattedSchedule the value to set.
     */
    public function setFormattedSchedule($formattedSchedule)
    {
        $this->formattedSchedule = $formattedSchedule;
        return $this;
    }

    /**
     * Get firstDate.
     *
     * @return DateTime
     */
    public function getFirstDate()
    {
        return $this->firstDate;
    }

    /**
     * Set firstDate.
     *
     * @param DateTime firstDate the value to set.
     */
    public function setFirstDate(DateTime $firstDate)
    {
        $this->firstDate = $firstDate;
        return $this;
    }

    /**
     * Get lastDate.
     *
     * @return DateTime
     */
    public function getLastDate()
    {
        return $this->lastDate;
    }

    /**
     * Set lastDate.
     *
     * @param DateTime lastDate the value to set.
     */
    public function setLastDate(DateTime $lastDate)
    {
        $this->lastDate = $lastDate;
        return $this;
    }

    /**
     * Get canceled.
     *
     * @return bool
     */
    public function getCanceled()
    {
        return $this->canceled;
    }

    /**
     * Set canceled.
     *
     * @param bool canceled the value to set.
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;
        return $this;
    }

    /**
     * Get soldOut.
     *
     * @return bool
     */
    public function getSoldOut()
    {
        return $this->soldOut;
    }

    /**
     * Set soldOut.
     *
     * @param bool soldOut the value to set.
     */
    public function setSoldOut($soldOut)
    {
        $this->soldOut = $soldOut;
        return $this;
    }

    /**
     * Get shortDate.
     *
     * @return shortDate.
     */
    public function getShortDate()
    {
        return $this->shortDate;
    }

    /**
     * Set shortDate.
     *
     * @param mixed shortDate the value to set.
     */
    public function setShortDate($shortDate)
    {
        $this->shortDate = $shortDate;
        return $this;
    }

    /**
     * Get imageList.
     *
     * @return array
     */
    public function getImageList()
    {
        return $this->imageList;
    }

    /**
     * Set imageList.
     *
     * @param array imageList the value to set.
     */
    public function setImageList(array $imageList)
    {
        $this->imageList = $imageList;
        return $this;
    }

    /**
     * Get address.
     *
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address.
     *
     * @param Address address the value to set.
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Getter for rubric
     *
     * return Rubric
     */
    public function getRubric()
    {
        return $this->rubric;
    }

    /**
     * Setter for rubric
     *
     * @param Rubric $rubric
     * @return Activity
     */
    public function setRubric(Rubric $rubric)
    {
        $this->rubric = $rubric;
        return $this;
    }


    /**
     * Getter for frontPlaceName
     *
     * return string
     */
    public function getFrontPlaceName()
    {
        return $this->frontPlaceName;
    }

    /**
     * Setter for frontPlaceName
     *
     * @param string $frontPlaceName
     * @return Activity
     */
    public function setFrontPlaceName($frontPlaceName)
    {
        $this->frontPlaceName = $frontPlaceName;
        return $this;
    }

    /**
     * Getter for imageUrlList
     *
     * return array
     */
    public function getImageUrlList()
    {
        return $this->imageUrlList;
    }

    /**
     * Setter for imageUrlList
     *
     * @param array $imageUrlList
     * @return Activity
     */
    public function setImageUrlList(array $imageUrlList)
    {
        $this->imageUrlList = $imageUrlList;
        return $this;
    }

    /**
     * Getter for links
     *
     * return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Setter for links
     *
     * @param array $links
     * @return Activity
     */
    public function setLinks(array $links)
    {
        $this->links = $links;
        return $this;
    }

    /**
     * Getter for priceList
     *
     * return array
     */
    public function getPriceList()
    {
        return $this->priceList;
    }

    /**
     * Setter for priceList
     *
     * @param array $priceList
     * @return Activity
     */
    public function setPriceList(array $priceList)
    {
        $this->priceList = $priceList;
        return $this;
    }
}
