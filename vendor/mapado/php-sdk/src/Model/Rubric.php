<?php

namespace Mapado\Sdk\Model;

class Rubric
{
    /**
     * uuid
     *
     * @var string
     * @access private
     */
    private $uuid;

    /**
     * name
     *
     * @var string
     * @access private
     */
    private $name;

    /**
     * parentList
     *
     * @var array
     * @access private
     */
    private $parentList = [];

    /**
     * childrenList
     *
     * @var array
     * @access private
     */
    private $childrenList = [];

    /**
     * Gets the value of uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Sets the value of uuid
     *
     * @param string $uuid uuid
     *
     * @return Rubric
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Gets the value of name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name
     *
     * @param string $name name
     *
     * @return Rubric
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the value of parentList
     *
     * @return array
     */
    public function getParentList()
    {
        return $this->parentList;
    }

    /**
     * Sets the value of parentList
     *
     * @param array $parentList parent list
     *
     * @return Rubric
     */
    public function setParentList($parentList)
    {
        $this->parentList = $parentList;
        return $this;
    }

    /**
     * Gets the value of childrenList
     *
     * @return array
     */
    public function getChildrenList()
    {
        return $this->childrenList;
    }

    /**
     * Sets the value of childrenList
     *
     * @param array $childrenList description
     *
     * @return Rubric
     */
    public function setChildrenList($childrenList)
    {
        $this->childrenList = $childrenList;
        return $this;
    }
}
