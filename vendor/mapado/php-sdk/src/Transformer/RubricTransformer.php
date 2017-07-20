<?php

namespace Mapado\Sdk\Transformer;

use DateTime;
use Mapado\Sdk\Model\Rubric;

/**
 * RubricTransformer
 *
 * @uses AbstractTransformer
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class RubricTransformer extends AbstractTransformer
{
    /**
     * {@inheritedDoc}
     */
    public function transformItem(array $item)
    {
        $rubric = new Rubric();

        $rubric->setUuid($this->getFromArray($item, 'uuid'))
            ->setName($this->getFromArray($item, 'name'));
        if (!empty($item['parent_list'])) {
            $rubric->setParentList($this->transformList($item['parent_list'], null));
        }
        if (!empty($item['children_list'])) {
            $rubric->setChildrenList($this->transformList($item['children_list'], null));
        }

        return $rubric;
    }
}
