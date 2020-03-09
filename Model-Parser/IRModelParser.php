<?php

function xml_attribute($object, $attribute)
{
    if(isset($object[$attribute]))
        return (string) $object[$attribute];
}

function port_string($port)
{
    return implode(" ", (array) $port->dim);
}

class IRModelParser {

    private $irXml = null;

    public function __construct($irXmlFile) 
    {
        $this->irXml = simplexml_load_file($irXmlFile);
    }

    public function createNetworkNodes(array $filterNodes)
    {
        $layerNodes = $this->irXml->layers->layer;
        $networkInformation = array();
        $inputNodes = array();
        $outputNodes = array();
        foreach($layerNodes as $layerNode) {
            $name = xml_attribute($layerNode, 'name');
            if (!in_array($name, $filterNodes)) {
                continue;
            }
            $inputIndex = 0;
            $outputIndex = 0;
            foreach($layerNode->children() as $key => $value) {
                $port = $value->port;
                if (!array_key_exists($name, $inputNodes)) {
                    $inputNodes[$name] = [];
                }
                if (!array_key_exists($name, $outputNodes)) {
                    $outputNodes[$name] = [];
                }
                if($key == "input") {
                    // $str = "0:node_name1[3 4],node_name2:1[2]->[20 15]";
                    $portString = port_string($port);
                    array_push($inputNodes[$name], 
                    count($inputNodes[$name]) . ":{$name}[{$portString}]");
                } else if($key == "output") {
                    $portString = port_string($port);
                    $shape = count(explode(" ", $portString));
                    array_push($outputNodes[$name], 
                    $name . ":" . count($outputNodes[$name]) . "[{$shape}]->[{$portString}]");
                }
            }
        }
        
        $nodes = array_map(function($input, $output) {
            $n = [];
            array_push($n, implode(",", $input));
            array_push($n, implode(",", $output));
            return implode(",", $n);
        }, $inputNodes, $outputNodes);

        return implode(",", $nodes);
    }

}

$filterNodes = explode(",", $argv[2]);
$filterNodes = array_map(function ($node) {
    return trim($node);
}, $filterNodes);

$parser = new IRModelParser($argv[1]);
$res = $parser->createNetworkNodes($filterNodes);
print($res);
