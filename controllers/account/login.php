<?php

//Controller of Index
class AccountLogin extends Controller
{

    public $calledMethode;

    public function __construct($_fileName)
    {
        parent::__construct($_fileName,__CLASS__);
    }

//---------------------------------------------------------------------------------------------------------------------

    /* show the content, generate by the template.
     */
    public function display()
    {
        //set the template of the content
        if ($this->calledMethode == 'doLogout')
        {
            $this->contentView->setContentTemplate('logout');
        }
        else
        {
            $this->contentView->setContentTemplate('login');
            $this->contentView->assign('alert', Notice::$contentAlert);
        }
        //assign the content-output to the content of global Template
        $this->globalView->assign('nav', $this->model->viewPageMenu());

        //if the service mode set and no admin is logged in
        if (SERVICE_MODE && !Session::get('loggedin'))
        {
            $this->globalView->assign('nav', '');
        }
        
        $this->globalView->assign('content', $this->contentView->render());
        //render the templates  
        return $this->globalView->render($global = true);
    }

//---------------------------------------------------------------------------------------------------------------------    
    function doLogin()
    {
        $this->model->doLogin();
    }

//---------------------------------------------------------------------------------------------------------------------    
    function doLogout()
    {
        Session::init();
        if (Session::get('loggedin'))
        {
            // Löschen aller Session-Variablen.
            Session::destroy();

//        // Session-Cookie.
//            $params = session_get_cookie_params();
//            setcookie(userLogin, '', time() - 42000, $params["path"],
//            $params["domain"], $params["secure"], $params["httponly"]
//            ); 
        }
        else
        {
            header('location: index.php');
        }
    }

}