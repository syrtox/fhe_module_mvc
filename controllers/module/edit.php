<?php

//Controller of Index
class ModuleEdit extends Controller
{

    public $calledMethode;
    private $module;
    
    public function __construct($_filePath)
    {
        parent::__construct($_filePath, __CLASS__);
        $this->module = (string)$_GET['id'];
    }

//---------------------------------------------------------------------------------------------------------------------
//    
    /* show the content, generate by the template.
     */
    public function display()
    {

    //set the template of the content
        $this->contentView->setContentTemplate('edit');
        $this->contentView->assign('post', Form::fetch());
        $this->contentView->assign('alert', Notice::$contentAlert);
        //assign the content-output to the content of global Template
        $this->globalView->assign('nav', $this->model->viewPageMenu());

        $this->globalView->assign('content', $this->contentView->render());
        //render the templates  
        return $this->globalView->render($global = true);
    }

//----------------------------------------------------------------------------------------------------------------------
    
    public function editModule()
    {
        echo "wwww";
        $this->model->edit($this->module);  
        
    }
    
//----------------------------------------------------------------------------------------------------------------------
    
    public function addModule()
    {
        echo "ghggzgzgzw";
        $this->model->run();     
    }
}