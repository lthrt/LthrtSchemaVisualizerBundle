<?php

namespace Lthrt\SchemaVisualizerBundle\Model;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EdgesRepresentation
{
    private $links;

    // mixed to handle arrays of entityRepresentations

    public function __construct($entityRepresentations)
    {
        foreach ($entityRepresentations as $entityRepresentation) {
            foreach (['oneToOne', 'oneToMany', 'manyToOne', 'manyToMany'] as $relation) {
                $getMethod = 'get' . ucfirst($relation);
                foreach ($entityRepresentation->$getMethod() as $key => $type) {
                    $link                                                           = [];
                    $link[stristr($relation, 'To', true)][]                         = $entityRepresentation->getClass();
                    $link[strtolower(substr(stristr($relation, 'To', false), 2))][] = $type;
                    foreach (['one', 'many'] as $kind) {
                        if (isset($link[$kind])) {
                            sort($link[$kind]);
                        }
                    }
                    $links[] = $link;
                }
            }
        }
        $this->links = [];

        foreach ($links as $link) {
            if (!in_array($link, $this->links)) {
                $this->links[] = $link;
            }
        }
    }

    public function getJSON()
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(['id']);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
        $json       = $serializer->serialize($this->links, 'json');

        return $json;
    }
}
