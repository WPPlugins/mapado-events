<?php

namespace Mapado\Sdk\Model;

class SuggestCode
{
    /**
     * uuid
     *
     * @var string
     * @access private
     */
    private $javascript;

    /**
     * fullname
     *
     * @var string
     * @access private
     */
    private $style;

    /**
     * Gets the uuid.
     *
     * @return string
     */
    public function getJavascript()
    {
        return $this->javascript;
    }

    /**
     * Sets the uuid.
     *
     * @param string $javascript the javascript
     *
     * @return self
     */
    public function setJavascript($javascript)
    {
        $this->javascript = $javascript;

        return $this;
    }

    /**
     * Gets the fullname.
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Sets the fullname.
     *
     * @param string $style the style
     *
     * @return self
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }
}
