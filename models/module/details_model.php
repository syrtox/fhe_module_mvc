<?php

require_once ("models/viewHelper/module_details.helper.php");

class ModuleDetails_Model extends DPLoad
{
    private $helper;
   
    public function __construct()
    {
        parent::__construct();
        $this->helper = new ModuleDetailsViewHelper();
    }

//---------------------------------------------------------------------------------------------------------------------    

    public function getModDetails($_modulNr)
    {
        //Abfragen der Moduldetails
        $queryDetails = "SELECT m.modulNr, t.modulNr, CONCAT(a.akadTitel,' ', substr(a.vorname,1,1) ,'. ', a.nachname), 
                                l.regelsemester, t.voraussetzung, m.voraussetzung, t.gewichtungNote, m.wichtungGesamtnote, 
                                sum(l.praesenszeit),sum(l.vorbereitungszeit), sum(l.selbststudienzeit),m.voraussetzungFuer,
                                t.voraussetzungFuer, s.bezeichnung, v.bezeichnung
                        FROM lehrveranstaltung l LEFT JOIN modul m ON l.modul_modulnr = m.modulNr
                        LEFT JOIN teilmodul t ON l.teilmodul_modulnr = t.modulNr
                             JOIN mitarbeiter a ON m.mitarbeiter_persnr = a.persNr
                             JOIN studiengang s ON s.id = l.studiengang_id
                             JOIN vertiefung v ON v.id = l.vertiefung_id
                        WHERE l.modul_modulnr = '$_modulNr' OR l.teilmodul_modulnr = '$_modulNr' AND m.aktiv = 1";


        $resDetails = $this->db->select($queryDetails, "mysql_fetch_row");
        $rowDetails = $resDetails[0];
        
        //Abfrage der Dozenten
        $queryLecture = "SELECT distinct CONCAT(a.akadTitel,' ', substr(a.vorname,1,1) ,'. ', a.nachname)
		         FROM lehrveranstaltung l JOIN lehrender d ON l.id = d.lehrveranstaltung_lvNr
	          	      JOIN mitarbeiter a ON d.mitarbeiter_persnr = a.persNr
	                 WHERE l.modul_modulnr = '$_modulNr' OR l.teilmodul_modulnr = '$_modulNr' GROUP BY a.nachname";


        $resLecture = $this->db->select($queryLecture, "mysql_fetch_row");

        //Abfrage der Anzahl und die Teilmodule des Hauptmoduls f&#271;&#380;&#733;r Seitennavigation	
        $queryNofMod = "SELECT t.modulNr, t.bezeichnung
			FROM lehrveranstaltung l 
                             JOIN modul m ON l.modul_modulnr = m.modulNr
		             JOIN teilmodul t ON l.teilmodul_modulnr = t.modulNr
		        WHERE l.modul_modulnr = '" . $rowDetails[0] . "' ORDER BY l.teilmodul_modulnr";

        $resNofMod = $this->db->select($queryNofMod, "mysql_fetch_array");
        $nofMod = $this->db->numRows;
        
        $tmodArray = array();
        $ix = 0;
        if ($resNofMod);
        {
            //Speichere Teilmodule mit Nr im Array
            foreach ($resNofMod as $rowTmod)
            {
                $tmodArray[$ix] = $rowTmod[0];
                $ix++;
            }
        }

        $output .="<p class='postheadericon' style='font-weight:bold; margin-top:25px;'> 
                   Kurz&uuml;bersicht zum Modul</p>";

        $output .= '<table id="modDetails_ov">
		  <tr>
		    <th style="height: 25px; vertical-align:middle;"><b>Studiengang</b></th>
		     <td colspan="2" style="height: 25px; vertical-align:middle;"><b>' . $rowDetails[13] . '</b></td>
		 </tr>';

        if ($rowDetails[14] != 'keine')
        {
            $output .='<tr>
                       <th>Vertiefungsrichtung</th>
                       <td colspan="2">' . $rowDetails[14] . '</td>
		       </tr>';
        }

        $output .='<tr>
                   <th>Verantwortlich</th>
                   <td colspan="2">' . $rowDetails[2] . '</td>
                  </tr>
                   <tr>
                   <th>Lehrende(r)</th>
                    <td colspan="2">';

        //Ausgabe Lehrende
        foreach ($resLecture[0] as $rowLecture)
        {
            $output .= '<p>' . $rowLecture. '</p>';
        }

        $output .='</td>
		   </tr>';
        
        //Wenn Modul hat Teilmodul, zeige Laufzeit
        if (($_modulNr == $rowDetails[0]) && ($ix > 0))
        {
            $output .= '<tr>
                       <th>Laufzeit</th>
		       <td colspan="2">' . $ix . ' Semester</td>
		       <tr>';
        }
        else
        {
            $output .=' <tr>
                        <th>Regelsemester </th>
                        <td colspan="2">' . $rowDetails[3] . '. Fachsemester</td>
                        </tr>';
        }

        $output .=' <tr>
		    <th>Vorraussetzungen</th>';

        //Wenn Modul, zeige Modul Vorraussetzung
        if ($_modulNr == $rowDetails[0])
        {
            $output .= '<td colspan="2">' . $rowDetails[5] . '</td>';
        }
        else
        {
            $output .= '<td colspan="2">' . $rowDetails[4] . '</td>';
        }

        $output .='</tr>
		 <tr><th>ist Vorraussetzung<br/> <span style="font-size:8pt;">(f&uuml;r Modul o. Teilmodul)</span></th>';
       
        //Wenn Modul, zeige Modul Vorraussetzung
        if ($_modulNr == $rowDetails[0])
        {
            $output .= '<td colspan="2">' . $rowDetails[11] . '</td>';
        }
        else
        {
            $output .= '<td colspan="2">' . $rowDetails[12] . '</td>';
        }


        $output .='</tr>
		  <tr><th>Gewichtung Note</th>';

        //Wenn Modul, zeige Modul Gewichtung
        if ($_modulNr == $rowDetails[0])
        {
            $output .= '<td colspan="2">' . $rowDetails[7] . '</td>';
        }
        else
        {
            $output .= '<td colspan="2">' . $rowDetails[6] . '</td>';
        }

        $tTime = $rowDetails[8] + $rowDetails[9] + $rowDetails[10] . ' Stunden';

        $output .= '</tr>
		<tr>
            <th>Workload<br>Gesamt:<br><br>Pr&auml;senszeit:<br>Vorbereitungszeit:<br>Selbstudienzeit:</th>
            <td style="height: 98px; text-align:right; border-right:1px white solid;"><br>' . $tTime . '<br>
                <br>'.$rowDetails[8].' Stunden<br>'.$rowDetails[9].' Stunden<br>'.$rowDetails[10].' Stunden</td>
            <td style="height: 98px; min-width: 126px;"></td>
		</tr>	
         	</table>';

        //Wenn Teilmodul zeige Teilmodulnavigation
        if ($_modulNr == $rowDetails[1])
        {
            //Suche den Index im Array passend zum Teilmodul 
            $i = array_search($_modulNr, $tmodArray);
            //Ausgabe Seitennavigation f&#271;&#380;&#733;r Teilmodule

            $output .="<div style='width:500px; margin-top:5px;'>";
            $output .= '<span>';
            if ($i > 0)
            {
                $output .='<a href="index.php?sid=17&action=showDetails&module='.$tmodArray[$i - 1].'"> <-vorheriges</a>';
            }

            if ($i < $nofMod - 1)
            {
                $output .= " Teilmodul ";
                $output .= '<a href="index.php?sid=17&action=showDetails&module=' . $tmodArray[$i + 1] . '"> n&auml;chstes-></a></span>';
            }
            $output .="</span>";
            $output .="</div>";
        }

        //Wenn Modul hat Teilmodul
        if (($rowDetails[1] != NULL) && ($_modulNr == $rowDetails[0]))
        {
            $output .="<p class='postheadericon' style='font-weight:bold; margin-top:40px;'> Teilmodule zu diesem Modul </p>
                <br/><br/>";
            $output .="<p>N&auml;here Informationen zu dem einzelnen Teilmodulen, finden Sie in der jeweiligen Beschreibung des Moduls.
                    Wenn Sie sich in einem Modul eingeschrieben oder zur Pr&uuml;fung angemeldet haben, so erscheint 
                    dieses Modul in ihrem Profil.</p><br/>";
            $output .="<p>PL = Pr&uuml;fungsleistung --- TPL = Teilpr&uuml;fungsleistung --- SL = Studienleistung</p>";
            $output .="<p>STPL = studienbegleidende Teilpr&uuml;fungsleistung</p><br/>";

            foreach ($tmodArray as $tmodNr)
            {
                //Abfragen der Teilmodule mit Einschreibung
                $output .= $this->helper->getModDetailsHeader($tmodNr);
            }
        }
        else
        {
            //Abfragen Details Lehrveranstaltung
            $output .="<p class='postheadericon' style='font-weight:bold; margin-top:35px;'> 
                       Detailierte Modulbeschreibung</p><br/><br/>";
            $output .= "<p>Einem Modul ist mindestens eine Lehrveranstaltung zugeordnet, in dieser wird der eigentliche
                       Inhalt<br/> des Moduls vermittelt.
                       Eine detailierte Beschreibung zum Inhalt und den Anforderungen, finden Sie in der unten 
                        aufgef&uuml;hrten &Uuml;bersicht.</p><br /><br />";

             $output .= $this->getCourseDetails($_modulNr);
        }
        
        return $output;
    }
//----------------------------------------------------------------------------------------------------------------------
    
