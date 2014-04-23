<?php

namespace Udb\Domain\Util;

use Zend\Stdlib\Parameters;


trait ObjectParamsTrait
{

    /**
     * @var Parameters
     */
    protected $params;


    /**
     * @return Parameters
     */
    public function getParams()
    {
        if (! $this->params instanceof Parameters) {
            $this->params = new Parameters();
        }
        
        return $this->params;
    }


    /**
     * @param Parameters $params
     */
    public function setParams(Parameters $params)
    {
        $this->params = $params;
    }


    /**
     * @param string $name
     * @param mixed $defaultValue
     */
    public function getParam($name, $defaultValue = null)
    {
        return $this->getParams()->get($name, $defaultValue);
    }


    /**
     * Tries to return the required parameter and throws an exception if the parameter has not been set.
     * 
     * @param string $name
     * @return mixed
     */
    public function getRequiredParam($name)
    {
        $value = $this->getParam($name);
        if (null === $value) {
            throw new Exception\MissingParamException(sprintf("Missing required parameter '%s'", $name));
        }
        
        return $value;
    }


    /**
     * @param string $name
     * @param string $value
     * @return Parameters
     */
    public function setParam($name, $value)
    {
        $this->getParams()->set($name, $value);
        return $this;
    }
}