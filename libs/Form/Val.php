<?php

class Val 
{
    public function __construct()
    {
        
    }
    
    public function minlength($data, $arg)
    {
        if (strlen($data) < $arg) {
            return "Your string can only be $arg long";
        }
    }
    
    public function maxlength($data, $arg)
    {
        if (strlen($data) > $arg) {
            return "Your string can only be $arg long";
        }
    }
    
    public function isNotEmpty($data,$opt = 0)
    {
        if ((!isset($data) || empty($data)) && $opt == 0) {
            return "Your string can only be $data long";
        }
        else if (!isset($data))  
        {
             return "Your string can only be $arg long";
        }
    }
    
    
    public function digit($data)
    {
        if (ctype_digit($data) == false) {
            return "Zahlenfeld nicht korrekt ausge&uuml;t";
        }
    }
    
    public function __call($name, $arguments) 
    {
        throw new Exception("$name does not exist inside of: " . __CLASS__);
    }
    
}