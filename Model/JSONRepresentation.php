<?php

namespace Lthrt\SchemaVisualizerBundle\Model;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JSONRepresentation
{
    /**
     * @var EntityRepresentation
     */
    private $entityRepresentation;

    // mixed to handle arrays of entityRepresentations

    public function __construct($entityRepresentation)
    {
        $this->entityRepresentation = $entityRepresentation;
    }

    public function getJSON()
    {
        $rep        = $this->entityRepresentation;
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(['id']);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
        $json       = $serializer->serialize($rep, 'json');

        return $json;
    }
}
