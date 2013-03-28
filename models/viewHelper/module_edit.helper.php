<?php

class ModuleEditViewHelper
{

    protected $db;
    private $dpload;

    function __construct()
    {
        $this->dpload = DPLoad::getInstance();
        $this->db = Database::getInstance();
        Session::init();
    }

//----------------------------------------------------------------------------------------------------------------------

    public function execute($_args)
    {
        switch ($_args[0])
        {

            case deleteMod:
                return $this->deleteMod($_args[1]);
                break;
            default:
                echo "Coud not find the details";
                return false;
        }
    }

//Funktion zum Loeschen von Modulen-------------------------------------------------------------------------------------
    public function deleteMod($_moduleNr)
    {

        if ($this->dpload->isAccessAllowed(zugriff_modloeschen))
        {
            $datetime = date("Y-m-d H:i:s", time());
            $accountid = Session::get('accountId');

            $queryMod = "SELECT l.modul_modulnr as modnr, l.teilmodul_modulnr, m.aktiv as aktiv FROM lehrveranstaltung l
	                    JOIN modul m ON m.modulnr = l.modul_modulnr
	                   WHERE l.modul_modulnr = '$_moduleNr' OR l.teilmodul_modulnr = '$_moduleNr'";

            $res = $this->db->select($queryMod);
            $row = $res[0];

            //Wenn zu l&#271;&#380;&#733;schendes Modul = Hauptmodul 
            if ($_moduleNr == $row['modnr'])
            {

                $query = $this->db->delete("modul", "modulnr = '$_moduleNr'");
                $query = $this->db->delete("teilmodul", "modul_modulnr = '$_moduleNr'");
                $query = $this->db->delete("lehrveranstaltung", "modul_modulnr ='$_moduleNr'");

                $dataArray = array(geloescht => $datetime, account_id => $accountid);

                $query = $this->db->update("log", $dataArray, "modul_modulnr = '$_moduleNr'");

                if ($query)
                    Notice::alertModDelSucc();
            }
            else
            {
                $query = $this->db->delete("teilmodul", "modulnr = '$_moduleNr'");
                $query = $this->db->delete("lehrveranstaltung", "teilmodul_modulnr = '$_moduleNr'");

                $dataArray = array(geloescht => $datetime, account_id => $accountid);

                $query = $this->db->update("log", $dataArray, "teilmodul_modulnr = '$_moduleNr'");

                if ($query)
                    Notice::alertModDelSucc();
            }
        }
        else
        {
            Notice::alertModDelAccessErr();
        }
    }

//Funktion zum deaktivieren von Modul--------------------------------------------------------------------------------------------------- 
    public function activateMod($_moduleNr)
    {
//Pr&#271;&#380;&#733;fen ob Rechte f&#271;&#380;&#733;r Aktivierung vorhanden
        if ($this->dpload->isAccessAllowed(zugriff_modaktivieren))
        {

            $queryMod = "SELECT l.modul_modulnr as modnr, l.teilmodul_modulnr, m.aktiv as aktiv FROM lehrveranstaltung l
	                      JOIN modul m ON m.modulnr = l.modul_modulnr
	               WHERE l.modul_modulnr = '$_moduleNr' OR l.teilmodul_modulnr = '$_moduleNr'";

            $res = $this->db->select($queryMod);
            $row = $res[0];

            //Wenn Hauptmodul aktiviert, oder Parameter ist Hauptmodul
            if ($row['aktiv'] || ($row['modnr'] == $_moduleNr))
            {
                //Pr&#271;&#380;&#733;fen ob f&#271;&#380;&#733;r Modul, Teilmodul ben&#271;&#380;&#733;tigt wird
                if ($row['teilmodul_modulnr'] != 0)
                {
                    //Wenn modulnr = Art Modul 
                    if ($row['modnr'] == $_moduleNr)
                    {
                        $query = $this->db->update("modul", array(aktiv => 1), "modulnr = '$_moduleNr'");
                    }
                    else
                    {
                        $query = $this->db->update("teilmodul", array(aktiv => 1), "modulnr = '$_moduleNr'");
                    }

                    if ($query)
                        Notice::alertModActSucc();
                }
                else
                {
                    Notice::alertModActNoSub();
                }
            }
            else
            {
                Notice::alertModActErr($row['modnr']);
            }
        }
        else
        {
            Notice::alertModActAccessErr();
        }
    }

//Funktion zum deaktivieren von Modul-------------------------------------------------------------------------------------------------
    public function deactivateMod($_moduleNr)
    {

        $queryMod = "SELECT modul_modulnr, teilmodul_modulnr FROM lehrveranstaltung 
		               WHERE modul_modulnr = '$_moduleNr' OR teilmodul_modulnr = '$_moduleNr'";

        $res = $this->db->select($queryMod);

        $row = $res[0];

        //Wenn modulnr = Art Modul 
        if ($row['modul_modulnr'] == $_moduleNr)
        {
            $query = $this->db->update("modul", array(aktiv => 0), "modulnr = '$_moduleNr'");
        }
        else
        {
            $query = $this->db->update("teilmodul", array(aktiv => 0), "modulnr = '$_moduleNr'");
        }

        if ($query)
            Notice::alertModDeactSucc();
    }

}

?>
