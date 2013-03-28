<?php

error_reporting(E_ALL ^ E_NOTICE);
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set("display_errors", 1);

Session::init();
include_once "config.php";

//autoload needed Class-Files
function __autoload($class)
{
    //look fpr the class file in LIBS
    $file = $class . ".php";

    if (!file_exists($file))
    {
        //cut the classname
        $class = substr($class, 0, strpos($class, "ViewHelper")); 
        $array = array($class);
        
       //search for the upper letters 
        for ($i = 1; $i < strlen($class); $i++) 
        {
            if (preg_match("/[A-Z]/", $class[$i]))
            {
                $array[0] = substr($class, 0, $i); //cut the string
                $array[1] = substr($class, $i, strlen($class));
            }
        }
        $file = strtolower(implode("_", $array)); //implode the array
        require_once ("../models/viewHelper/".$file.".helper.php");
    }
    else
    {
        require_once $file;
    }   
}

//wenn Werte uebermittelt
if(isset($_POST['args']))
Session::set('request', $_POST['args']);

//lesen der Werte aus Session
$args = Session::get('request');
$id = $_POST['id'];

switch ($_GET['do'])
{
    case getMajorsAsDrop:
        $var = new UniversityViewHelper();
        echo $var->getMajorsAsDrop($id);
        break;
    case getAreaOfSpecAsDrop:
        $var = new UniversityViewHelper();
        echo $var->getAreaOfSpecAsDrop($id);
        break;
    case getModules:

        //if a subject choosed...
        if ($args[1] != 0) //if a major choosed...
        {
            if ($args[4] == 1) //if the view == table
            {
                $var = new ModuleListViewHelper();
                echo $var->getModuleMajorTable($args);
            }
            else //if the view == timeline
            {
                $var = new ModuleListViewHelper();
                echo $var->getModuleMajorTimeline($args);
            }
        }
        else //all Others
        {
            $var = new ModuleListViewHelper();
            echo $var->getModuleOverview($args);
        }
        break;
        case showModuleList:

            $var = new ModuleListViewHelper();
            echo $var->showModuleList($args);          
        break;
    case subMod:
        
        $var = new ModuleDetailsViewHelper();
        $var->subscribeMod($id,1);
        echo Notice::$contentAlert;
        echo $var->getModDetailsHeader($id);
        break;
    case unsubMod:
        
        $var = new ModuleDetailsViewHelper();
        $var->unsubscribeMod($id,1);
        echo Notice::$contentAlert;
        echo $var->getModDetailsHeader($id);
        break;
    case subExam:
        
        $var = new ModuleDetailsViewHelper();
        $var->subscribeMod($id,0);
        echo Notice::$contentAlert;
        echo $var->getModDetailsHeader($id);
        break;
    case unsubExam:
        
        $var = new ModuleDetailsViewHelper();
        $var->unsubscribeMod($id,0);
        echo Notice::$contentAlert;
        echo $var->getModDetailsHeader($id);
        break;
   case actMod:
        $list = new ModuleListViewHelper;
        $var = new ModuleEditViewHelper();
        $var->activateMod($id);
        echo Notice::$contentAlert;
        echo $list->showModuleList();
        break;
    case deactMod:
        $list = new ModuleListViewHelper;
        $var = new ModuleEditViewHelper();
        $var->deactivateMod($id);
        echo Notice::$contentAlert;
        echo $list->showModuleList();
        break;
    case delMod:
        $list = new ModuleListViewHelper;
        $var = new ModuleEditViewHelper();
        $var->deleteMod($id);
        echo Notice::$contentAlert;
        echo $list->showModuleList($args);
        break;
    case rating:
        $var = new ModuleDetailsViewHelper();        
        echo $var->rateMod();
        break;
    default:
}
?>