 public function showModBreadcrumb($_modulNr)
{
    $an = "Sie befinden sich hier: ";
    $url = $_SERVER['PHP_SELF'].'?sid=13';
    $tr = " > ";
    $modlink = "index.php?sid=17&action=showDetails&module=";
    $pfad = "";

    
    $query = "SELECT m.modulNr, m.bezeichnung, t.modulNr, t.bezeichnung		          
              FROM lehrveranstaltung l LEFT JOIN modul m ON l.modul_modulnr = m.modulNr
		                       LEFT JOIN teilmodul t ON l.teilmodul_modulnr = t.modulNr
	      WHERE l.modul_modulnr = '$_modulNr' OR l.teilmodul_modulnr = '$_modulNr'";
		
		
		$res = $this->db->select($query,"mysql_fetch_row");
		$row = $res[0];
		 
		 //Abfragen der Module f&#271;&#380;&#733;r Breadcrumb
		 //Wenn kein Teilmodul vorhanden
	     if (t.modulNr == NULL || $_modulNr == $row[0])
	     {
		    $modarray = array($row[0] => $row[1]);	    
		 }
		 else
		 {
		    $modarray = array($row[0] => $row[1], $row[2] => $row[3]);	
	     }						
   
	$home = $an."<a href=\"".$url."\">Modul&uuml;bersicht</a>";
    
    while( $modul = each($modarray))
    {  
        $pfad = $pfad.$tr."<a  href=\"".$modlink.$modul[key]."\">".ucfirst($modul[value])."</a>"; 
		//echo $tr.ucfirst($pie[$a]);
	} 
	return "<div id='modbreadcrumb'>$home$pfad</div>";
	    
}


//----------------------------------------------------------------------------------------------------------------------

public function getCourseDetails($_modulNr)
{

    $res = $this->db->select("SELECT veranstaltungsort, typ, niveaustufe, teilnehmer, status.bezeichnung, anmeldung, 
                                  thema, ziele, inhalte, methoden,sprache,pruefungsVoraussetzung, pruefungsart, 
                                  wdhpruefungsart,angebot, verwendbarkeit, gewichtungNote
                             FROM lehrveranstaltung JOIN status ON lehrveranstaltung.status_id = status.id                            
                             WHERE modul_modulnr = '$_modulNr' OR teilmodul_modulnr = '$_modulNr'","mysql_fetch_row");

    $row = $res[0];
    
    $max = ($row[3] == 0) ? 'unbegrenzt' : 'max. ' . $row[3];
    
    $output = '<table id="courseDetails">
	<tr>
		<td colspan="2" class="thead">
		<strong>Veranstaltungsform</strong><br/>' . $row[1] . '</td>
		<td class="thead">
		<strong>Veranstaltungsort</strong><br>' . $row[0] . '</td>
		<td class="thead">
		<strong>Niveaustufe</strong><br>' . $row[2] . '</td>
	</tr>
	<tr>
		<td colspan="2" class="theadzs">
		<strong>Teilnehmer: </strong>' . $max . '</td><td class="theadzs">
		<strong>Status: </strong>' . $row[4] . '</td>
		<td class="theadzs">
		<strong>Anmeldung:</strong> ' . $row[5] . '<br></td>
	</tr>
	<tr>
		<td colspan="2" style="height: 20px"></td>
		<td ></td>
		<td></td>
	</tr>
		<tr>
		<td colspan="4" class="tbody" style="border:none;">
		<span><strong>Thema:</strong></span><br><br>
		' . nl2br($row[6]) . '</td>
	</tr>
	<tr>
		<td colspan="4" style="height: 20px"></td>
	</tr>
	<tr>
		<td colspan="4" class="tbody">
		<span ><strong>Inhalte:</strong></span><br><br>' . nl2br($row[8]) . '<br><br></td>
	</tr>
	<tr>
		<td colspan="4" class="tbody">
		<span ><strong>Methoden:</strong></span><br>
		<br>' . nl2br($row[9]) . '</td>
	</tr>
	<tr>
		<td colspan="4" style="height: 20px">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="tbody">
		<span><strong>Lernziele:</strong></span><span><br><br>' . nl2br($row[7]) . '</span><br></td>
	</tr>
	<tr>
		<td colspan="4" style="height: 20px"></td>
	</tr>
	<tr>
		<td colspan="4" class="tbody"><strong>Literatur/Vorlesungsunterlagen:</strong><br>
		<br>v cx&nbsp; cxsdcdscdwcwdcsdcsdc</td>
	</tr>
	<tr>
		<td colspan="2" style="height: 20px"></td>
		<td style="height: 20px"></td>
		<td  style="height: 20px"></td>
	</tr>
	<tr>
		<td rowspan="2" class="tfooter" style="background-color: #F8F8F8"><strong>Workload</strong><br>
		Gesamt:<br><br>Pr&#271;&#380;&#733;senszeit:<br>Vorbereitungszeit:<br>Selbststudienzeit:</td>
		<td rowspan="2"  class="tfooter" style="background-color: #F8F8F8"><br>60 Stunden<br><br>
		30 Stunden<br>15 Stunden<br>15 Stunden<br></td>
		<td  class="tfooter" style="background-color: #FDFDFD"><strong>
		Veranstaltungssprache</strong><br>' . $row[10] . '</td>
		<td  class="tfooter" style="background-color: #FDFDFD"><strong>Pr&uuml;fungsart:</strong><br>' . $row[12] . '</td>
	</tr>
	<tr>
		<td class="tfooter" style="background-color: #FDFDFD"><strong>Gewichtung 
		der Note<br></strong>' . $row[16] . '</td>
		<td class="tfooter" style="background-color: #FDFDFD"><strong>Pr&uuml;fungsvorrausetzung:
            </strong><br>' . $row[11] . '<br></td>
	</tr>
	<tr>
		<td colspan="2" class="tfooterzs" style="background-color: #F8F8F8"><strong>
		Verwendbarkeit<br></strong>' . $row[15] . '</td>
		<td  class="tfooterzs" style="background-color: #F8F8F8"><strong>
		H&auml;ufigkeit <br>
		</strong>' . $row[14] . '</td>
		<td class="tfooterzs" style="background-color: #F8F8F8"><strong>Wiederholungspr&uuml;fung</strong>
            <br>' . $row[13] . '</td>
	</tr>
	</table>';
    
    return $output;
}
    
}