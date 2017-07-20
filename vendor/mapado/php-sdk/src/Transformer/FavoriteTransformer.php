<?php

namespace Mapado\Sdk\Transformer;

use Mapado\Sdk\Model\Favorite;

/**
 * Class FavoriteTransformer
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class FavoriteTransformer extends AbstractTransformer
{
    /**
     * activityTransformer
     *
     * @var ActivityTransformer
     * @access private
     */
    private $activityTransformer;

    /**
     * __construct
     *
     * @param ActivityTransformer $activityTransformer
     * @access public
     */
    public function __construct(ActivityTransformer $activityTransformer)
    {
        $this->activityTransformer = $activityTransformer;
    }

    /**
     * {@inheritedDoc}
     */
    public function transformItem(array $item)
    {
        $favorite = new Favorite();
        $favorite
            ->setUuid($this->getFromArray($item, 'uuid'))
            ->setAction($this->getFromArray($item, 'action'))
        ;

        if (isset($item['_embedded']['activity'])) {
            $favorite->setActivity($this->activityTransformer->transformItem($item['_embedded']['activity']));
        }

        return $favorite;
    }
}
