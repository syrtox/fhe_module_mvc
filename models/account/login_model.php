<?php

class AccountLogin_Model extends DPLoad
{

    public function __construct()
    {
        parent::__construct();
    }

//---------------------------------------------------------------------------------------------------------------------    
    public function doLogin()
    {
        // Maskierende Slashes aus POST Array entfernen
        if (get_magic_quotes_gpc())
        {
            $_POST = array_map('stripslashes', $_POST);
        }

        // Benutzereingabe umladen, von Leerzeichen befreien und 
        $username = strtolower(trim($_POST['username']));
        $password = md5(trim($_POST['password']));

        // Benutzereingabe mit User in der Datenbank vergleichen
        $query = "SELECT id, benutzername,cookieHash, gruppen_id, aktiv FROM account 
              WHERE benutzername = '" . mysql_real_escape_string($username) . "' 
              AND passwort ='" . mysql_real_escape_string($password) . "'";

        $cookie = $this->db->select($query);
        $usercookie = $cookie[0];

            //if the service mode set, and the user not an admin
            if (SERVICE_MODE && $usercookie['gruppen_id'] != 5)
            {
                Notice::alertServiceMode();
                
                return false;
            }
            else
            {
                if ($this->db->numRows == 0)
                {
                    //neue Fehlermeldung wenn kein Beutzer mit diesem Passwort
                    Notice::getLoginErr();
                    return false;
                }
                else
                {
                    // Stimmen die Benutzereingaben &#65533;berein, wurde 1 Datensatz gefunden
                    // Abfrageergebnis fetchen
                    if ($usercookie['aktiv'] != 1)
                    {
                        //fehlermeldung wenn Account inaktiv;
                        Notice::getInactiveAccountErr();
                        return false;
                    }
                    else
                    {
                        // Wenn die Anmeldung korrekt war Session Variable setzen,
                        // COOKIE an Browser schicken und auf die geheime Seite weiterleiten
                        Session::init();
                        Session::set('accountId', $usercookie['id']);
                        Session::set('loggedin', true);
                        Session::set('groupId', $usercookie['gruppen_id']);
                        Session::set('sessionId', session_id());
                        //Session::set('moduebersicht', 'index.php?sid=" . GetPageIDByFilename('module/module_overview_public.php');                
                        //TODO cookie objekt
                        //setcookie('userLogin', $usercookie['cookieHash'], time() + 600);
                        header('location: index.php?sid=' . $this->GetDefaultForwardingPageID());

                        return true;
                    }
                }
            }
        return false;
    }

}