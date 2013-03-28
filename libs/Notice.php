<?php

/**
 * Description of notice
 *
 * @author Steven
 * @return string
 * 
 * object for the status massages
 * in the global template
 */
class Notice {
  
    public static $globalAlert = '';
    public static $contentAlert = ''; 
    
    public function __construct() {
    }
//----------------------------------------------------------------------------------------------------------------------    
    public static function getNewModulErr()
    {
        self::$contentAlert = '<div class="status_error">'.NEW_MODUL_ERR.'</div>';
    }

//---------------------------------------------------------------------------------------------------------------------- 
    
    public static function getLoginErr()
    {
        self::$contentAlert = LOGIN_ERR;
    }
 
//---------------------------------------------------------------------------------------------------------------------- 
    
    public static function getInactiveAccountErr()
    {
        self::$contentAlert = INACTIV_ACCOUNT;
    }

//----------------------------------------------------------------------------------------------------------------------     
    
    public static function getPageAccessErr()
    {
        self::$globalAlert = '<div class="status_error">'.PAGE_ACCESS_ERR.'</div>';
    }  
 
//----------------------------------------------------------------------------------------------------------------------    
    public static function getGlobalErr()
    {
        self::$globalAlert = '<div class="status_error">'.GLOBAL_PAGE_ERR.'</div>';
    }  
//---------------------------------------------------------------------------------------------------------------------   
    public static function alertServiceMode()
    {
        self::$contentAlert = LOGIN_SMODE_ALERT;
    } 

//---------------------------------------------------------------------------------------------------------------------     
    public static function getGlobalServiceMode()
    {
        self::$globalAlert = '<div class="status_error">'.GLOBAL_SMODE_ALERT.'</div>';
    }
//---------------------------------------------------------------------------------------------------------------------    
    public static function alertSubscribeSucc($_modulNr)
    {
        self::$contentAlert = '<div class="status_ok">'.MOD_SUBSCRIBE_SUCC.' </div>';
    }

//---------------------------------------------------------------------------------------------------------------------    
    public static function alertSubscribeExamSucc($_modulNr)
    {
        self::$contentAlert = '<div class="status_ok">'.EXAM_SUBSCRIBE_SUCC.' </div>';
    }
    
    
//---------------------------------------------------------------------------------------------------------------------    
    public static function alertSubscribeErr()
    {
        self::$contentAlert = '<div class="status_error">'.MOD_SUBSCRIBE_ERR.'</div>';
    }

//---------------------------------------------------------------------------------------------------------------------    
    public static function alertUnsubscribeSucc()
    {
        self::$contentAlert = '<div class="status_ok">'.MOD_UNSUBSCRIBE_SUCC.'</div>';
    }
 
//---------------------------------------------------------------------------------------------------------------------    
    public static function alertUnsubscribeExamSucc()
    {
        self::$contentAlert = '<div class="status_ok">'.EXAM_UNSUBSCRIBE_SUCC.'</div>';
    }
        
 //---------------------------------------------------------------------------------------------------------------------    
    public static function alertUnsubscribeExamErr()
    {
        self::$contentAlert = '<div class="status_error">'.EXAM_UNSUBSCRIBE_ERR.'</div>';
    }
    
    
//---------------------------------------------------------------------------------------------------------------------    
    public static function alertAlreadySubscribed()
    {
        self::$contentAlert = '<div class="status_error">'.MOD_ALREADY_SUBSCRIBED.'</div>';
    }
//---------------------------------------------------------------------------------------------------------------------    
    public static function alertModSubscribeAccessErr()
    {
        self::$contentAlert = '<div class="status_error">'.MOD_SUBSCRIBE_ACCESS_ERR.'</div>';
    }

//---------------------------------------------------------------------------------------------------------------------    
    public static function alertModActSucc()
    {
        self::$contentAlert = '<div class="status_ok">'.MOD_ACT_SUCC.'</div>';
    }
    
//---------------------------------------------------------------------------------------------------------------------    
    public static function alertModActNoSub()
    {
        self::$contentAlert = '<div class="status_error">'.MOD_ACT_NOSUB_ERR.'</div>';
    }

//---------------------------------------------------------------------------------------------------------------------    
    public static function alertModActErr($_modulNr)
    {
        self::$contentAlert = '<div class="status_error">'.MOD_ACT_ERR.$_modulNr.'</div>';
    }

//---------------------------------------------------------------------------------------------------------------------    
    public static function alertModActAccessErr()
    {
        self::$contentAlert = '<div class="status_error">'.MOD_ACT_ACCESS_ERR.'</div>';
    }
     
//---------------------------------------------------------------------------------------------------------------------    
    public static function alertModDelAccessErr()
    {
        self::$contentAlert = '<div class="status_error">'.MOD_DEL_ACCESS_ERR.'</div>';
    }

//---------------------------------------------------------------------------------------------------------------------    
    public static function alertModDelSucc()
    {
        self::$contentAlert = '<div class="status_ok">'.MOD_DEL_SUCC.'</div>';
    }


//---------------------------------------------------------------------------------------------------------------------    
    public static function alertModDeactSucc()
    {
        self::$contentAlert = '<div class="status_ok">'.MOD_DEACT_SUCC.'</div>';
    }

       
}

?>
