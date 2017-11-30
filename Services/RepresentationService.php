<?php

namespace Lthrt\SchemaVisualizerBundle\Services;

use Lthrt\SchemaVisualizerBundle\Model\AdjacencyListRepresentation;
use Lthrt\SchemaVisualizerBundle\Model\AssociationGetter;
use Lthrt\SchemaVisualizerBundle\Model\EntityRepresentation;
use Lthrt\SchemaVisualizerBundle\Model\GraphRepresentation;
use Lthrt\SchemaVisualizerBundle\Model\JSONRepresentation;
use Lthrt\SchemaVisualizerBundle\Model\NodesAndEdgesRepresentation;

class RepresentationService
{
    private $em;

    public function __construct($em, $router)
    {
        $this->em     = $em;
        $this->router = $router;
    }

    public function getAdjacencyListJSON($class = null, $level = 1)
    {
        $allMetadata = $this->em->getMetadataFactory()->getAllMetadata();
        $class       = str_replace('_', '\\', $class);
        if ($class) {
            $getter    = new AssociationGetter($this->em);
            $classes   = $getter->getAssociations($class);
            $classes[] = $this->em->getClassMetadata($class)->name;
            $counter   = 1;
            while ($counter < intval($level)) {
                foreach ($classes as $iterClass) {
                    $associations = $getter->getAssociations($iterClass);
                    foreach ($associations as $association) {
                        $classes[] = $association;
                    }
                }
                $counter++;
            }
        } else {
            $classes = array_map(function ($m) {return $m->getName();},
                $this->em->getMetadataFactory()->getAllMetadata()
            );
        }

        $classes = array_unique($classes);

        foreach ($classes as $key => $newClass) {
            $metadata                = $this->em->getClassMetadata($newClass);
            $entityRepresentations[] = new EntityRepresentation($metadata, $class);
        }
        $graphRepresentation = new AdjacencyListRepresentation($entityRepresentations, $this->router);

        return $graphRepresentation->getJSON($level);
    }

    public function getGraphJSON($class = null)
    {
        if ($class) {
            $class   = str_replace('_', '\\', $class);
            $classes =
                array_map(
                function ($d) {return $d->name;},
                array_filter(
                    $this->em->getMetadataFactory()->getAllMetadata(),
                    function ($m) use ($class) {
                        return in_array($class,
                            array_map(
                                function ($md) {return $md['targetEntity'];},
                                $m->associationMappings
                            )
                        );
                    }
                )
            );
            $classes[] = $this->em->getClassMetadata($class)->name;
            $classes   = array_unique($classes);
        } else {
            $classes = array_map(function ($m) {return $m->getName();},
                $this->em->getMetadataFactory()->getAllMetadata()
            );
        }

        foreach ($classes as $key => $newClass) {
            $metadata                = $this->em->getClassMetadata($newClass);
            $entityRepresentations[] = new EntityRepresentation($metadata, $class);
        }

        $graphRepresentation = new GraphRepresentation($entityRepresentations, $this->router);

        return $graphRepresentation->getJSON($level);
    }

    public function getJSON($class = null)
    {
        if ($class) {
            $classes = [$class = str_replace('_', '\\', $class)];
        } else {
            $classes = array_map(function ($m) {return $m->getName();},
                $this->em->getMetadataFactory()->getAllMetadata()
            );
        }

        foreach ($classes as $key => $class) {
            $metadata                = $this->em->getClassMetadata($class);
            $entityRepresentations[] = new EntityRepresentation($metadata);
        }
        $jsonRepresentation = new JSONRepresentation($entityRepresentations);

        return $jsonRepresentation->getJSON();

        // $class                = str_replace('_', '\\', $class);
        // $metadata             = $this->em->getClassMetadata($class);
        // $entityRepresentation = new EntityRepresentation($metadata);
        // $jsonRepresentation   = new JSONRepresentation($entityRepresentation);

        // return $jsonRepresentation->getJSON($level);
    }

    public function getNodesAndEdges($class = null)
    {
        if ($class) {
            $classes = [$class = str_replace('_', '\\', $class)];
        } else {
            $classes = array_map(function ($m) {return $m->getName();},
                $this->em->getMetadataFactory()->getAllMetadata()
            );
        }

        foreach ($classes as $key => $class) {
            $metadata                = $this->em->getClassMetadata($class);
            $entityRepresentations[] = new EntityRepresentation($metadata);
        }
        $representation = new NodesAndEdgesRepresentation($entityRepresentations);

        return $representation->getJSON();
    }
}
