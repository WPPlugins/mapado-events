<?php

namespace Mapado\Sdk\Transformer;

use Mapado\Sdk\Model\User;

/**
 * UserTransformer
 *
 * @uses AbstractTransformer
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class UserTransformer extends AbstractTransformer
{
    /**
     * {@inheritedDoc}
     */
    public function transformItem(array $item)
    {
        $return = new User();

        $return->setUuid($this->getFromArray($item, 'uuid'))
            ->setFullname($this->getFromArray($item, 'fullname'))
            ->setAvatar($this->getFromArray($item, 'avatar'));

        return $return;
    }
}
