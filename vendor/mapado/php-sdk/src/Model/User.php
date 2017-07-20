<?php

namespace Mapado\Sdk\Model;

class User
{
    /**
     * uuid
     *
     * @var string
     * @access private
     */
    private $uuid;

    /**
     * fullname
     *
     * @var string
     * @access private
     */
    private $fullname;

    /**
     * avatar
     *
     * @var string
     * @access private
     */
    private $avatar;

    /**
     * Gets the value of $uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Sets the value of uuid
     *
     * @param string $uuid uuid
     *
     * @return User
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Gets the value of fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Sets the value of fullname
     *
     * @param string $fullname fullname
     *
     * @return User
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
        return $this;
    }

    /**
     * Gets the value of avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Sets the value of avatar
     *
     * @param string $avatar avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }
}
