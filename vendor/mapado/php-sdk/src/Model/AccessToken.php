<?php

namespace Mapado\Sdk\Model;

use DateTime;

class AccessToken
{
    private $accessToken;

    private $expiresAt;

    private $tokenType;

    private $scope;

    private $refreshToken;

    /**
     * Gets the value of accessToken
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Sets the value of accessToken
     *
     * @param string $accessToken access token
     *
     * @return AccessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Gets the value of expiresAt
     *
     * @return DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Sets the value of expiresAt
     *
     * @param DateTime $expiresAt expiration date
     *
     * @return AccessToken
     */
    public function setExpiresAt(DateTime $expiresAt)
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    /**
     * Gets the value of tokenType
     *
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * Sets the value of tokenType
     *
     * @param string $tokenType token type
     *
     * @return AccessToken
     */
    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;
        return $this;
    }

    /**
     * Gets the value of scope
     *
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Sets the value of scope
     *
     * @param mixed $scope scope
     *
     * @return AccessToken
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * Gets the value of refreshToken
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Sets the value of refreshToken
     *
     * @param string $refreshToken refreshToken
     *
     * @return AccessToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }
}
