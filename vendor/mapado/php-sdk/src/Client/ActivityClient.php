<?php

namespace Mapado\Sdk\Client;

class ActivityClient extends AbstractClient
{
    const ACTIVITY_URL = '/activities';

    /**
     * find activities by parameters
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
     * find one activity by uuid
     *
     * @param string $uuid
     * @param array $parameters
     * @access public
     * @return array
     */
    public function findOne($uuid, array $parameters = [])
    {
        $list = $this->apiGet('/' . $uuid, $parameters)->json();
        return $this->transformer->transformItem($list);
    }

    /**
     * program
     *
     * @param string $uuid
     * @access public
     * @return array
     */
    public function program($uuid, array $parameters = [])
    {
        $list = $this->apiGet('/' . $uuid . '/program', $parameters)->json();
        return $this->transformer->transformList($list);
    }

    /**
     * @{inheritedDoc}
     */
    protected function getRootUrl()
    {
        return self::ACTIVITY_URL;
    }
}
