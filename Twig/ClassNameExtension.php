<?php

namespace Lthrt\SchemaVisualizerBundle\Twig;

class ClassNameExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('className', [$this, 'classNameFilter']),
        ];
    }

    public function getName()
    {
        return 'classname_extension';
    }

    public function classNameFilter($string)
    {
        return strrev(strstr(strrev($string), '_', true));
    }
}
