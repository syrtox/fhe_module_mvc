<?php

class Bootstrap extends DPLoad
{

    private $url = null;
    private $controller = null;
    private $controllerPath = 'controllers/'; // Always include trailing slash
    private $errorFile = 'error.php';
    private $defaultFile;
    public static $currentSID = 0;

    public function __construct()
    {
        parent::__construct();
        $this->defaultFile = $this->getLoginPageID();
    }

//---------------------------------------------------------------------------------------------------------------------
    /**
     * Starts the Bootstrap
     * 
     * @return boolean
     */
    public function init()
    {

        //Sets the protected $_url
        //default schema  http://localhost/index.php?sid=controller&action=methode

        $loggedIn = Session::get('loggedin');
        $this->getUrl();

        //if the service mode set
        if (SERVICE_MODE && !$loggedIn)
        {
            self::$currentSID = $this->defaultFile;
            Notice::getGlobalServiceMode();
        }



        //check for the access of the current sid
        if ($this->PageisAccessAllowed(self::$currentSID))
        {
            //if logged in an the sid == login page and no logout
            if ($loggedIn && (self::$currentSID == $this->defaultFile) && $_GET['action'] != 'doLogout')
            {
                //forwarding to the profile index
                self::$currentSID = $this->GetDefaultForwardingPageID();
                $this->loadExistingController();
                $this->callControllerMethod();
            }
            else
            {
                //load existing controller, based on the url
                $this->loadExistingController();
                $this->callControllerMethod();
            }
        }
        else if ($loggedIn)
        {
            //default profil-page
            self::$currentSID = $this->GetDefaultForwardingPageID();

            //alert a page access error on the global template
            Notice::getPageAccessErr();

            $this->loadExistingController();
            $this->callControllerMethod();
        }
        else
        {
            //default login-page
            self::$currentSID = $this->defaultFile;
            $this->loadExistingController();
            $this->callControllerMethod();
        }
    }

//---------------------------------------------------------------------------------------------------------------------
    /**
     * (Optional) Set a custom path to controllers
     * @param string $path
     */
    public function setControllerPath($_path)
    {
        $this->controllerPath = trim($_path, '/') . '/';
    }

//---------------------------------------------------------------------------------------------------------------------
    /**
     * (Optional) Set a custom path to models
     * @param string $path
     */
    public function setModelPath($_path)
    {
        $this->modelPath = trim($_path, '/') . '/';
    }

//---------------------------------------------------------------------------------------------------------------------
    /**
     * (Optional) Set a custom path to the error file
     * @param string $path Use the file name of your controller, eg: error.php
     */
    public function setErrorFile($_path)
    {
        $this->errorFile = trim($_path, '/');
    }

//---------------------------------------------------------------------------------------------------------------------  
    /**
     * (Optional) Set a custom path to the error file
     * @param string $path Use the file name of your controller, eg: index.php
     */
    public function setDefaultFile($_path)
    {
        $this->defaultFile = trim($_path, '/');
    }

//---------------------------------------------------------------------------------------------------------------------    
    /**
     * Fetches the $_GET from 'url'
     */
    private function getUrl()
    {
        //if there no sid in the url, set the index as default
        $url[0] = isset($_GET['sid']) ? $_GET['sid'] : $this->getLoginPageID();
        $url[1] = isset($_GET['action']) ? $_GET['action'] : null;

        self::$currentSID = (int) $url[0];
        //filter NULL from the array
        $this->url = array_filter($url, 'strlen');
    }

//---------------------------------------------------------------------------------------------------------------------    
    /**
     * This loads if there is no GET parameter passed
     */
    private function loadDefaultController()
    {
        require $this->controllerPath . $this->defaultFile;
        $this->controller = new Index();

        echo $this->controller->display();
    }

//---------------------------------------------------------------------------------------------------------------------
    /**
     * Load an existing controller if there IS a GET parameter passed
     * default http://localhost/index.php?sid=controller&action=methode

     * * @return boolean|string
      HIER MUSS DYNAMICPAGELOAD EINSETZEN!!!
     */
    private function loadExistingController()
    {
        $fileLink = $this->getPageIncludeByID(self::$currentSID);

        //set the path of the controller for the new instance
        $file = $this->controllerPath . $fileLink;

        if (file_exists($file))
        {
            require $file;

            //return the position of the file extension
            $ext = strpos($fileLink, ".php");

            //new filepath without the extension
            $filePath = substr($fileLink, 0, $ext);
            //load the controller of the file

            $className = str_replace('/', ' ', $filePath);

            //upper all first letters in the string
            $className = ucwords($className);

            //trim the whitespaces
        $className = str_replace(' ', '', $className);

            $this->controller = new $className($filePath);
            return true;
        }
        else
        {
            $this->error();
            return false;
        }
    }

//---------------------------------------------------------------------------------------------------------------------    
    /**
     * If a method is passed in the GET url paremter
     * 
     *  http://localhost/index.php?sid=controller&action=methode
     */
    private function callControllerMethod()
    {
        $length = count($this->url);
        // Make sure the method we are calling exists
        if ($length > 1)
        {
            if (!method_exists($this->controller, $this->url[1]))
            {
                $this->error();
            }
            else
            {
                //call the methode of the controller
                $this->controller->{$this->url[1]}();
                $this->controller->calledMethode = $this->url[1];
                //render the template
                echo $this->controller->display();
            }
        }
        else
        {
            //default
            echo $this->controller->display();
        }
    }

//---------------------------------------------------------------------------------------------------------------------    
    /**
     * Display an error page if nothing exists
     * 
     * @return boolean
     */
    private function error()
    {
        
        require $this->controllerPath . $this->errorFile;
        $this->controller = new Error($args = "");
        $this->controller->display();
        return false;
    }

}