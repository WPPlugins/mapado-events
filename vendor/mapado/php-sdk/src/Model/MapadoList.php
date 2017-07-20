<?php

namespace Mapado\Sdk\Model;

/**
 * Class MapadoList
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class MapadoList implements \IteratorAggregate
{
    /**
     * data
     *
     * @var \Traversable
     * @access private
     */
    private $data;

    /**
     * limit
     *
     * @var int
     * @access private
     */
    private $limit;

    /**
     * totalHits
     *
     * @var int
     * @access private
     */
    private $totalHits;

    /**
     * page
     *
     * @var int
     * @access private
     */
    private $page;

    /**
     * pages
     *
     * @var int
     * @access private
     */
    private $pages;

    /**
     * links
     *
     * @var array
     * @access private
     */
    private $links;

    /**
     * __construct
     *
     * @param \Traversable $data
     * @access public
     */
    public function __construct(\Traversable $data = null)
    {
        $this->data = $data;
    }

    /**
     * getIterator
     *
     * @access public
     * @return Iterator
     */
    public function getIterator()
    {
        return $this->data;
    }

    /**
     * Getter for limit
     *
     * return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Setter for limit
     *
     * @param int $limit
     * @return MapadoList
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Getter for totalHits
     *
     * return int
     */
    public function getTotalHits()
    {
        return $this->totalHits;
    }

    /**
     * Setter for totalHits
     *
     * @param int $totalHits
     * @return MapadoList
     */
    public function setTotalHits($totalHits)
    {
        $this->totalHits = $totalHits;
        return $this;
    }

    /**
     * Getter for page
     *
     * return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Setter for page
     *
     * @param int $page
     * @return MapadoList
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * Getter for pages
     *
     * return int
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Setter for pages
     *
     * @param int $pages
     * @return MapadoList
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
        return $this;
    }

    /**
     * Getter for links
     *
     * return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Setter for links
     *
     * @param array $links
     * @return MapadoList
     */
    public function setLinks(array $links = null)
    {
        $this->links = $links;
        return $this;
    }
}
