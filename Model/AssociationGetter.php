<?php

namespace Lthrt\SchemaVisualizerBundle\Model;

class AssociationGetter
{
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function getAssociations($class)
    {
        $metadata = $this->em->getClassMetadata($class);
        array_map(
            function ($a) use (&$associations) {
                $associations[] = $a['targetEntity'];
            },
            $metadata->associationMappings
        );
        $allMetadata = $this->em->getMetadataFactory()->getAllMetadata();
        foreach ($allMetadata as $md) {
            if (in_array($class,
                array_map(
                    function ($m) {
                        return $m['targetEntity'];
                    }, $md->associationMappings)
                )) {
                $associations[] = $md->name;
            }
        }

        return $associations;
    }
}
