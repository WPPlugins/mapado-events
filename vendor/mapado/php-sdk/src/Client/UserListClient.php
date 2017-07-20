<?php

namespace Mapado\Sdk\Client;

/**
 * Class UserListClient
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class UserListClient extends AbstractClient
{
    const USER_LIST_URL = '/users';

    /**
     * findByUserUuid
     *
     * @param string $userUuid
     * @access public
     * @return array
     */
    public function findByUserUuid($userUuid, array $parameters = [])
    {
        $url = '/' . $userUuid . '/user-lists';
        $activityList = $this->apiGet($url, $parameters)->json();

        return $this->transformer->transformList($activityList);
    }

    /**
     * @{inheritedDoc}
     */
    protected function getRootUrl()
    {
        return self::USER_LIST_URL;
    }
}
