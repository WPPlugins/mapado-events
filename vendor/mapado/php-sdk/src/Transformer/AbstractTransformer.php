<?php

namespace Mapado\Sdk\Transformer;

use Mapado\Sdk\Model\MapadoList;

abstract class AbstractTransformer implements TransformerInterface
{
    /**
     * {@inheritedDoc}
     */
    public function transformList(array $input, $key = 'data')
    {
        $datas = !empty($key) ? $input[$key] : $input;

        $return = [];
        foreach ($datas as $item) {
            $return[] = $this->transformItem($item);
        }

        $return = new MapadoList(new \ArrayIterator($return));
        $return->setLimit($this->getFromArray($input, 'limit'))
            ->setTotalHits($this->getFromArray($input, 'total_hits'))
            ->setPage($this->getFromArray($input, 'page'))
            ->setPages($this->getFromArray($input, 'pages'))
            ->setLinks($this->getFromArray($input, '_links'))
        ;

        return $return;
    }

    /**
     * getFromArray
     *
     * @param array $array
     * @param string $key
     * @access protected
     * @return mixed
     */
    protected function getFromArray($array, $key)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }

        return substr($key, -5) === '_list' ? [] : null;
    }
}
