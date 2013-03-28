<?php

class UniversityViewHelper
{

    private $db;

    private $dpload;
    
    function __construct()
    {
        $this->db = Database::getInstance();
        $this->dpload = DPLoad::getInstance();
        Session::init();
    }

//----------------------------------------------------------------------------------------------------------------------

    public function execute($_args)
    {
        switch ($_args[0])
        {
            case getFacultiesAsDrop:
                return $this->getFacultiesAsDrop();
                break;
            case getSubjectsAsDrop:
                return $this->getSubjectsAsDrop();
            case getMajorsAsDrop:
                return $this->getMajorsAsDrop();
                break;
            case getAreasOfSpec:
                return $this->getAreasOfSpecAsDrop();
                break;
            case getLecturerAsDrop:
                return $this->getLecturerAsDrop();
                break;
            case getModuleOverview:
                return $this->getModuleOverview();
                break;
            case getModuleMajorTable:
                return $this->getModuleMajorTable();
                break;
            default:
                echo "Coud not find the details";
                return false;
        }
    }

//---------------------------------------------------------------------------------------------------------------------

    public function getSubjectsAsDrop()
    {
        
       //Wenn Modul Administration
        if (Bootstrap::$currentSID === 28)
        {
            if ($this->dpload->isAccessAllowed(zugriff_modadmin))
            {
                $query = "SELECT fr.id, fr.bezeichnung FROM fachrichtung fr WHERE fr.id > 0 ORDER BY 1";

                $res = $this->db->select($query, "mysql_fetch_row");
            }
            else if ($this->dpload->isAccessAllowed(zugriff_modanzeigen))
            { //Abfragen aller Fachrichtungen der Fakultät des Verantwortlichen
                $query = "SELECT fr.id, fr.bezeichnung FROM fachrichtung fr
                                    JOIN fakultaet f ON f.id = fr.fakultaet_id 
                                    JOIN mitarbeiter m ON m.fakultaet_id = f.id		          	                                 
                                    WHERE fr.id > 0 AND m.account_id =" . $_SESSION['accountId'] . "
                                    ORDER BY 1";

                $res = $this->db->select($query, "mysql_fetch_row");
            }
        }
        else
        {
            $query = "SELECT fr.id, fr.bezeichnung FROM fachrichtung fr WHERE fr.id > 0 ORDER BY 1";

            $res = $this->db->select($query, "mysql_fetch_row");
        }
        $output = $output . '<option value="0">---alle anzeigen---</option>';


        if ($this->db->numRows)
        {
            foreach ($res as $row)
            {
                $output = $output . '<option value="' . $row[0] . '">' . $row[1] . '</option>';
            }
        }
        return $output;
    }

//---------------------------------------------------------------------------------------------------------------------

    public function getAreaOfSpecAsDrop($_majorID = NULL)
    {
        if (isset($_majorID))
        {
            $query = "SELECT id, bezeichnung FROM vertiefung WHERE studiengang_id = '" . $_majorID . "'";
        }
        else
        {
            $query = "SELECT id, bezeichnung FROM vertiefung WHERE id > 0";
        }
        
        $res = $this->db->select($query);
        $i = 1;

        $output .= '<option value="0">---alle anzeigen---</option>';

        if ($this->db->numRows > 0)
        {
            foreach ($res as $row)
            {
                $output .= '<option value="' . $row[id] . '">' . $row[bezeichnung] . '</option>';
                $i++;
            }
        }
        return $output;
    }

//---------------------------------------------------------------------------------------------------------------------

    public function getMajorsAsDrop($_subjectID = NULL, $_current = 0)
    {
        if (isset($_subjectID))
        {
            $query = "SELECT id, bezeichnung FROM studiengang WHERE fachrichtung_id = '" . $_subjectID . "'";
        }
        else
        {
            $query = "SELECT id, bezeichnung FROM studiengang WHERE id > 0";
        }

        $res = $this->db->select($query);

        $output .= '<option value="0">---alle anzeigen---</option>';

        if ($this->db->numRows > 0)
        {
            foreach ($res as $row)
            {
                $output .= '<option value="' . $row[id] . '">' . $row[bezeichnung] . '</option>';
            }
        }
        return $output;
    }

//---------------------------------------------------------------------------------------------------------------------

    public function getLecturerAsDrop($_arg = null)
    {
        
        $subject = (isset($_arg)) ? 'JOIN fachrichtung f ON f.fakultaet_id = m.fakultaet_id WHERE  f.id = '.$_arg : '';
        
        if ($this->dpload->isAccessAllowed(zugriff_modadmin))
        {
            $queryLecturer = "SELECT persnr,  concat(akadTitel,' ',nachname) FROM mitarbeiter m ".$subject." 
                                     WHERE ist_dozent = 1 ORDER BY nachname";
            
            $output .= '<option value="0">---alle anzeigen---</option>';

        }
        else if ($this->dpload->isAccessAllowed(zugriff_modanzeigen))
        {
            $queryLecturer = "SELECT persnr,  concat(akadTitel,' ',nachname) FROM mitarbeiter m WHERE fakultaet_id =
                                         (SELECT fakultaet_id FROM mitarbeiter WHERE account_id = 
                                           " . Session::get('accountId') . ") AND ist_dozent = 1 ORDER BY nachname";
            
            $output .= '<option value="0">---alle anzeigen---</option>';
        }
        else
        {
            $queryLecturer = "SELECT persnr,  concat(akadTitel,' ',nachname) FROM mitarbeiter m
                              WHERE account_id =" . Session::get('accountId');
        }
        
        $resLecturer = $this->db->select($queryLecturer,"mysql_fetch_row");

        if ($this->db->numRows)
        {
            foreach ($resLecturer as $rowLecturer)
            {
                $output .= '<option value="' . $rowLecturer[0] . '">' . $rowLecturer[1] . '</option>';
            }

            return $output;
        }
        return false;
    }

}

?>