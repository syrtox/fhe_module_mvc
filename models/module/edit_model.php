<?php

class ModuleEdit_Model extends DPLoad
{

    public function __construct()
    {
        parent::__construct();
    }

//---------------------------------------------------------------------------------------------------------------------    

    public function run()
    {
        try
        {
            $form = new Form();

            //sammle die posts aus dem Request
            if ($_REQUEST['modType'] != 'tmod')
            {
                $_POST['tmoduleNr'] = -1;
                $_POST['resposible'] = -1;
                $_POST['major'] = 0;
            }

            //Wenn Modul mit Teilmodul
            if ($_REQUEST['modType'] == 'modt')
            {
                //Arrays der Checkboxen
                $_POST['readingLoc'] = array();
                $_POST['readingType'] = array();
                $_POST['regularity'] = 1;
                $_POST['language'] = array();
                $_POST['lecturer'] = array();
                $_POST['content'] = 'none';
                $_POST['targets'] = 'none';
                $_POST['examType'] = 'none';
                $_POST['examTypeRep'] = 'none';
                $_POST['requiredForExam'] = 'none';
                $_POST['weightMark'] = 'none';
                $_POST['performance'] = '1';
            }

            //sammeln und validieren der GET'S
            $form->post('moduleNr')
                    ->val('checkModNr', 2)
                    ->post('tmoduleNr')
                    ->val('checkModNr', 2)
                    ->post('modType')

                    //Allgemeine Informationen
                    ->post('modDescrb')
                    ->val('minlenght', 2)
                    ->post('statusid')
                    ->val('isNotEmpty')
                    ->post('major')
                    ->val('isNotEmpty')
                    ->post('requiredForMod')
                    ->post('isRequired')
                    ->post('niveau')
                    ->post('responsible')
                    ->val('isNotEmpty')
                    ->post('regist')
                    ->val('isNotEmpty')
                    ->post('subscriber')
                    ->post('areaOfSpec')
                    //Arrays der Checkboxen
                    ->post('readingLoc', 1)
                    ->val('isNotEmpty')
                    ->post('readingType', 1)
                    ->val('isNotEmpty')
                    ->post('regularity')
                    ->val('isNotEmpty')
                    ->post('language', 1)
                    ->val('isNotEmpty')
                    ->post('lecturer')
                    ->val('isNotEmpty')

                    // Informationen Dauer und Angebot
                    ->post('regularSem')
                    ->val('isNotEmpty')
                    ->post('pz')
                    ->val('isNotEmpty', 1)
                    ->post('vz')
                    ->val('isNotEmpty', 1)
                    ->post('sz')
                    ->val('isNotEmpty', 1)
                    ->post('cp')
                    ->val('isNotEmpty')
                    ->post('sws')
                    ->val('isNotEmpty')
                    ->post('usability')

                    //Informationen Inhalt
                    ->post('topic')
                    ->post('content')
                    ->val('minlenght', 3)
                    ->post('targets')
                    ->val('minlenght', 3)
                    ->post('methodes')

                    //Informationen Pruefung
                    ->post('examType')
                    ->val('minlenght', 3)
                    ->post('examTypeRep')
                    ->val('minlenght', 3)
                    ->post('requiredForExam')
                    ->val('minlenght', 3)
                    ->post('performance')
                    ->val('isNotEmpty')
                    ->post('weightMark')
                    ->val('isNotEmpty');

            //Wenn submit und formular valide
            if ($form->submit())
            {
                if (!isset($_REQUEST['module']))
                {
                    //wenn Modul erfolgreich erstellt 
                    $this->createModule($form->fetch());
                }
                //fuege Moduldetails ein
                $this->saveModuleDetails($form->fetch());
            }
        }
        catch (Exception $e)
        {
            return false;
        }
    }

//----------------------------------------------------------------------------------------------------------------------

