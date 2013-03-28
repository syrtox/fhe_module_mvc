<?php


class ModuleDetailsViewHelper
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

            case getModDetailsHeader:
                return $this->getModDetailsHeader($_args[1]);
                break;
            default:
                echo "Coud not find the details";
                return false;
        }
    }

//----------------------------------------------------------------------------------------------------------------------

    public function getModDetailsHeader($_modulNr)
    {

        $queryModul = "SELECT m.modulNr, m.bezeichnung, t.modulNr, sum(l.ects) ,sum(l.sws) ,
		              s.bezeichnung, l.teilnehmer ,l.anmeldung,  l.leistung, t.bezeichnung, l.id
	               FROM lehrveranstaltung l LEFT JOIN modul m ON l.modul_modulnr = m.modulNr
	               LEFT JOIN teilmodul t ON l.teilmodul_modulnr = t.modulNr 	                                        
	                    JOIN status s ON m.status_id = s.id
	               WHERE l.modul_modulnr = '$_modulNr' OR l.teilmodul_modulnr = '$_modulNr'";

        $res = $this->db->select($queryModul,"mysql_fetch_row");
        $row = $res[0];

        //Anzahl der eingeschriebenen Teilnehmer f&#271;&#380;&#733;r Modul z&#271;&#380;&#733;hlen
        $querySubscrb = "SELECT ifnull(COUNT(*),0) FROM einschreibung WHERE lehrveranstaltung_id = '$row[10]'";
        $resSubscrb = $this->db->select($querySubscrb,"mysql_fetch_row");
        $rowSubscrb = $resSubscrb[0];


        if ($row[8] == 'PL')
            $perform = 'Pr&uuml;fungsleistung';
        if ($row[8] == 'TPL')
            $perform = 'Teilpr&uuml;fungsleistung';
        if ($row[8] == 'SPL')
            $perform = 'studienbgl. Pr&uuml;fungsleistung';
        if ($row[8] == 'STPL')
            $perform = 'studienbgl. Teilpr&uuml;fungsleistung';
        if ($row[8] == 'SL')
            $perform = 'Studienleistung';

        //Wenn Modul mit Teilmodul und $modulnr nicht TeilmodulNr
        if (($_modulNr == $row[0]) && ($row[2] != NULL) && $row[1] != $row[9])
        {
            $output .= '<table class="modDetailsHeader" class="ajaxload">
            <tr>
                <th style="width: 50px; text-align: right">' . $row[0] . '</th>
                <th colspan="3" style="width: 265px; text-align: left;">
                <a href="index.php?sid=17&action=showDetails&module=' . $row[0] . '" >' .$row[1].'</a></th>
                <th style="width: 95px;">&nbsp;</th>
                <th style="width: 40px; text-align: right;">ECTS:</th>
                <th style="width: 5px; text-align: left; ">' . $row[3] . '</th>
                <th style="width: 50px; text-align: right;">SWS:</th>
                <th style="padding-right:5px;  text-align: left; width: 5px;">' . $row[4] . '</th>
            </tr>
            <tr>
                <td style="text-align: right;">Status:</td>
                <td style="width: 75px;">' . $row[5] . '</td>
                <td style="text-align: right; width: 130px;">Leistungsnachweis:</td>
                <td style="width:190px;">siehe Teilmodul</td>
                <td colspan="5" style="width: 140px; text-align: right;">';


            //Wenn Anmeldung pflicht
            if ($row[5] == 'pflicht')
            {
                //Zeige maximale Teilnehmerzahl			  			
                $output .= 'Teilnehmer: derzeit ' . $rowSubscrb[0] . ' von ' . $row[6] . '</td>';
            }
            else
            {
                $output .= 'Teilnehmer: derzeit ' . $rowSubscrb[0] . '   </td>';
            }
            $output .= "</table>";
        }

        //*********Wenn Teilmodul, oder Hauptmodul ohne Teilmodul**************
        else
        {
            //Ausgabe der Module
            foreach ($res as $rowTmodul)
            {
                //pruefe ob teilmodul oder Hauptmodul
                
                $modNr = ($_modulNr == $rowTmodul[0]) ? $rowTmodul[0] : $rowTmodul[2];
                $modDescrb = ($_modulNr == $rowTmodul[0]) ? $rowTmodul[1] : $rowTmodul[9];

                $output .= '<table class="modDetailsHeader">
		   <tr>
                    <th style="width: 50px; text-align: right">' . $modNr . '</th>
                    <th colspan="3" style="width: 265px; text-align: left;">
                    <a href="index.php?sid=17&action=showDetails&module='. $modNr .'" >' .$modDescrb.'</a></th>
                    <th style="width: 10px;">&nbsp;</th>
                    <th style=" text-align: right;">ECTS:</th>
                    <th style="width: 5px;text-align: left;" colspan="2"  >' . $rowTmodul[3] . '</th>
                    <th style="width: 50px; text-align: right;">SWS:</th>
                    <th  style="padding-right:5px; text-align: left;" >' . $rowTmodul[4] . '</th>
		   </tr>
		   <tr>
                    <td style="text-align: right;">Status:</td>
                    <td style="width: 75px;">' . $rowTmodul[5] . '</td>
                    <td style="text-align: right; width: 130px;">Leistungsnachweis:</td>
                    <td style="width: 190px;">' . $perform . '</td>';


                //Wenn Anmeldung pflicht
                if ($rowTmodul[5] == 'pflicht')
                {
                    //Zeige maximale Teilnehmerzahl			  			
                   $output .= '<td colspan="3" style="width: 93px; text-align: right;">Teilnehmer:</td>
			 <td colspan="3" style="text-align: left;">derzeit '.$rowSubscrb[0].'von '.$rowTmodul[6].'</td>';
                }
                else
                {
                    $output .= '<td colspan="3" style="width: 93px; text-align: right;">Teilnehmer:</td>
			  <td colspan="3" style="text-align:left;">derzeit ' . $rowSubscrb[0] . '</td>';
                }
                $output .= '</tr>';

                //wenn Recht zur Einschreibung vorhanden
                if ($this->dpload->isAccessAllowed('zugriff_modeinschreiben'))
                {
                    $query = "SELECT teilnahmePruefung, date_format(angemeldet,'%d.%m.%y - %H:%i:%s Uhr') as angemeldet, 
                                     date_format(eingeschrieben,'%d.%m.%y - %H:%i:%s Uhr') as eingeschrieben 
                              FROM einschreibung e JOIN studierende s ON e.studierende_matrikelnr = s.matrikelnr
                              WHERE e.lehrveranstaltung_id = '$row[10]' 
                                    AND s.account_id =" . Session::get('accountId');

                    $resprf = $this->db->select($query,"mysql_fetch_row");

                    //Wenn Einschreibung vorhanden
                    if ($this->db->numRows)
                    {
                        $rowprf = $resprf[0];

                        //Wenn zur Pruefung nicht angemeldet
                        if ($rowprf[0])
                        {
                            $output .= 
                            '<tr>
		              <td colspan="2" style=" text-align: right;">&nbsp;</td>
                            <td style="text-align: right;">Modulpr&uuml;fung: </td>
                            <td><a href="#" id="unsubExam"';
                            
                            $output .="
                             onclick =\"callAjaxRouter(this.id,'unsubExam','$modNr');\"";
                             
                            $output .=' style="text-align: center">abmelden</a></td>
                            <td colspan="3" style="width: 93px; text-align: right;">Einschreibung: </td>
                            <td colspan="3" style="text-align: left;"> <a href="#"  id="unsubMod"';
                              
                            $output .= "onclick =\"callAjaxRouter(this.id,'unsubMod','$modNr');\">
                                        austragen</a></td></tr>";

                            $output .= 
                            '<tr>
                              <td colspan="4" style="padding-right:140px" class="angemeldet">angemeldet ' 
                               .$rowprf[1] . '</td>
                              <td colspan="6" style="width: 180px; padding-right: 5px;" class="angemeldet">Teilnahme:' 
                               .$rowprf[2] . '</td>
		            </tr>';
                        }
                        else
                        {
                           $output .= 
                            '<tr>
		               <td colspan="2" style=" text-align: right;">&nbsp;</td>
			       <td style="text-align: right;">Modulpr&uuml;fung: </td>
			       <td><a href="#"  id="subExam"';
                               $output .= "onclick =\"callAjaxRouter(this.id,'subExam','$modNr');\">
                                        anmelden</a></td>";
                               
                               $output .='
			       <td colspan="3" style="width: 93px; text-align: right;">Einschreibung: </td>
			       <td colspan="3" style="text-align: left;"> <a href="#"  id="unsubMod"';
                                
                               $output .= "onclick =\"callAjaxRouter(this.id,'unsubMod','$modNr');\">
                                        austragen</a></td></tr>";

                            $output .= 
                           '<tr>
                              <td colspan="4" class="angemeldet"></td>
                              <td colspan="6" style="width: 250px; padding-right: 5px;" class="angemeldet">Teilnahme: ' 
                               . $rowprf[2] . '</td>
                            </tr>';
                        }
                    }
                    else
                    {
                        $output .=
                                '<tr>
                          <td colspan="2" style=" text-align: right;">&nbsp;</td>
                          <td style="text-align: right;"></td>
                          <td style="text-align: center"></td>
                          <td colspan="3" style="width: 93px; text-align: right;">Einschreibung: </td>
                              <td colspan="3" style="text-align: left;"><a href="#" id="subMod"';
                        $output .= "onclick=\"callAjaxRouter(this.id,'subMod','$modNr')\">
                                teilnehmen</a></td></tr>";
                    }
                }
            }
            $output .= "</table>";

            //Bewertungfunktion
            $message = "<strong>Modulbewertung</strong>";
            $rating = false;
            
           if ($this->dpload->isAccessAllowed(zugriff_modbewerten) && !$rating)
            {
                $show = ',isDisabled:false';
                $message = '<font color="red">Jetzt bewerten!</font>';
            }
            
            
            $output .= '<div style="min-width:240px; float:right; font-size:12px;  margin-bottom:25px;">          
                   <div style="float:left; margin: 0 5px;">'.$message.'</div>
                   <div style="float:right;  margin: 0 5px;">3.5 (1000)</div>
                   <div class="rating" data-average="3.5" data-id="1">
                   </div></div>';    
            
            $output .= '<script>$(".rating").jRating({
            onSuccess : function(){
		alert("Modul erfolgreich bewertet!");
	     },      
	     onError : function(){
		alert("Error");
	     }';

           $output .= $show.'});</script>';
        }
        return $output;
    }

