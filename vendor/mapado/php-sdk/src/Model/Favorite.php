<?php

namespace Mapado\Sdk\Model;

/**
 * Class Favorite
 * @author Julien Deniau <julien.deniau@mapado.com>
 */
class Favorite
{
    /**
     * uuid
     *
     * @var string
     * @access private
     */
    private $uuid;

    /**
     * action
     *
     * @var string
     * @access private
     */
    private $action;

    /**
     * activity
     *
     * @var Activity
     * @access private
     */
    private $activity;

    /**
     * Getter for uuid
     *
     * return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Setter for uuid
     *
     * @param string $uuid
     * @return Favorite
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * Getter for action
     *
     * return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Setter for action
     *
     * @param string $action
     * @return Favorite
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Getter for activity
     *
     * return Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Setter for activity
     *
     * @param Activity $activity
     * @return Favorite
     */
    public function setActivity(Activity $activity)
    {
        $this->activity = $activity;
        return $this;
    }
}