    private function createModule(&$_dataArray)
    {
        $datetime = date("Y-m-d H:i:s", time());
       //Puefen ob vorhanden          
        if ($_dataArray['modType'] == 'mod' || $_dataArray['modType'] == 'modt')
        {
            $query = $this->db->select("SELECT modulnr FROM modul WHERE modulnr = '" . $_dataArray['moduleNr'] . "'");

            if ($this->db->numRows)
            {
                Notice::alertModAlreadyExist();
                throw new Exception(Notice::$contentAlert);
            }
            else
            {
                $dataArray = array(modulnr => $_dataArray['moduleNr'],
                                   mitarbeiter_persnr  => $_dataArray['responsible']);
                //Modul mit modulnr anlegen
                $query = $this->db->insert("modul",$dataArray );
            }
        }
        else //Wenn Teilmodul
        {
            //Abfragen ob Modul fuer Teilmodul schon vorhanden
            $query = $this->db->select("SELECT bezeichnung FROM modul WHERE modulnr = '".$_dataArray['moduleNr']."'");

            //Wenn nicht
            if ($this->db->numRows == 0)
            {
                $massage = 'Fehler: Modul ' . $_dataArray['moduleNr'] . '
                            nicht vorhanden<br> Bitte legen Sie erst das Modul an!';
                throw new Exception(Notice::alertModMissingErr());
            }
            else
            {
                //Pruefen ob Teilmodul vorhanden
                $query = "SELECT modulnr FROM teilmodul WHERE modulnr = '".$_dataArray['tmoduleNr']."'";
                
                $query = $this->db->select($query);

                if ($this->db->numRows)
                {
                    $massage = 'Fehler: Teilmodul ' . $_dataArray['tmoduleNr'] . ' schon vorhanden<br>';
                    throw new Exception(Notice::alertModMissingErr());
                }
                else
                {
                    $dataArray =  array(modulnr => $_dataArray['tmoduleNr'],
                                        modul_modulnr => $_dataArray['moduleNr']);
                            
                    $query = $this->db->insert("teilmodul",$dataArray);
                }
            }
        }

        $dataArray = array(modul_modulnr => $_dataArray['moduleNr'],
                           teilmodul_modulnr => $_dataArray['tmoduleNr'],
                           studiengang_id => $_dataArray['major']);

        $query = $this->db->insert("lehrveranstaltung", $dataArray);

        if (!$query)
        {
            $dataArray = array(erstellt => $datetime,
                               geaendert => 0,
                               geloescht => 0,
                               account_id => Session::get('accountId'),
                               modul_modulnr => $_dataArray['moduleNr'],
                               teilmodul_modulnr => $_dataArray['tmoduleNr']);

            //Erstelltes Modul eintragen in Log
            $query = $this->db->insert("log", $dataArray);

            if ($query)
            {
                Notice::alertModCreateSucc();
                return true;
            }
        }
    }
//----------------------------------------------------------------------------------------------------------------------

