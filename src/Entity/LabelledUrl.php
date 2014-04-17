<?php

namespace Udb\Domain\Entity;


class LabelledUrl
{

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $label;


    /**
     * Constructor.
     * 
     * @param string $url
     * @param string $label
     */
    public function __construct($url, $label)
    {
        $this->url = $url;
        $this->label = $label;
    }


    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}