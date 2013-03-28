<?php

//Controller of Index
class Index extends Controller{

    public $calledMethode;
    
    public function __construct($_fileName)
    {
        parent::__construct($_fileName,__CLASS__);     
    }
    
//---------------------------------------------------------------------------------------------------------------------
    
    /* show the content, generate by the template.
     */
    public function display() {

        echo $this->model->viewPageMenu();
        //set the template of the content
        $this->contentView->setContentTemplate('index');
        $this->contentView->assign('users', $this->model->getEntries());
        //assign the content-output to the content of global Template 
        $this->globalView->assign('nav', $this->model->viewPageMenu());
        $this->globalView->assign('content', $this->contentView->render());
        //render the templates  
        return  $this->globalView->render($global = true);
    }

}