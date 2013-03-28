<?php

class ProfileIndex extends Controller
{
    public $calledMethode;

    function __construct($_fileName)
    {
        parent::__construct($_fileName,__CLASS__);
    }
  
//---------------------------------------------------------------------------------------------------------------------
    
    /* show the content, generate by the template.
     */
    public function display() {
        
        //get the userdetails
        $this->model->initProfil();
        //set the template of the content
        $this->contentView->setContentTemplate('profile');
        
        $this->contentView->assign('name', $this->model->name);
        $this->contentView->assign('username', $this->model->username);
        $this->contentView->assign('userGroup', $this->model->userGroup);
      
        //assign the content-output to the content of global Template 
        $this->globalView->assign('nav', $this->model->viewPageMenu());
        $this->globalView->assign('content', $this->contentView->render());       
        //render the templates  
        return $this->globalView->render($global = true);
    }
}
?>
