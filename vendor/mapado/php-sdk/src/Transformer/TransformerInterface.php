<?php

namespace Mapado\Sdk\Transformer;

interface TransformerInterface
{
    /**
     * transform an api item result to an instance of an object
     *
     * @param array $item
     * @access public
     * @return mixed
     */
    public function transformItem(array $item);

    /**
     * transform an api list result to a list of instances
     *
     * @param array $list
     * @access public
     * @return array
     */
    public function transformList(array $list);
}
