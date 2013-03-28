<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of error
 *
 * @author Steven
 */
class Error extends Controller
{
    public $calledMethode;
    
    public function __construct($_fileName)
    {
        parent::__construct($_fileName,__CLASS__);
    }
    
    //---------------------------------------------------------------------------------------------------------------------
    
    /* show the content, generate by the template.
     */
    public function display() {
        
        $this->globalView->setGlobalTemplate('error/error.tpl');
        return $this->globalView->render($global = true);
    }

}

?>
