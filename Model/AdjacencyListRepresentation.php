<?php

namespace Lthrt\SchemaVisualizerBundle\Model;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AdjacencyListRepresentation
{
    /**
     * @var EntityRepresentation
     */
    private $adjacencyList;

    // mixed to handle arrays of entityRepresentations

    public function __construct($entityRepresentations, $router, $level = 0)
    {
        $entityList = array_map(function ($er) {return $er->getClass(); }, $entityRepresentations);
        foreach ($entityRepresentations as $entityRepresentation) {
            foreach (['oneToOne', 'oneToMany', 'manyToOne', 'manyToMany'] as $type) {
                $getMethod = 'get' . ucfirst($type);
                foreach ($entityRepresentation->$getMethod() as $key => $relation) {
                    if (in_array($relation, $entityList)) {
                        $this->adjacencyList[$entityRepresentation->getClass()][] = $relation;
                        if (!isset($this->adjacencyList[$relation])) {
                            $this->adjacencyList[$relation] = [];
                        }
                    }
                }
            }
        }
    }

    public function getJSON()
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(['id']);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
        $json       = $serializer->serialize($this->adjacencyList, 'json');

        return $json;
    }
}
