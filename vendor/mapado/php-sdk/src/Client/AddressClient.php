<?php

namespace Mapado\Sdk\Client;

class AddressClient extends AbstractClient
{
    const ADDRESS_URL = '/addresses';

    /**
     * find addresses by parameters
     *
     * @param array $parameters
     * @access public
     * @return array
     */
    public function findBy(array $parameters = [])
    {
        return $this->transformer->transformList($this->apiGet('', $parameters)->json());
    }

    /**
     * find one address by uuid
     *
     * @param string $uuid
     * @access public
     * @return array
     */
    public function findOne($uuid)
    {
        return $this->transformer->transformItem($this->apiGet('/' . $uuid)->json());
    }

    /**
     * @{inheritedDoc}
     */
    protected function getRootUrl()
    {
        return self::ADDRESS_URL;
    }
}
