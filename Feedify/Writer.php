<?php

namespace Feedify;

class Writer
{


    /* CONSTANTS
     *************************************************************************/
    const RSS_FORMAT = 'rss';
    const SITEMAP_FORMAT = 'sitemap';



    /* ATTRIBUTES
     *************************************************************************/
    public $title;
    public $description;
    public $feedURL;
    public $siteURL;
    public $items = array();

    protected $formatters = array();
    protected $attributeTypes = array(
        'title' => 'string',
        'url' => 'string',
        'date' => 'date',
        'intro' => 'string',
        'author' => 'string'
    );



    /* PUBLIC METHODS
     *************************************************************************/
    public function output($format=self::RSS_FORMAT) {
        $view = $this->getTwigView($format);
        $parameters = array();
        $parameters['feed'] = $this;
        $parameters['items'] = $this->getFormattedItems();
        echo $view->render($parameters);
    }

    public function addItems($items) {
        if (!is_array($items)) {
            $items = array($items);
        }
        $this->items = array_merge($this->items, $items);
    }

    public function addAttribute($attributeName) {
        $this->addAttributeFormatter($attributeName);
    }

    public function addAttributeMap($attributeName, $mapAttribute) {
        $this->addAttributeFormatter($attributeName, $mapAttribute);
    }

    public function addAttributeFormatter($attributeName, $formatter=NULL) {
        if (in_array($attributeName, array_keys($this->attributeTypes))) {
            if (!$formatter) {
                $formatter = $attributeName;
            }
            if (is_string($formatter)) {
                $formatter = $this->getDefaultFormatter($formatter);
            }
            $this->formatters[$attributeName] = $formatter;
        } else {
            throw new \Exception('Unknown feed attribute: '.$attributeName);
        }
    }



    /* PRIVATE METHODS
     *************************************************************************/
    protected function getFormattedItems() {
        $formattedItems = array();
        foreach ($this->items as $item) {
            $formattedItem = array();
            foreach ($this->formatters as $attributeName => $formatter) {
                $formattedItem[$attributeName] = $this->checkAttributeFormat($attributeName, $formatter($item));
            }
            $formattedItems[] = $formattedItem;
        }
        return $formattedItems;
    }

    protected function checkAttributeFormat($attributeName, $attributeValue) {
        if ($this->attributeTypes[$attributeName] == 'date') {
            // Handle standard string date
            if (is_string($attributeValue)) {
                $attributeValue = strtotime($attributeValue);
            }
            // Handle timestamp
            if (is_int($attributeValue)) {
                $attributeValue = new \DateTime($attributeValue);
            }
            if (!is_a($attributeValue, 'DateTime')) {
                return NULL;
            }
        } else {
            if (!is_string($attributeValue)) {
                return NULL;
            }
        }
        return $attributeValue;
    }

    protected function getDefaultFormatter($attributeName) {
        return function ($object) use ($attributeName) {
            if (isset($object[$attributeName])) {
                return $object[$attributeName];
            }
            if (isset($object->$attributeName)) {
                return $object->$attributeName;
            }
        };
    }

    protected function getTwigView($format) {
        header('Content-Type: application/rss+xml; charset=utf-8');
        $filename = $format.'.twig';
        if (!is_file(__DIR__.'/'.$filename)) {
            throw new \Exception('Unknown feed format: '.$format);
        }
        $loader = new \Twig_Loader_Filesystem(__DIR__);
        $twig = new \Twig_Environment($loader);
        $twig = $this->addTwigFilters($twig);
        return $twig->loadTemplate($filename);
    }

    protected function addTwigFilters($twig) {
        $formatDateFilter = new \Twig_SimpleFilter('formatDate', function(\DateTime $date, $format) {
            return $date->format($format);
        });
        $twig->addFilter($formatDateFilter);
        return $twig;
    }

}




