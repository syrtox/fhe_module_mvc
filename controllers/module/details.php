<?php

//Controller of Index
class ModuleDetails extends Controller
{

    public $calledMethode;
    private $module;
    
    public function __construct($_filePath)
    {
        parent::__construct($_filePath, __CLASS__);
        $this->module = $_GET['module'];
    }

//---------------------------------------------------------------------------------------------------------------------
//    
    /* show the content, generate by the template.
     */
    public function display()
    {

    //set the template of the content

        $this->contentView->setContentTemplate('details');
        $this->contentView->assign('modBreadcrumb', $this->model->showModBreadcrumb($this->module));
        //assign the content-output to the content of global Template
        $this->globalView->assign('nav', $this->model->viewPageMenu());

        $this->globalView->assign('content', $this->contentView->render());
        //render the templates  
        return $this->globalView->render($global = true);
    }

//----------------------------------------------------------------------------------------------------------------------
    
    public function showDetails()
    {
        $this->contentView->assign('details', $this->model->getModDetails($this->module));     
    }
}