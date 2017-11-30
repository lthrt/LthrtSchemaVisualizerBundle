<?php

namespace Lthrt\SchemaVisualizerBundle\Model;

use Doctrine\ORM\Mapping\ClassMetadata;

class EntityRepresentation
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $parent;

    /**
     * @var string
     */
    private $fields;

    /**
     * @var string
     */
    private $oneToOne;

    /**
     * @var string
     */
    private $oneToMany;

    /**
     * @var string
     */
    private $manyToOne;

    /**
     * @var string
     */
    private $manyToMany;

    public function __construct(ClassMetadata $metadata, $class = null)
    {
        $this->name   = strrev(strstr(strrev($metadata->name), '\\', true));
        $this->class  = str_replace('\\', '_', $metadata->name);
        $this->parent = array_map(function ($c) {return strrev(strstr(strrev($c), '\\', true));}, $metadata->parentClasses);
        $this->fields = array_keys(array_map(function ($f) {return $f['fieldName'];}, $metadata->fieldMappings));

        $constants['oneToOne']   = ClassMetadata::ONE_TO_ONE;
        $constants['oneToMany']  = ClassMetadata::ONE_TO_MANY;
        $constants['manyToOne']  = ClassMetadata::MANY_TO_ONE;
        $constants['manyToMany'] = ClassMetadata::MANY_TO_MANY;

        foreach (['oneToOne', 'oneToMany', 'manyToOne', 'manyToMany'] as $type) {
            $this->$type = array_unique(
                array_map(
                    function ($f) {return str_replace('\\', '_', $f['targetEntity']);},
                    array_filter($metadata->associationMappings,
                        function ($a) use ($class, $constants, $type, $metadata) {
                            return $class
                            ? $constants[$type] == $a['type'] && ($metadata->name == $a['targetEntity'] || $metadata->name == $a['sourceEntity'])
                            : $constants[$type] == $a['type'];
                        }
                    )
                )
            );
        }
    }

    /**
     * Get class.
     *
     * @return
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set class.
     *
     * @param
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get name.
     *
     * @return
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent.
     *
     * @param
     *
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get fields.
     *
     * @return
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set fields.
     *
     * @param
     *
     * @return $this
     */
    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Get oneToOne.
     *
     * @return
     */
    public function getOneToOne()
    {
        return $this->oneToOne;
    }

    /**
     * Set oneToOne.
     *
     * @param
     *
     * @return $this
     */
    public function setOneToOne($oneToOne)
    {
        $this->oneToOne = $oneToOne;

        return $this;
    }

    /**
     * Get oneToMany.
     *
     * @return
     */
    public function getOneToMany()
    {
        return $this->oneToMany;
    }

    /**
     * Set oneToMany.
     *
     * @param
     *
     * @return $this
     */
    public function setOneToMany($oneToMany)
    {
        $this->oneToMany = $oneToMany;

        return $this;
    }

    /**
     * Get manyToOne.
     *
     * @return
     */
    public function getManyToOne()
    {
        return $this->manyToOne;
    }

    /**
     * Set manyToOne.
     *
     * @param
     *
     * @return $this
     */
    public function setManyToOne($manyToOne)
    {
        $this->manyToOne = $manyToOne;

        return $this;
    }

    /**
     * Get manyToMany.
     *
     * @return
     */
    public function getManyToMany()
    {
        return $this->manyToMany;
    }

    /**
     * Set manyToMany.
     *
     * @param
     *
     * @return $this
     */
    public function setManyToMany($manyToMany)
    {
        $this->manyToMany = $manyToMany;

        return $this;
    }
}
