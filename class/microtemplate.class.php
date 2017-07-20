<?php

/**
 * Class MapadoMicroTemplate
 */
class MapadoMicroTemplate extends ArrayObject
{
    private $template;


    /**
     * Initialization
     */
    public function __construct($template)
    {
        $this->template = $template;
    }


    /**
     * Public function
     */
    public function assign($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    public function assignIf($key, $callback, $conditionList)
    {
        $conditionList = array_slice(func_get_args(), 2);
        $parameterList = array();
        foreach ($conditionList as $condition) {
            if (!$condition) {
                $this->assign($key, false);
                return false;
            }
            $parameterList[] = $condition;
        }
        $this->assign($key, call_user_func_array($callback, $parameterList));
    }

    public function reset()
    {
        $this->exchangeArray(array());
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function output()
    {
        $output = $this->template;
        foreach ($this as $key => $value) {
            $output = preg_replace('/\[%\s*' . $key . '\s*](.*?)\[\s*' . $key . '\s*%]/s', empty($value)?'':'$1', $output);
            $output = preg_replace('/\[%\s*!\s*' . $key . '\s*](.*?)\[\s*' . $key . '\s*%]/s', empty($value)?'$1':'', $output);
        }
        foreach ($this as $key => $value) {
            $regexp = '/\[\[\s*' . $key . '(:([^\s*\]]*))?\s*\]\]/';
            if (is_callable($value)) {
                $output = preg_replace_callback($regexp, function ($matches) use ($value) {
                    $options = array();
                    if (count($matches) >= 3) {
                        $options = explode(':', $matches[2]);
                    }
                    return $value($options);
                }, $output);
            } else {
                $output = preg_replace($regexp, $value, $output);
            }
        }

        return $output;
    }
}
