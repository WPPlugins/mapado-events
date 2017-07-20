<?php

namespace Mapado\Sdk\Transformer;

use Mapado\Sdk\Model\SuggestCode;
use Mapado\Sdk\Model\User;

/**
 * SuggestCodeTransformer
 *
 * @uses AbstractTransformer
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class SuggestCodeTransformer extends AbstractTransformer
{
    /**
     * {@inheritedDoc}
     */
    public function transformItem(array $item)
    {
        $suggestCode = new SuggestCode();

        if (!empty($item['javascript'])) {
            $suggestCode->setJavascript($item['javascript']);
        }

        if (!empty($item['style'])) {
            $suggestCode->setStyle($item['style']);
        }

        return $suggestCode;
    }
}