    public function saveModuleDetails(&$_dataArray)
    {
        $datetime = date("Y-m-d H:i:s", time());
        //Wenn Modul           
        if ($_dataArray['modType'] == 'mod' || $_dataArray['modType'] == 'modt')
        {

            $updateArray = array(modulNr => $_dataArray['moduleNr'],
                                bezeichnung => $_dataArray['modDescrb'],
                                aktiv => 0,
                                voraussetzung => $_dataArray['requiredForMod'],
                                voraussetzungFuer => $_dataArray['isRequired'],
                                wichtungGesamtnote => $_dataArray['weightMark'],
                                mitarbeiter_persnr => $_dataArray['responsible'],
                                status_id => $_dataArray['statusid']);

            $query = $this->db->update("modul", $updateArray, "modulnr ='" . $_dataArray['moduleNr'] . "'");
        }       
        else //Wenn Teilmodul
        {
            $updateArray = array(bezeichnung => $_dataArray['modDescrb'],
                                aktiv => 0,
                                voraussetzung => $_dataArray['requiredForMod'],
                                voraussetzungFuer => $_dataArray['isRequired'],
                                pruefungsart => $_dataArray['examType'],
                                gewichtungNote => $_dataArray['weightMark'],
                                status_id => $_dataArray['statusid']);

            $query = $this->db->update("teilmodul", $updateArray, "modulnr ='" . $_dataArray['tmoduleNr'] . "'");
        }
        //Wenn Modul oder Teilmodul und kein Fehler
        if (($_dataArray['modType'] == 'mod' || $_dataArray['modType'] == 'tmod') && $query)
        {

//               $query = "SELECT id FROM lehrveranstaltung WHERE modul_modulnr = '$modNr' && teilmodul_modulnr = 0";
//                                   
//               $querydummy = $this->db->select($query);
//               
//               //Wenn erstes Teilmodul für Modul, ueberschreibe dummy
//               if(mysql_num_rows($querydummy))
//               {
//                    $querydummy = catchMysqlQuery("UPDATE lehrveranstaltung SET teilmodul_modulnr = '$tmodNr' 
//                                                   WHERE modul_modulnr = $modNr && teilmodul_modulnr = 0");
//               }
            //Update Daten der Lehrveranstaltung

            $dataArray = array(veranstaltungsort => $_dataArray['readingLoc'],
                status_id => $_dataArray['statusid'],
                ziele => $_dataArray['targets'],
                inhalte => $_dataArray['inhalt'],
                methoden => $_dataArray['methodes'],
                pruefungsVoraussetzung => $_dataArray['requiredForExam'],
                leistung => $_dataArray['performance'],
                thema => $_dataArray['topic'],
                pruefungsart => $_dataArray['examType'],
                wdhpruefungsart => $_dataArray['examTypeRep'],
                typ => $_dataArray['readingType'],
                regelsemester => $_dataArray['regularSem'],
                angebot => $_dataArray['regularity'],
                ects => $_dataArray['cp'],
                sws => $_dataArray['sws'],
                studiengang_id => $_dataArray['studyPath'],
                verwendbarkeit => $_dataArray['usability'],
                teilnehmer => $_dataArray['subscriber'],
                anmeldung => $_dataArray['regist'],
                sprache => $_dataArray['language'],
                praesenszeit => $_dataArray['pz'],
                vorbereitungszeit => $_dataArray['vz'],
                selbststudienzeit => $_dataArray['sz'],
                gewichtungNote => $_dataArray['weightMark'],
                niveaustufe => $_dataArray['niveau'],
                vertiefung_id => $_dataArray['areaOfSpec']);

            $where = "modul_modulnr = '" . $_dataArray['tmoduleNr'] . "' 
                               && teilmodul_modulnr = '" . $_dataArray['moduleNr'] . "'";

            $query = $this->db->update("lehrveranstaltung", $dataArray, $where);

            if ($query)
            {
                $queryCourse = "SELECT id FROM lehrveranstaltung WHERE modul_modulnr = 
	                          '".$_dataArray['moduleNr']." && teilmodul_modulnr = '".$_dataArray['$tmoduleNr']."'";

                //abfragen lvid
                $resCourse = $this->db->select($queryCourse);
                $rowCourse = $resCourse[0];

                //Aktuelle Zuordnung der Lehrenden loeschen        
                $query = $this->db->delete("lehrender", "lehrveranstaltung_lvNr = '$rowCourse[lvid]'");

                if ($query)
                {
                    //Setze Zaehler fuer Dozent-Array          
                    $nofLec = count($_dataArray['lecturer']) - 1;

                    //bis alle Dozenten eingetragen
                    while ($nofLec)
                    {

                        $dataArray = array(lehrveranstaltung_lvNr => $rowCourse[lvid],
                                           mitarbeiter_persNr => $_dataArray['lecturer'][$nofLec]);

                        $query = $this->db->insert("lehrender", $dataArray);
                        $nofLec--;
                    }
                }
                if ($query)
                {
                    $dataArray = array(geaendert => $datetime,
                                       account_id => Session::get('accountId'));
                    
                    $where = "WHERE modul_modulnr = '".$_dataArray['moduleNr']."' 
                              AND teilmodul_modulnr = '".$_dataArray['tmoduleNr']."'";
                    
                    //aenderung ins Log eintragen
                    $query = $this->db->update("log",$dataArray,$where);  

                    if ($query)
                        Notice::alertModSavedSucc();
                }
            }
        }
    }
//----------------------------------------------------------------------------------------------------------------------

