<?php

namespace Mapado\Sdk\Client;

/**
 * Class FavoriteClient
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class FavoriteClient extends AbstractClient
{
    const FAVORITE_URL = '/users';

    /**
     * findByUserUuid
     *
     * @param string $userUuid
     * @access public
     * @return array
     */
    public function findByUserUuid($userUuid, array $parameters = [])
    {
        $url = '/' . $userUuid . '/favorites';
        $activityList = $this->apiGet($url, $parameters)->json();

        return $this->transformer->transformList($activityList);
    }

    /**
     * @{inheritedDoc}
     */
    protected function getRootUrl()
    {
        return self::FAVORITE_URL;
    }
}
