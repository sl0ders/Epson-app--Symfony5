<?php

namespace App\Services;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;

class xmlManager
{
    /**
     * @var Container
     */
    private $container;

    /**
     * xmlManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function receiptAllFilesName(): array
    {
        // set the wrapper for xml files
        $path = $this->container->getParameter('folder_import');
        // retrieve all files names
        $files = scandir($path);
        $filesPath = [];
        foreach ($files as $file) {
            // recovery of extension for each file name
            $path_part = pathinfo($file);
            // if the extendion is xml, adding of this file name in an array to eliminate the first two elements of the base table which only returns points
            if ($path_part['extension'] == "xml") {
                // create a path of each file xml
                array_push($filesPath, $path ."/" . $file);
            }
        }
        return $filesPath;
    }

    public function ParserXml($Uri)
    {
         // the "\" is not understood by the simplexml_load_file function so we replace the "\" by / for each url
        $file = str_replace("\\", "/", $Uri);
        $xml = @simplexml_load_file($file);
        if (!$xml) {
            echo "Erreur aucun fichier xml trouvé";
        } else {
            // turn the file path into xml file
            $xml = simplexml_load_file($file);
        }
        return $xml;
    }
}