    public function edit($_moduleNr)
    {
        if (!empty($_moduleNr))
        {
            echo $_moduleNr;
            //Wenn Recht fur Bearbeiten Module eigner Fakult&#258;ï¿½t vorhanden
            if ($this->isAccessAllowed(zugriff_modbearbeiten) && !$this->isAccessAllowed(zugriff_modadmin))
            {

                $res = $this->db->select("SELECT l.id, m.fakultaet_id FROM lehrveranstaltung l JOIN studiengang s 
	                              ON s.id = l.studiengang_id JOIN fachrichtung fr ON fr.id =
	                              s.fachrichtung_id JOIN fakultaet f ON f.id = fr.fakultaet_id
	                              JOIN mitarbeiter m ON m.fakultaet_id = f.id
	                              WHERE m.account_id =" . Session::get('accountId') . " 
	                              AND l.modul_modulnr = '" . $_moduleNr . "' 
	                              OR l.teilmodul_modulnr = '" . $_moduleNr . "'");

                if (!$this->db->numRows)
                {
                    Notice::alertModActAccessErr();
                    exit();
                }
            }
            //Wenn nicht und kein Adminrecht
            else if (!$this->isAccessAllowed(zugriff_modadmin))
            {
                //Pr&#258;&#378;fe ob aktueller Account verantwortlich f&#258;&#378;r zu bearbeitendes Modul
                $res = $this->db->select("SELECT l.id  FROM lehrveranstaltung l 
                                    JOIN modul m ON m.modulnr = l.modul_modulnr
                                    JOIN mitarbeiter ma ON ma.persnr = m.mitarbeiter_persnr
	                              WHERE ma.account_id =" . Session::get('accountId') . " 
	                              AND l.modul_modulnr = '" . $_moduleNr . "' 
	                              OR l.teilmodul_modulnr = '" . $_moduleNr . "' 
	                              AND ma.account_id =" . Session::get('accountId'));

                if (!$this->db->numRows)
                {
                    Notice::alertModActAccessErr();
                    echo '<p class="status_error">Sie haben keine Berechtigung f&uuml;r die Bearbeitung an diesem Modul</p>';
                    exit();
                }
            }

            //Starte Abfrage und finde passendes Modul
            $query = " SELECT m.modulNr, m.bezeichnung, m.aktiv, l.leistung ,m.voraussetzung,
                          m.voraussetzungfuer,m.wichtungGesamtnote,m.mitarbeiter_persnr,m.status_id,
                          l.teilmodul_modulNr, t.bezeichnung, t.leistung ,t.voraussetzung, t.leistung,
                          t.voraussetzungfuer,t.gewichtungNote,t.status_id,
                          l.veranstaltungsort,l.status_id, l.ziele, l.inhalte, l.methoden,
	                    l.pruefungsVoraussetzung, l.thema, l.pruefungsart, l.wdhpruefungsart, 
	                    l.typ, l.studiengang_id, l.regelsemester, l.angebot, l.ects, l.sws, l.verwendbarkeit, 
                          l.teilnehmer,  l.anmeldung, l.sprache, l.praesenszeit, l.vorbereitungszeit,
                          l.selbststudienzeit, l.gewichtungNote, l.id, m.aktiv, l.niveaustufe	                     		      
		      FROM lehrveranstaltung l LEFT JOIN modul m ON l.modul_modulnr = m.modulnr
		                               LEFT JOIN teilmodul t ON l.teilmodul_modulnr = t.modulnr
		      WHERE l.modul_modulnr = '$_moduleNr' OR l.teilmodul_modulnr = '$_moduleNr'";

            $res = $this->db->select($query,"mysql_fetch_row");
            $module = $res[0];

            //Wenn Modul ohne Teilmodul  
            if (($module[0] == $_moduleNr) && $module[9] == -1)
            {
                //Setze Modulart und deaktivere andere Arten
                $modType = 'mod';
                $tmodStatus = 'disabled';
                $modStatus = 'disabled';
            }
            //Wenn Teilmodul
            else if ($module[9] == $_moduleNr)
            {
                $modType = 'tmod';
                $modStatus = 'disabled';
                $modStatus = 'disabled';
                $responsible = $module[7];
            }
            //Wenn Modul mit Teilmodul
            else
            {
                $modType = 'modt';
                $tmodStatus = 'disabled';
                $modStatus = 'disabled';
            }
 
           //Zuweisen der abgefragten Attribute je nach Bedingung
            Form::$postData['modType'] = $modType;
            Form::$postData['moduleNr'] = $module[0];
            Form::$postData['tmoduleNr'] = ($module[0] == $_moduleNr) ? '' : $module[9];
            Form::$postData['modDescrb'] = ($module[0] == $_moduleNr) ? $module[1] : $module[10];
            Form::$postData['performance'] = $module[3];
            Form::$postData['requiredForMod'] = ($module[0] == $_moduleNr) ? $module[4] : $module[12];
            Form::$postData['isRequired'] = ($module[0] == $_moduleNr) ? $module[5] : $module[13];
            Form::$postData['responsible'] = $module[7]; //MITARBEITER VERANTWORTLICH
            Form::$postData['status'] = ($module[0] == $_moduleNr) ? $module[8] : $module[16];
            Form::$postData['weightMark'] = ($module[0] == $_moduleNr) ? $module[6] : $module[15];
            //Array aus String wiederherstellen      
            Form::$postData['readingLoc'] = explode(', ', $module[17]);
            Form::$postData['targets'] = $module[19];
            Form::$postData['content'] = $module[20];
            Form::$postData['methodes'] = $module[21];
            Form::$postData['requiredForExam'] = $module[22];
            Form::$postData['topic'] = $module[23];
            Form::$postData['examType'] = $module[24];
            Form::$postData['examTypeRep'] = $module[25];
            Form::$postData['readingType'] = explode(', ', $module[26]);
            Form::$postData['major'] = $module[27];
            Form::$postData['regularSem'] = $module[28];
            Form::$postData['regularity'] = explode(', ', $module[29]);
            Form::$postData['cp'] = $module[30];
            Form::$postData['sws'] = $module[31];
            Form::$postData['usability'] = $module[32];
            Form::$postData['subscriber'] = ($module[33] > 0) ? $module[33] : 0;
            Form::$postData['regist'] = $module[34];
            Form::$postData['language'] = explode(', ', $module[35]);
            Form::$postData['pz'] = $module[36];
            Form::$postData['vz'] = $module[37];
            Form::$postData['sz'] = $module[38];
            Form::$postData['niveau'] = $module[42];

            //Abfragen der Dozenten
            $query = "SELECT mitarbeiter_persNr FROM lehrender WHERE lehrveranstaltung_lvNr = $module[40]";

            $res = $this->db->select($query, "mysql_fetch_row");

            //Einlesen der Dozenten    
            foreach ($res[0] as $row)
            {
                $lecturerArray[] = $row[0];
            }

           Form::$postData['lecturer'] = $lecturerArray;
        }
        else
        {
            echo "no ID!";
        }
    }
    
//----------------------------------------------------------------------------------------------------------------------
    
    public function getModuleLogInfo($_moduleNr,$_tmoduleNr)
    {        

            //Abfragen Werte aus Log-Tabelle
            $res = $this->db->select("SELECT date_format(l.erstellt, '%d.%m.%y') as erstellt,  
					             date_format(l.geaendert, '%d.%m.%y') as geaendert, 
					             CONCAT(akadTitel,' ', nachname) as pers
					       FROM log l JOIN account a ON a.id = l.account_id
					       JOIN mitarbeiter m ON m.account_id = a.id
					       WHERE l.modul_modulnr = '" . $_moduleNr . "'
					       AND l.teilmodul_modulnr = '" . $_tmoduleNr . "'");

            $log = $res[0];

            $output .= '<div style="float:left; font-size:8pt;margin-bottom:20px;">
                        <span >Modul erstellt am: ' . $log['erstellt'] . '<br>zuletzt ge&auml;ndert 
                        am: ' . $log['geaendert'] . '</span><br>
                        <strong>ge&auml;ndert von:&nbsp; ' . $log['pers'] . '</strong></div>';
            
            return $output;
        }
    }
?>
