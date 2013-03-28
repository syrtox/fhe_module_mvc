<?php

class DPLoad
{

    protected $db;
    private static $instance;

    function __construct()
    {
        $this->db = Database::getInstance();
        Session::init();
    }
    
    //Singleton: check for an instance od this class
    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

//----------------------------------------------------------------------------------------------------------------------

    public function viewPageMenu($_pageID = 0)
    {

        $loggedIn = (bool) Session::get('loggedin');
        $showButton = true;
        $output = '';

        if ($loggedIn)
        {
            $viewString = 'eingeloggt_sichtbar';
        }
        else
        {
            $viewString = 'ausgeloggt_sichtbar';
        }



        if ($_pageID == 0)
        {

            $query = "SELECT id, bezeichnung_menue, dateilink FROM seiten 
                      WHERE $viewString = 1 AND im_menue_sichtbar = 1 AND ist_sub_id_von IS NULL 
                      ORDER BY reihenfolge_eingeloggt";
        }
        else
        {
            $query = "SELECT id, bezeichnung_menue, dateilink FROM seiten 
                      WHERE $viewString = 1 AND im_menue_sichtbar = 1 AND ist_sub_id_von = " . $_pageID . " 
                      ORDER BY reihenfolge_eingeloggt";
        }

        if ($_pageID != 0)
        {
            $output = '<ul>' . $output;
        }

        $activeID = $this->GetActiveMenuID();

        $result = $this->db->select($query);

        foreach ($result as $row)
        {
            if ($this->PageisAccessAllowed($row['id']))
            {
                $row['id'] == $activeID ? $isActive = 'class="active"' : $isActive = '';

                if ($this->getPageIDByFilename('module_overview_public.php') == $row['id'])
                {
                    if (empty($_SESSION['moduebersicht']))
                    {
                        $knownURL = "index.php?sid=" . $this->getPageIDByFilename('module_overview_public.php');
                    }
                    else
                    {
//                        $knownURL = $_SESSION['moduebersicht'];
                        $knownURL = "index.php?sid=" . $this->getPageIDByFilename('module_overview_public.php');
                    }
                }

                //Logout Button displayed manually
                if ($this->getPageIDByFilename('account_logout.php') == $row['id'])
                {
                    $showButton = false;
                }
                else
                {
                    $showButton = true;
                }

                if ($showButton)
                {
                    $output = $output . '<li>';

                    if ($row['dateilink'] == NULL)
                    {
                        $output = $output . '<a href="#" ' . $isActive . '>' . $row['bezeichnung_menue'] . '</a>';
                    }
                    else if (($this->getPageIDByFilename('module_overview_public.php') == $row['id']))
                    {
                        $output = $output . '<a href="'.$knownURL.'" '.$isActive.'>'.$row['bezeichnung_menue'].'</a>';
                    }
                    else
                    {
                        $output = $output . '<a href="index.php?sid=' . $row['id'] . '" ' . $isActive . '>'
                                . $row['bezeichnung_menue'] . '</a>';
                    }

                    $queryDropdown = "SELECT id FROM seiten WHERE $viewString = 1 AND im_menue_sichtbar = 1 
                                      AND ist_sub_id_von = " . $row['id'] . " ORDER BY reihenfolge_eingeloggt";

                    $resultDropdown = $this->db->select($queryDropdown);

//                    echo self::$count++.'<br>';
                    if ($this->db->numRows != 0)
                    {
                        $output = $output . $this->viewPageMenu($row['id']);
                    }
                    $output = $output . '</li>';
                }
            }
        }
        if ($_pageID != 0)
        {
            $output = $output . '</ul>';
        }

        return $output;
    }

//---------------------------------------------------------------------------------------------------------------------    
    protected function GetActiveMenuID()
    {

        $loggedIn = (bool) Session::get('loggedin');

        if (empty(Bootstrap::$currentSID))
        {

            if ($loggedIn)
            {
                $lastID = $this->GetDefaultForwardingPageID();
            }
            else
            {
                $lastID = $this->GetLoginPageID();
            }
        }
        else
        {
            $query = "SELECT id FROM seiten WHERE id = " . Bootstrap::$currentSID;
            $result = $this->db->select($query);

            if ($this->db->numRows == 0)
            {
                if ($loggedIn)
                {
                    //Weiterleitung auf default nach Login
                    $lastID = $this->GetDefaultForwardingPageID();
                }
                else
                {
                    $lastID = $this->GetLoginPageID();
                }
            }
            else
            {
                $firstTry = true;

                do
                {
                    if ($firstTry)
                    {
                        $query = "SELECT id, ist_sub_id_von FROM seiten WHERE id = " . Bootstrap::$currentSID;
                        $result = $this->db->select($query);
                        $row = $result[0];
                        $lastID = $row['id'];
                        $activeID = $row['ist_sub_id_von'];
                        $firstTry = false;
                    }
                    else
                    {
                        $query = "SELECT ist_sub_id_von FROM seiten WHERE id = " . $activeID;
                        $result = $this->db->select($query);
                        $row = $result[0];
                        $lastID = $activeID;
                        $activeID = $row['ist_sub_id_von'];
                    }
                }
                while (!is_null($activeID));
            }
        }

        return $lastID;
    }

//---------------------------------------------------------------------------------------------------------------------    
    protected function PageisAccessAllowed($_pageID)
    {
        Session::init();
        $accountID = (int) Session::get('accountId');
        $loggedIn = (bool) Session::get('loggedin');

        if ((empty($accountID) && $loggedIn ) OR empty($_pageID))
        {
            return false;
        }
        else
        {
            $query = "SELECT vollzugriff FROM seiten WHERE id = $_pageID";
            $result = $this->db->select($query);

            if ($this->db->numRows == 0)
            {
                return false;
            }
            else
            {

                $row = $result[0];
                if ($row['vollzugriff'] == 1)
                {
                    if ($this->PageViewable($_pageID))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }

            if (!$loggedIn)
            {
                return false;
            }
            else
            {
                $query = "SELECT gruppen_id FROM account WHERE id =" . $accountID;
                $result = $this->db->select($query);
                $row = $result[0];
                $groupID = $row['gruppen_id'];

                if ($groupID == "")
                {
                    unset($_SESSION);
                    return false;
                }

                $query = "SELECT * FROM account_hat_seitenzugriff WHERE account_id = $accountID AND seiten_id = $_pageID";
                $result = $this->db->select($query);

                if ($this->db->numRows == 0)
                {
                    $query = "SELECT * FROM gruppe_hat_seitenzugriff WHERE gruppen_id = $groupID AND seiten_id = $_pageID";
                    $result = $this->db->select($query);
                    if ($this->db->numRows == 0)
                    {
                        return false;
                    }
                    else
                    {
                        if ($this->PageViewable($_pageID))
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    }
                }
                else
                {
                    if ($this->PageViewable($_pageID))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
        }
    }

//---------------------------------------------------------------------------------------------------------------------

    protected function getPageIDByFilename($_filename)
    {
        if (empty($_filename))
        {
            return '';
        }

        $query = "SELECT id FROM seiten WHERE dateilink LIKE '%$_filename%'";
        $result = $this->db->select($query);
        $row = $result[0];
        return $row['id'];
    }

//---------------------------------------------------------------------------------------------------------------------
    protected function GetDefaultForwardingPageID()
    {
        $query = "SELECT id FROM seiten WHERE ist_standardseite = 1";
        $result = $this->db->select($query);
        $row = $result[0];
        return $row['id'];
    }

    //---------------------------------------------------------------------------------------------------------------------    
    protected function PageViewable($_pageID)
    {

        $loggedIn = (bool) Session::get('loggedin');

        if (empty($_pageID))
        {
            return false;
        }
        if ($loggedIn)
        {
            $query = "SELECT eingeloggt_sichtbar, ist_loginseite FROM seiten WHERE id = $_pageID";
            $result = $this->db->select($query);
            $row = $result[0];

            if ($row['eingeloggt_sichtbar'] || $row['ist_loginseite'])
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $query = "SELECT ausgeloggt_sichtbar FROM seiten WHERE id = $_pageID";
            $result = $this->db->select($query);
            $row = $result[0];

            if ($row['ausgeloggt_sichtbar'])
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

//---------------------------------------------------------------------------------------------------------------------
    protected function getLoginPageID()
    {
        $query = "SELECT id FROM seiten WHERE ist_loginseite = 1";
        $result = $this->db->select($query);
        $row = $result[0];
        return $row['id'];
    }

//----------------------------------------------------------------------------------------------------------------------

    protected function getPageIncludeByID($_pageID)
    {
        if (empty($_pageID))
        {
            return '';
        }

        $query = "SELECT dateilink FROM seiten_mvc WHERE id = $_pageID";
        $result = $this->db->select($query);
        $row = $result[0];
        return $row['dateilink'];
    }
//----------------------------------------------------------------------------------------------------------------------
    
    public function isAccessAllowed($_description)
    {
        $groupID = (int) Session::get('groupId');
        $accountID = (int) Session::get('accountId');

        $query = "SELECT id FROM rechte WHERE bezeichnung_intern = '" . $_description . "'";
        $result = $this->db->select($query,"mysql_fetch_array");

        if ($this->db->numRows == 0)
        {
            return false;
        }
        else
        {
            $row = $result[0];
            $accessID = $row['id'];

            $query = "SELECT rechte_id, gruppen_id FROM gruppe_hat_rechtezugriff 
                      WHERE gruppen_id = " . $groupID . " AND rechte_id = " . $accessID;
            $result = $this->db->select($query);

            if ($this->db->numRows == 0)
            {
                $query = "SELECT rechte_id, account_id FROM account_hat_rechtezugriff
                          WHERE account_id = " . $accountID . " AND rechte_id = " . $accessID;
                
                $result = $this->db->select($query);

                if ($this->db->numRows == 0)
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
            else
            {
                return true;
            }
        }

        return false;
    }

}

?>
