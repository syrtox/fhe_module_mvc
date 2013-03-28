<?php

class View
{

    // Pfad zum Template
    private $path = 'templates';
    private $model;
    private $globalTpl = 'index';
    private $contentTpl = 'index';
    private $input = array();
    private $helpers;

//---------------------------------------------------------------------------------------------------------------------    
    /**
     * Enth�lt die Variablen, die in das Template eingebetet 
     * werden sollen.
     */
    public function __construct($model = 'index')
    {
        $this->model = $model;
    }

//---------------------------------------------------------------------------------------------------------------------    
    /**
     * Ordnet eine Variable einem bestimmten Schl&uuml;ssel zu.
     */
    public function assign($key, $value)
    {
        $this->input[$key] = $value;
    }

//---------------------------------------------------------------------------------------------------------------------    

    public function setGlobalTemplate($tpl = 'index')
    {
        $this->globalTpl = $tpl;
    }

//---------------------------------------------------------------------------------------------------------------------    

    public function setContentTemplate($tpl = 'index')
    {
        $this->contentTpl = $tpl;
    }

    //--------------------------------------------------------------------------------------------------------------------

    public function __call($methodName, $args)
    {
        $helper = $this->loadViewHelper($methodName);
        if ($helper === null)
        {
            return "Unknown ViewHelper $methodName";
        }
        $val = $helper->execute($args);
        return $val;
    }

    //-------------------------------------------------------------------------------------------------------------------- 
    protected function loadViewHelper($_helper)
    {

        $helperName = str_replace('_', ' ', $_helper);
        $helperName = str_replace(' ','',ucwords($helperName));
        
        if (!isset($this->helpers[$_helper]))
        {
            $className = "{$helperName}ViewHelper";
            $fileName = "models/viewHelper/" . lcfirst($_helper) . ".helper.php";

            if (!file_exists($fileName))
            {
                return null;
            }
            include_once($fileName);
            $this->helpers[$_helper] = new $className();
        }
        return $this->helpers[$_helper];
    }

//---------------------------------------------------------------------------------------------------------------------    

    /**
     * Das Template-File laden und zur�ckgeben
     */
    public function render($globalTpl = false)
    {

        // Pfad zum Template erstellen & �berpr�fen ob das Template existiert.
        $globalFile = $this->path . DIRECTORY_SEPARATOR . $this->globalTpl . '.php';
        $contentFile = $this->path . DIRECTORY_SEPARATOR . $this->model . DIRECTORY_SEPARATOR . $this->contentTpl . '.tpl.php';

        //if the rendering od global template set
        $file = ($globalTpl) ? $globalFile : $contentFile;
        if (file_exists($file))
        {

            // Der Output des Scripts wird in einen Buffer gespeichert, d.h.
            // nicht gleich ausgegeben.
            ob_start();

            // Das Template-File wird eingebunden und dessen Ausgabe in 
            // $output gespeichert.
            include $file;
            $output = ob_get_contents();
            ob_end_clean();

            // Output zur�ckgeben.
            return $output;
        }
        else
        {
            // Template-File existiert nicht-> Fehlermeldung.
            return 'could not find template';
        }
    }

}