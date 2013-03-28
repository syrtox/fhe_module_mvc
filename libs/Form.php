<?php
/**
 *
 * - Fill out a form
 *    - POST to PHP
 *  - Sanitize
 *  - Validate
 *  - Return Data
 *  - Write to Database
 
 */

require 'Form/Val.php';
class Form
{
    
    /** @var array $_currentItem The immediately posted item*/
    private $currentItem = null;
    
    /** @var array $_postData Stores the Posted Data */
    public static $postData = array();
    
    /** @var object $_val The validator object */
    private $val = array();
    
    /** @var array $_error Holds the current forms errors */
    private $error = array();

    public function __construct() 
    {
        $this->val = new Val();
    }
    
    /**
     * post - This is to run $_POST
     * 
     * @param string $field - The HTML fieldname to post
     */
     public function post($field,$opt = 0)
    { 
        if($opt == 1)
        self::$postData[$field] = implode(", ",$_POST[$field]);
        else
        self::$postData[$field] = $_POST[$field];    
            
        self::$currentItem = $field;
        
        return $this;
    }
    
    /**
     * fetch - Return the posted data
     * 
     * @param mixed $fieldName
     * 
     * @return mixed String or array
     */
    public static function fetch($fieldName = false)
    {
        if ($fieldName) 
        {
            if (isset($self::$postData[$fieldName]))
            return $self::$postData[$fieldName];
            
            else
            return false;
        } 
        else 
        {
            return self::$postData;
        }
        
    }
    
    /**
     * val - This is to validate
     * 
     * @param string $typeOfValidator A method from the Form/Val class
     * @param string $arg A property to validate against
     */
    public function val($typeOfValidator, $arg = null)
    {
        if ($arg == null)
        $error = $this->val->{$typeOfValidator}(self::$postData[$this->currentItem]);
        else
        $error = $this->val->{$typeOfValidator}(self::$postData[$this->currentItem], $arg);
        
        if ($error)
        $this->error[$this->errorCount++] = $error;
        array_unique ($this->error);
        
        return $this;
    }
    
    /**
     * submit - Handles the form, and throws an exception upon error.
     * 
     * @return boolean
     * 
     * @throws Exception 
     */
    public function submit()
    {
        if (empty($this->error)) 
        {
            return true;
        } 
        else 
        {
            $str = '';
            foreach ($this->error as $key => $value)
            {
                $str .= $key . ' => ' . $value . "<br>";
            }
            $str = '<div style="status_ok">'.$str.'</div>';
            throw new Exception($str);
        }
    }
}