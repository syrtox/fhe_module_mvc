<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author Steven
 */
class Controller
{
    protected $globalView;
    protected $contentView;
    protected $modelPath = 'models/';
    protected $model;

 //-----------------------------------------------------------------------------------------------------------------
    public function __construct($_filePath, $_class) {
        $this->globalView = new View();
        
        
        $model = substr($_filePath,0,strpos($_filePath,"/"));
        //new view for the content in the global tpl
        $this->contentView = new View($model);
        $path = $this->modelPath . $_filePath.'_model.php';

        if (file_exists($path)) {
            require $path;

            $modelName = $_class.'_Model';
            $this->model = new $modelName();
        }
    }
}

?>
