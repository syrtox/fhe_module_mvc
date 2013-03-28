<?php

//Controller of Index
class ModuleOverview extends Controller
{

    public $calledMethode;

    public function __construct($_filePath)
    {
        parent::__construct($_filePath, __CLASS__);
    }

//---------------------------------------------------------------------------------------------------------------------
//    
    /* show the content, generate by the template.
     */
    public function display()
    {

//set the template of the content

        $this->contentView->setContentTemplate('overview');

        //assign the content-output to the content of global Template
        $this->globalView->assign('nav', $this->model->viewPageMenu());

        $this->globalView->assign('content', $this->contentView->render());
        //render the templates  
        return $this->globalView->render($global = true);
    }

}