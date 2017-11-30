<?php

namespace Lthrt\SchemaVisualizerBundle\Model;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NodesRepresentation
{
    private $nodes;

    // mixed to handle arrays of entityRepresentations

    public function __construct($entityRepresentations)
    {
        foreach ($entityRepresentations as $entityRepresentation) {
            $node['name']   = $entityRepresentation->getName();
            $node['class']  = $entityRepresentation->getClass();
            $node['fields'] = $entityRepresentation->getFields();
            $nodes[]        = $node;
        }
        $this->nodes = [];

        foreach ($nodes as $node) {
            if (!in_array($node, $this->nodes)) {
                $this->nodes[] = $node;
            }
        }
    }

    public function getJSON()
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(['id']);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
        $json       = $serializer->serialize($this->nodes, 'json');

        return $json;
    }
}
