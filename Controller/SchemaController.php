<?php

namespace Lthrt\SchemaVisualizerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Eigenfunction controller.
 *
 * @Route("schema")
 */
class SchemaController extends Controller
{
    /**
     * Get Dagre Representation
     *
     * @Route("/graph/{depth}/{class}", name="graph_schema")
     * @Method("GET")
     */
    public function dagreAction(
        Request $request,
                $depth = 0,
                $class = null
    ) {
        $adjacencyList = $this->get('lthrt_schema_visualizer.representation_service')->getAdjacencyListJSON($class, $depth);

        return $this->render('LthrtSchemaVisualizerBundle:Schema:dagre.html.twig', [
            'adjacencyList' => $adjacencyList,
            'class'         => $class,
            'depth'         => $depth,
        ]);
    }

    /**
     * Get Old Graph Representation
     *
     * @Route("/graphml/{depth}/{class}", name="old_graph_schema")
     * @Method("GET")
     */
    public function graphAction(
        Request $request,
                $depth = 0,
                $class = null
    ) {
        $adjacencyList = $this->get('lthrt_schema_visualizer.representation_service')->getAdjacencyListJSON($class, $depth);

        return $this->render('LthrtSchemaVisualizerBundle:Schema:graph.html.twig', [
            'adjacencyList' => $adjacencyList,
            'class'         => $class,
            'depth'         => $depth,
        ]);
    }

    /**
     * Get Graphml Representation
     *
     * @Route("/oldgraph/{depth}/{class}", name="graphml_schema")
     * @Method("GET")
     */
    public function graphmlAction(
        Request $request,
                $depth = 0,
                $class = null
    ) {
        $json = $this->get('lthrt_schema_visualizer.representation_service')->getNodesAndEdges($class);

        return $this->render('LthrtSchemaVisualizerBundle:Schema:graphml.html.twig', [
            'json'  => $json,
            'class' => $class,
            'depth' => $depth,
        ]);
    }

    /**
     * Get Json Representation
     *
     * @Route("/json/{depth}/{class}", name="json_schema")
     * @Method("GET")
     */
    public function jsonAction(
        Request $request,
                $depth = 0,
                $class = null
    ) {
        $json          = $this->get('lthrt_schema_visualizer.representation_service')->getJSON($class);
        $adjacencyList = $this->get('lthrt_schema_visualizer.representation_service')->getAdjacencyListJSON($class, $depth);

        return $this->render('LthrtSchemaVisualizerBundle:Schema:json.html.twig', [
            'json'          => $json,
            'class'         => $class,
            'adjacencyList' => $adjacencyList,
            'depth'         => $depth,
        ]);
    }

    /**
     * List Schema
     *
     * @Route("/", name="list_schema")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $classes = array_map(function ($m) {return str_replace('\\', '_', $m->getName());},
            $this->getDoctrine()->getManager()->getMetadataFactory()->getAllMetadata()
        );

        return $this->render('LthrtSchemaVisualizerBundle:Schema:list.html.twig', ['classes' => $classes]);
    }
}
