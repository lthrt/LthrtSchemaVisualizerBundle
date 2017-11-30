<?php

namespace Lthrt\SchemaVisualizerBundle\Model;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NodesAndEdgesRepresentation
{
    /**
     * @var EntityRepresentation
     */
    private $nodesAndEdges;

    // mixed to handle arrays of entityRepresentations

    public function __construct($entityRepresentation)
    {
        $nodes                        = new NodesRepresentation($entityRepresentation);
        $edges                        = new EdgesRepresentation($entityRepresentation);
        $this->nodesAndEdges['nodes'] = $nodes->getJSON();
        $this->nodesAndEdges['edges'] = $edges->getJSON();
    }

    public function getJSON()
    {
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(['id']);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
        $json       = $serializer->serialize($this->nodesAndEdges, 'json');
        $json       = str_replace('\\', '', $json);
        $json       = str_replace('"[', '[', $json);
        $json       = str_replace(']"', ']', $json);

        return $json;
    }
}