//----------------------------------------------------------------------------------------------------------------------

 public function unsubscribeMod($_modulNr, $opt)
{
    if ($this->dpload->isAccessAllowed('zugriff_modeinschreiben'))
    {
        $accountid = Session::get('accountId');

        //abfragen der MAtrikelnr
        $resMatr = $this->db->select("SELECT matrikelnr FROM studierende 
                                      WHERE account_id = $accountid","mysql_fetch_row");
        $matr = $resMatr[0];


        //Abfragen ob Modul oder Teilmodul
        $resMod =$this->db->select("SELECT id , modul_modulnr, teilmodul_modulnr, teilnehmer  
  	                               FROM lehrveranstaltung  
  	                               WHERE  modul_modulnr = '$_modulNr' 
                                       OR teilmodul_modulnr = '$_modulNr'","mysql_fetch_row");

        $rowMod = $resMod[0];

        $resExam = $this->db->select("SELECT teilnahmePruefung FROM einschreibung 
                                      WHERE studierende_Matrikelnr = $matr[0] 
                                      AND lehrveranstaltung_id = $rowMod[0]","mysql_fetch_row");
        $rowExam = $resExam[0];

        //Wenn Einschreiben
        if ($opt == 1)
        {
            if ($rowExam[0])
            {
                Notice::alertUnsubscribeExamErr();
            }
            else
            {
                $where = "studierende_Matrikelnr = $matr[0] AND lehrveranstaltung_id = $rowMod[0]";
                $query = $this->db->delete("einschreibung",$where);

                if ($query)
                {
                    Notice::alertUnsubscribeSucc();
                }
            }
        }
        else
        {        
                $dataArray = array('teilnahmePruefung' => 0,
                                       'angemeldet' => 0);
                
                $where = "studierende_matrikelnr = $matr[0] AND lehrveranstaltung_id = $rowMod[0]";
                
                $query = $this->db->update("einschreibung", $dataArray, $where);
            
            if ($query)
            {
                Notice::alertUnsubscribeExamSucc();
            }
        }
    }
    else
    {
        Notice::alertModSubscribeAccessErr();
    }
}

//Funktion zum einschreiben in Modul------------------------------------------------------------------------------------
public function subscribeMod($_modulNr, $_opt = 1)
{

    if ($this->dpload->isAccessAllowed('zugriff_modeinschreiben'))
    {
        $accountid = Session::get('accountId');
        $datetime = date("Y-m-d H:i:s", time());

        //abfragen der MAtrikelnr2
        $resmatr = $this->db->select("SELECT matrikelnr FROM studierende 
                                      WHERE account_id = $accountid","mysql_fetch_row");
        
        $matr = $resmatr[0];


        //Abfragen ob Modul oder Teilmodul
        $resMod = $this->db->select("SELECT l.id as lvid, l.modul_modulnr as modnr, 
                                            l.teilmodul_modulnr as tmodnr, l.teilnehmer  
  	                               FROM lehrveranstaltung l 
  	                               WHERE  l.modul_modulnr = '$_modulNr' 
                                     OR l.teilmodul_modulnr = '$_modulNr'","mysql_fetch_row");

        $rowMod = $resMod[0];

        //Abfrage ob max. Teilnehmer erreicht
        $query = "SELECT id FROM lehrveranstaltung WHERE teilnehmer = 
                  (SELECT COUNT(id) FROM einschreibung WHERE lehrveranstaltung_id = $rowMod[0]) 
  	             AND id = $rowMod[0] AND anmeldung = 'pflicht' ";
        
        
        $resNofSubscrb = $this->db->select($query);

        $nofSubscrb = $this->db->numRows;
        
        //Abfrage ob Eintrag zum Studierenden vorhanden
        $resEntry = $this->db->select("SELECT id FROM einschreibung WHERE studierende_matrikelnr = $matr[0] 
  	                                   AND lehrveranstaltung_id = $rowMod[0]");

        $nofEntries = $resEntry[0];
        
        //Wenn nicht max. Teilnehmer erreicht
        if (!$nofSubscrb)
        {
            //Wenn Einschreiben
            if ($_opt == 1)
            {
                //Wenn noch nicht eingeschrieben
                if (!$nofEntries)
                {
                    $dataArray = array(teilnahmePruefung => 0,
                                       studierende_matrikelnr => $matr[0],
                                       lehrveranstaltung_id => $rowMod[0],
                                       angemeldet => 0,
                                       eingeschrieben => $datetime);
                                           
                    $query = $this->db->insert(einschreibung, $dataArray);

                    
                    if ($query)
                    {
                        Notice::alertSubscribeSucc($_modulNr);
                    }
                }
                else
                {
                    Notice::alertAlreadySubscribed();
                }
            }
            else
            {
                $dataArray = array('teilnahmePruefung' => 1,
                                       'angemeldet' => $datetime);
                
                $where = "studierende_matrikelnr = $matr[0] AND lehrveranstaltung_id = $rowMod[0]";
                
                $query = $this->db->update("einschreibung", $dataArray, $where);
                
                if($query)
                {
                    Notice::alertSubscribeExamSucc($_modulNr);
                }
            }
        }
        else
        {
            Notice::alertSubscribeErr();
        }
    }
    else
    {
         Notice::alertModSubscribeAccessErr();
    }
}

//--Abfragen der Module Tags fuer autocomplete--------------------------------------------------------------------------

public function getModTags($opt)
{
    //wenn option gesetzt, zeige auch inaktive
    $sqlpraefix = ($opt == 1) ? 'WHERE m.aktiv = 1' : '';
    $sqlpraefix2 = ($opt == 1) ? 'WHERE t.aktiv = 1' : '';

// Load available tags
    //Abfragen Tags der Module
    $tagResMod = catchMysqlQuery("SELECT m.modulnr as modNr,  m.bezeichnung, l.niveaustufe, f.bezeichnung, 
                                        concat(a.akadTitel,' ',a.nachname),date_format(log.erstellt, '%d.%m.%y'), 
                                        date_format(log.geaendert,  '%d.%m.%y'), l.regelsemester			          
                                 FROM lehrveranstaltung l JOIN modul m ON m.modulnr = l.modul_modulnr
                                        JOIN studiengang s ON s.id = l.studiengang_id
                                        JOIN fachrichtung f ON f.id = s.fachrichtung_id
                                        JOIN mitarbeiter a ON a.persnr = m.mitarbeiter_persnr
                                        LEFT JOIN lehrender d ON d.lehrveranstaltung_lvnr = l.id
                                        LEFT JOIN log ON log.modul_modulnr = m.modulnr $sqlpraefix
                                        GROUP BY m.modulnr");



    //Abfrage Tags Teilmodule
    $tagResTmod = catchMysqlQuery("SELECT t.modulnr, t.bezeichnung, l.niveaustufe, f.bezeichnung, 
                                          concat(a.akadTitel,' ',a.nachname), date_format(log.erstellt, '%d.%m.%y'), 
                                          date_format(log.geaendert,  '%d.%m.%y'), l.regelsemester 			          
			                 FROM lehrveranstaltung l JOIN teilmodul t ON t.modulnr = l.teilmodul_modulnr
			                       JOIN studiengang s ON s.id = l.studiengang_id
			                       JOIN fachrichtung f ON f.id = s.fachrichtung_id
             		                 JOIN modul m ON m.modulnr = t.modul_modulnr
			                       JOIN mitarbeiter a ON a.persnr = m.mitarbeiter_persnr
			                       LEFT JOIN lehrender d ON d.lehrveranstaltung_lvnr = l.id
			                       LEFT JOIN log ON log.teilmodul_modulnr = t.modulnr $sqlpraefix2
			                       GROUP BY t.modulnr");


    if (!$tagResMod || !$tagResMod)
    {
        echo 'Datenbankfehler Tags: ' . mysql_error();
    }
    else
    {
        //laden der Tags in Array                       
        while ($row = mysql_fetch_array($tagResMod))
        {

            $TagNrArray[] = '{ label : "' . $row[0] . '", category:  "Modulnummer" }';
            $TagBezArray[] = '{ label : "' . $row[1] . '", category:  "Bezeichnung"}';
            $TagNsArray[] = '{ label : "' . $row[2] . '", category:  "Niveaustufe"}';
            $TagFrArray[] = '{ label : "' . $row[3] . '", category:  "Fachrichtung"}';
            $TagVerArray[] = '{ label : "' . $row[4] . '", category:  "Verantwortlicher"}';
            $TagEdArray[] = '{ label : "' . $row[5] . '", category:  "Erstellt am:"}';
            $TagGdArray[] = '{ label : "' . $row[6] . '", category:  "Ge&auml;ndert am:"}';
            $TagRsArray[] = '{ label : "' . $row[7] . '", category:  "Regelsemester"}';
        }

        while ($row = mysql_fetch_array($tagResTmod))
        {
            $TagNrArray[] = '{ label : "' . $row[0] . '", category:  "Modulnummer" }';
            $TagBezArray[] = '{ label : "' . $row[1] . '", category:  "Bezeichnung"}';
            $TagNsArray[] = '{ label : "' . $row[2] . '", category:  "Niveaustufe"}';
            $TagFrArray[] = '{ label : "' . $row[3] . '", category:  "Fachrichtung"}';
            $TagVerArray[] = '{ label : "' . $row[4] . '", category:  "Verantwortlicher"}';
            $TagEdArray[] = '{ label : "' . $row[5] . '", category:  "Erstellt am:"}';
            $TagGdArray[] = '{ label : "' . $row[6] . '", category:  "Ge&auml;ndert am:"}';
            $TagRsArray[] = '{ label : "' . $row[7] . '", category:  "Regelsemester"}';
        }


        //Filtern von doppelten Eintr&#271;&#380;&#733;gen im Array
        $regularSemtag = array_unique($TagRsArray);
        $nstag = array_unique($TagNsArray);
        $vertag = array_unique($TagVerArray);
        $edtag = array_unique($TagEdArray);
        $gdtag = array_unique($TagGdArray);
        $regularSemtag = array_unique($TagRsArray);
        $subjecttag = array_unique($TagFrArray);



        //Array als String
        $nrtags = implode(",", $TagNrArray);
        $beztags = implode(",", $TagBezArray);
        $nstags = implode(",", $nstag);
        $vertags = implode(",", $vertag);
        $edtags = implode(",", $edtag);
        $gdtags = implode(",", $gdtag);
        $regularSemtags = implode(",", $regularSemtag);

        //Verketten der Strings zu einem String
        $tags = $nrtags . ',' . $beztags . ',' . $nstags . ',' . $vertags . ',' . $edtags . ',' 
                . $gdtags . ',' . $regularSemtags;

        return $tags;
    }
}

//---------------------------------------------------------------------------------------------------------------------

public function rateMod()
{

$aResponse['error'] = false;
$aResponse['message'] = '';

// ONLY FOR THE DEMO, YOU CAN REMOVE THIS VAR
	$aResponse['server'] = ''; 
// END ONLY FOR DEMO
	
		$id = intval($_POST['idBox']);
		$rate = floatval($_POST['rate']);
		$success = true;
		// else $success = false;
		
		
		// json datas send to the js file
		if($success)
		{
			$aResponse['message'] = 'Your rate has been successfuly recorded. Thanks for your rate :)';
			
			// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
				$aResponse['server'] = '<strong>Success answer :</strong> Success : Your rate has been recorded. Thanks for your rate :)<br />';
				$aResponse['server'] .= '<strong>Rate received :</strong> '.$rate.'<br />';
				$aResponse['server'] .= '<strong>ID to update :</strong> '.$id;
			// END ONLY FOR DEMO
			Notice::alertModActSucc();
			return json_encode($aResponse);
		}
		else
		{
			$aResponse['error'] = true;
			$aResponse['message'] = 'An error occured during the request. Please retry';
			
			// ONLY FOR THE DEMO, YOU CAN REMOVE THE CODE UNDER
				$aResponse['server'] = '<strong>ERROR :</strong> Your error if the request crash !';
			// END ONLY FOR DEMO
			
			
			return json_encode($aResponse);
		}
            
	}
}
?>
