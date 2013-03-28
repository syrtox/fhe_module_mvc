<?php

class ModuleListViewHelper
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

            case getModuleOverview:
                return $this->getModuleOverview();
                break;
            case getModuleMajorTable:
                return $this->getModuleMajorTable();
                break;
            case getModuleMajorTimeline:
                return $this->getModuleMajorTable();
                break;
             case showModuleList:
                return $this->showModuleList();
                break;
            default:
                echo "Coud not find the details";
                return false;
        }
    }

//---------------------------------------------------------------------------------------------------------------------

    public function getModuleOverview($_args = NULL)
    {
        $sqlPraefix = ($_args[0] == 0) ? 'WHERE f.id > 0' : 'WHERE f.id =' . $_args[0];
        $nofMod = 0;
        //if there no status set
        if ($_args[3] == NULL)
            $_args[3] = array(1, 2, 3);

        //searched for any keayword
        if (!empty($_args[5]))
        {
            $keyword = $_args[5];

            //Suchpraefix fuer Module       
            $sqlPraefix = "WHERE m.modulnr like '%" . $keyword . "%' 
                      OR m.bezeichnung like '%" . $keyword . "%'
                      OR t.modulnr like '%" . $keyword . "%'
                      OR t.bezeichnung like '%" . $keyword . "%'
                      OR concat(a.akadTitel,' ',a.nachname) like '%" . $keyword . "%'
                      OR l.inhalte like '%" . $keyword . "%'
                      OR l.thema like '%" . $keyword . "%'
                      OR l.methoden like '%" . $keyword . "%'
                      OR l.ziele like '%" . $keyword . "%'";
        }

        //Abfragen der Module
        $res = $this->db->select("SELECT m.modulNr, m.bezeichnung, ifnull(t.modulNr,m.modulNr), 
              ifnull(t.bezeichnung,m.bezeichnung), l.regelsemester, s.bezeichnung, l.ects, l.sws, 
              sg.bezeichnung, t.aktiv, m.aktiv, f.bez_kurz, l.leistung, l.niveaustufe, ifnull(t.status_id,m.status_id)
              FROM modul m LEFT JOIN lehrveranstaltung l ON l.modul_modulnr = m.modulNr    
                             LEFT JOIN teilmodul t ON l.teilmodul_modulnr = t.modulNr 
                             JOIN status s ON l.status_id = s.id
                             JOIN studiengang sg ON sg.id = l.studiengang_id
                             JOIN fachrichtung f ON f.id = sg.fachrichtung_id 
                             JOIN mitarbeiter a ON a.persnr = m.mitarbeiter_persnr			                   			                   
                             $sqlPraefix ORDER BY 5,1", "mysql_fetch_array");

        $output = '<div id="seiten">               
            <form method="post">
             <span style="font-size:10pt">Anzahl: </span><select  class="pagesize">
                   <option selected="selected" value="10">10</option>
                   <option value="20">20</option>
                   <option value="30">30</option>
                   <option value="40">40</option>
             </select>&nbsp;&nbsp;&nbsp;
             <img style="vertical-align:middle;" alt="first" src="../images/first.png" class="first">
             <img  style="vertical-align:middle;" alt="prev" src="../images/prev.png" class="prev">
             <input type="text" id="page" style="width:50px;" class="pagedisplay" style="width: 30px; height: 14px">
             <img  style="vertical-align:middle;" alt="next"src="../images/next.png" class="next">
             <img style="vertical-align:middle;" alt="last" src="../images/last.png" class="last">
         </form>
       </div>';

        $output .= '<table id="myTable" class="tablesorter" style="width:725px; border:0;" cellspacing="1" >
               <thead>
                      <tr>
                      <th style="width: 50px; class="spancenter">Modul</th>
                      <th style="width: 250px;">Bezeichnung</th>
                      <th style="width: 30px;">FR</th>
                      <th style="width: 30px;">FS</th>
                      <th style="width: 40px;">SWS</th>
                      <th style="width: 30px;">CP</th>
                      <th style="width: 60px;">Niveau</th>
                      <th style="width: 70px;">Status</th>
                      <th style="width: 70px;">Leistung</th>
              </thead><tbody>';

        //Zeilenweise Ausgabe der Module
        foreach ($res as $row)
        {
            //Wenn Hauptmodul, ist aktivert, und mit ausgew&#271;&#380;&#733;hlzten status
            if ($row[10] && in_array($row[14], $_args[3]))
            {
                //Wenn Teilmodul und aktiviert, oder Hauptmodul
                if (($row[9] && in_array($row[14], $_args[3])) || ($row[0] == $row[2]))
                {
                    
                    $output .='  <tr>
                            <td class="spancenter">' . $row[2] . '</td>
                            <td> <a href="index.php?sid=17&action=showDetails&module='.$row[2].'">'.$row[3].'</a></td>
                            <td class="spancenter">' . $row[11] . '</td>
                            <td class="spancenter">' . $row[4] . '</td>
                            <td class="spancenter">' . $row[6] . '</td>
                            <td class="spancenter">' . $row[7] . '</td>
                            <td class="spancenter">' . $row[13] . '</td>
                            <td class="spancenter">' . $row[5] . '</td>
                            <td class="spancenter">' . $row[12] . '</td>
                            </tr>';
                    
                    $nofMod++;
                }
            }
        }

            if (!empty($keyword))
            {
                $output .= '<span>Suche nach "' . $keyword . '" ergab ' . $nofMod . ' Treffer</span>';
            }
            else
            {
                $output .= 'Treffer gesamt: ' . $nofMod;
            }
        
        $output .= '</tbody></table>';

        //Ausgabe Tabelle mit Abkuerzungen
        $output .='<h2 class="postheader" style="margin-top:30px";>
                <span class="postheadericon">Abk&uuml;rzungen </span></h2>
               <table id="abk_legende">
                            <tr>
                                  <th  style="width: 30px">FR</th>
                                  <td  style="width: 120px">Fachrichtung</td>
                                  <th  style="width: 30px">CP</th>
                                  <td  style="width: 100px">Credit-Points(ECTS)</td>												
                                  <th  style="width: 36px">SL</th>
                                  <td >Studienleistung</td>

                            </tr>
                            <tr>
                                  <th  style="width: 30px">FS</th>
                                  <td  style="width: 120px">Fachsemester</td>
                                  <th  style="width: 30px">PL</th>
                                  <td  style="width: 100px">Pr&uuml;fungsleistung</td>
                                  <th  style="width: 36px">SPL</th>
                                  <td >studienbegleitende Pr&uuml;fungsleistung</td>
                            </tr>
                            <tr>
                                  <th  style="width: 30px">SWS</th>
                                  <td  style="width: 120px">Semesterwochenstunden</td>
                                  <th  style="width: 30px">TPL</th>
                                  <td  style="width: 100px">Teilpr&uuml;fungsleistung</td>
                                  <th  style="width: 36px">STPL</th>
                                  <td >studienbegleitende Teilpr&uuml;fungsleistung</td>
                            </tr>
                            </table>';
        return $output;
    }

//---------------------------------------------------------------------------------------------------------------------

    public function getModuleMajorTable($_args)
    {
        $currentSem = "";
        $currentstudyPath = "";
        $currentAofSpec = "";
        $tHead = true;
        $i = 1;
        $k = 1;

        $sqlPraefix = 'WHERE l.studiengang_id =' . $_args[1] . ' AND l.vertiefung_id = 0';

        //if there no status set
        if ($_args[3] == NULL)
            $_args[3] = array(1, 2, 3);

        //searched for any keayword
        if (!empty($_args[5]))
        {
            $keyword = $_args[5];

            //Suchpraefix fuer Module       
            $sqlPraefix = "WHERE m.modulnr like '%" . $keyword . "%' 
                  OR m.bezeichnung like '%" . $keyword . "%'
                  OR t.modulnr like '%" . $keyword . "%'
                  OR t.bezeichnung like '%" . $keyword . "%'
                  OR concat(a.akadTitel,' ',a.nachname) like '%" . $keyword . "%'
                  OR l.inhalte like '%" . $keyword . "%'
                  OR l.thema like '%" . $keyword . "%'
                  OR l.methoden like '%" . $keyword . "%'
                  OR l.ziele like '%" . $keyword . "%'";
        }

        //Abfragen der Module
        $res = $this->db->select("SELECT m.modulNr, m.bezeichnung, ifnull(t.modulNr,m.modulNr), 
          ifnull(t.bezeichnung,m.bezeichnung), l.regelsemester, s.bezeichnung, l.ects, l.sws, 
          sg.bezeichnung, t.aktiv, m.aktiv, f.bez_kurz, l.leistung, l.niveaustufe, ifnull(t.status_id,m.status_id)
          FROM modul m LEFT JOIN lehrveranstaltung l ON l.modul_modulnr = m.modulNr    
                         LEFT JOIN teilmodul t ON l.teilmodul_modulnr = t.modulNr 
                         JOIN status s ON l.status_id = s.id
                         JOIN studiengang sg ON sg.id = l.studiengang_id
                         JOIN fachrichtung f ON f.id = sg.fachrichtung_id 
                         JOIN mitarbeiter a ON a.persnr = m.mitarbeiter_persnr			                   			                   
                         $sqlPraefix ORDER BY 5,1", "mysql_fetch_array");

        //get the number of found rows
        $nofMod = $this->db->numRows;

        //Ausgabe Modul, gegliedert nach Semester
        foreach ($res as $row)
        {
            //Wenn Hauptmodul, ist aktivert und in Status
            if ($row[10] && in_array($row[14], $_args[3]))
            {
                //Wenn Teilmodul, ist aktiviert und in Status, oder Hauptmodul
                if ((in_array($row[14], $_args[3]) && $row[9]) || ($row[0] == $row[2]))
                {

                    if ($row[8] != $currentstudyPath)
                        $output = "<h2 class='postheadericon' style='margin-bottom:20px;'>Module - Studiengang " . $row[8] . "</h2><br/>";

                    $semester = $row[4];

                    //Wenn neues Semseter, erstelle &#271;&#380;&#733;berschrift
                    if ($currentSem != $semester)
                    {
                        if ($semester != 1)
                        {
                            $output .= "</tbody></table>";
                        }

                        $output .= '<h4 class="postheadericon" style="margin: 20px 0px -10px 40px">' . $semester . '. Fachsemester</h4>';
                        $output .= '<table id="myTable' . $i . '" class="tablesorter" style="width: 600px; margin-left:40px; 
                                                        border:0;" cellspacing="1">';
                        $tHead = true;
                        $i++;
                    }
                    //Wenn Tabellenkopf
                    if ($tHead)
                    {
                        $output .= '<thead><tr><th style="height:5px;">Modul</th>
                               <th style="height:5px;">Bezeichnung</th>
                               <th style="height:5px;">Status</th>
                               <th style="height:5px;">SWS</th>
                               <th style="height:5px;">CP</th>
                               <th style="height:5px;">Leistung</th></tr></thead><tbody>';

                        $tHead = false;
                    }
                    $output .= '<tr><td style="width: 60px">' . $row[2] . '</td>
                    <td style="width: 300px"> <a href="index.php?sid=17&action=showDetails&module=' 
                            . $row[2] . '">' . $row[3] . '</a></td>
                    <td style="width: 150px">' . $row[5] . '</td>
                         <td style="width:55px;">' . $row[6] . '</td>
                         <td style="width:35px;">' . $row[7] . '</td>';

                    $output .= '<td style="width: 100px">' . $row[12] . '</td></tr>';

                    //Laufvariable = aktuelles Semester
                    $currentSem = $semester;
                    $currentstudyPath = $row[8];
                }
            }
        }
        $output .= "</tbody></table>";

        //wenn Vertiefungsrichtung gesetzt
        if ($_args[2] != 0)
        {
            $sqlPraefix = 'WHERE l.vertiefung_id = ' . $_args[2];
        }
        else
        {
            //alle Vertiefungsrichtungen des Studiengangs
            $sqlPraefix = 'WHERE v.studiengang_id = ' . $_args[1];
        }

        $resSpec = $this->db->select("SELECT m.modulNr, m.bezeichnung, ifnull(t.modulNr,m.modulNr), 
                               ifnull(t.bezeichnung,m.bezeichnung), l.regelsemester, s.bezeichnung, l.ects, 
                               l.sws, v.bezeichnung, t.aktiv, m.aktiv,l.leistung	          
                               FROM modul m LEFT JOIN lehrveranstaltung l ON l.modul_modulnr = m.modulNr    
                                     LEFT JOIN teilmodul t ON l.teilmodul_modulnr = t.modulNr 
                                     JOIN status s ON l.status_id = s.id
                                     JOIN studiengang sg ON sg.id = l.studiengang_id
                                     JOIN vertiefung v ON v.id = l.vertiefung_id
                                     $sqlPraefix ORDER BY 10,1", "mysql_fetch_array");

        $nofSubMod = $this->db->numRows;

        $entries = $nofMod + $nofSubMod;

        //Wenn Module vorhanden
        if ($this->db->numRows)
        {
            $output .= "<br><h2 class='postheadericon'>Module - Vertiefungsrichtungen</h2><br>";

            //Ausgabe Modul, gegliedert nach Semester
            foreach ($resSpec as $row)
            {
                //Wenn Hauptmodul und aktivert
                if ($row[10])
                {
                    //Wenn Teilmodul und aktiviert, oder Hauptmodul
                    if ($row[9] || ($row[0] == $row[2]))
                    {
                        $areaOfSpec = $row[8];

                        //Wenn neues Semeseter, erstelle &#271;&#380;&#733;berschrift
                        if ($currentAofSpec != $areaOfSpec)
                        {
                            if ($areaOfSpec != 1)
                            {
                                $output .= "</tbody></table>";
                            }

                            $output .= '<h4 class="postheadericon" style="margin: 20px 0px -10px 40px">' . $areaOfSpec . '</h4>';
                            $output .= '<table id="myTable' . $i . '" class="tablesorter" style="width: 600px; 
                                                                margin-left:40px; border:0;" cellspacing="1">';
                            $tHead = true;
                        }
                        //Wenn Tabellenkopf
                        if ($tHead)
                        {
                            $output .= '<thead><tr><th style="height:5px;">Modul</th>
                                     <th style="height:5px;">Bezeichnung</th>
                                     <th style="height:5px;">Status</th>
                                     <th style="height:5px;">FS</th>
                                     <th style="height:5px;">SWS</th>
                                     <th style="height:5px;">CP</th>
                                     <th style="height:5px;">Leistung</th></tr></thead><tbody>';

                            $tHead = false;
                        }
                        $output .= '<tr><td style="width: 60px">' . $row[2] . '</td>
                                        <td style="width: 300px"> <a href="index.php?sid=17 
                                        &modul=' . $row[2] . '">' . $row[3] . '</a></td>
                                        <td style="width: 150px">' . $row[5] . '</td>
                                        <td style="width: 40px">' . $row[4] . '</td>
                                             <td style="width:45px;">' . $row[6] . '</td>
                                             <td style="width:40px;">' . $row[7] . '</td>';

                        $output .= '<td style="width: 100px">' . $row[11] . '</td></tr>';

                        //Laufvariable = aktuelles Semester
                        $currentAofSpec = $areaOfSpec;
                        $i++;
                        $k++;
                    }
                }
            }
        }
        $output .= "</tbody></table>";
        //Ausgabe Tabelle mit Abkuerzungen
        $output .='<div style="margin:30px 100px 0px 45px; width:650px;"><h2 class="postheader" ;>
            <span class="postheadericon">Abk&uuml;rzungen </span></h2>
           <table id="abk_legende">
                        <tr>
                              <th  style="width: 30px">FR</th>
                              <td  style="width: 120px">Fachrichtung</td>
                              <th  style="width: 30px">CP</th>
                              <td  style="width: 100px">Credit-Points(ECTS)</td>												
                              <th  style="width: 36px">SL</th>
                              <td >Studienleistung</td>

                        </tr>
                        <tr>
                              <th  style="width: 30px">FS</th>
                              <td  style="width: 120px">Fachsemester</td>
                              <th  style="width: 30px">PL</th>
                              <td  style="width: 100px">Pr&uuml;fungsleistung</td>
                              <th  style="width: 36px">SPL</th>
                              <td >studienbegleitende Pr&uuml;fungsleistung</td>
                        </tr>
                        <tr>
                              <th  style="width: 30px">SWS</th>
                              <td  style="width: 120px">Semesterwochenstunden</td>
                              <th  style="width: 30px">TPL</th>
                              <td  style="width: 100px">Teilpr&uuml;fungsleistung</td>
                              <th  style="width: 36px">STPL</th>
                              <td >studienbegleitende Teilpr&uuml;fungsleistung</td>
                        </tr>
                        </table></div>';

        //Ausgabe des JS Plugin für Tablesorter
        $output .='<script type="text/javascript"> 
  $(document).ready(function() {';
        // call the tablesorter plugin
        for (; $i > 0; $i--)
        {
            $output .= "$('#myTable" . $i . "') .tablesorter({sortList: [[0,0]],widgets: ['zebra'] });\n";
        }

        $output .='})</script>';

        return $output;
    }

//----------------------------------------------------------------------------------------------------------------------

    public function getModuleMajorTimeline($_args = NULL)
    {
        $j = 1;
        $status = implode(",", $_args[3]);
        $output = "";
        $modarray = array('0');
        $sqlPraefix = 'WHERE l.studiengang_id = ' . $_args[1] . '  AND m.status_id IN (' . $status . ') AND m.aktiv = 1';

        //if no area of spec choosed..
        if ($_args[2] != 0)
        {
            $sqlPraefix = 'WHERE l.studiengang_id = ' . $_args[1] . ' AND l.vertiefung_id = ' . $_args[2] . ' 
	                                   AND m.status_id IN (' . $status . ') AND m.aktiv = 1';
        }
        //Abfrage der Module eines Studiengangs
        $queryModul = "SELECT distinct m.modulNr, m.bezeichnung, m.status_id, l.regelsemester, sum(l.sws), sum(l.ects)
	                FROM modul m JOIN lehrveranstaltung l ON l.modul_modulnr = m.modulNr $sqlPraefix 
                      GROUP BY m.bezeichnung
	                ORDER BY 3,1";

        $res = $this->db->select($queryModul, "mysql_fetch_array");

        //Abfrage des h&#271;&#380;&#733;chsten Semesters eines Studiengangs
        $queryNofRs = $this->db->select("SELECT max(regelsemester) AS maxrs FROM lehrveranstaltung 
                                       WHERE studiengang_id = $_args[1]", "mysql_fetch_row");

        $maxRs = $queryNofRs[0][0];

        //Ausgabe der Module
        foreach ($res as $rowModule)
        {

            if ($j == 1)
            {
                //show the legend
                $output .= '<table style="font-size:9pt; margin-top:10px; margin-left:20px; border-collapse: collapse">
				<tr>
				    <th style="width: 70px; " >Legende:</td>
					<td style="width: 25px" class="modpflicht">&nbsp;</td>
					<td  style="width: 80px">Pflichtmodul</td>
					<td style="width: 25px" class="modwpflicht">&nbsp;</td>
					<td  style="width: 120px">Wahlpflichtmodul</td>
					<td style="width: 25px" class="modwahl">&nbsp;</td>
					<td  style="width: 80px">Wahlmodul</td>
				</tr>
				</table>';

                $output .= "<table id='modtimeline'>";
                $output .= "<tr><th style='background-color:#003B7A; color:white;'>Modul</th>
			   <th colspan='2' style='background-color:#003B7A; color:white;'>Bezeichnung</th>	   			   
			   <td></td>";

                //Ausgabe der Spaltenbezeichnung
                for ($j; $j <= $maxRs; $j++)
                {
                    $output .= '<th>' . $j . ' Sem.</th> ';
                }
                $output .= "</tr>";
            }

            //Abfragen der Semester eines Moduls 
            $queryrs = "SELECT l.modul_modulnr, l.regelsemester FROM lehrveranstaltung l   
		  		   WHERE l.studiengang_id = $_args[1] AND l.modul_modulnr = '$rowModule[0]'
		               ORDER BY 2";

            $resRs = $this->db->select($queryrs, "mysql_fetch_array");
            $numRows = $this->db->numRows;

            $queryMinRs = $this->db->select("SELECT min(regelsemester) AS minrs FROM lehrveranstaltung 
                                             WHERE studiengang_id = $_args[1] AND modul_modulnr = '$rowModule[0]'"
                    , "mysql_fetch_array");

            $minRs = $queryMinRs[0][0];

            //Laufvariable fuer Nachfolger
            $temp = $minRs;
            $colspan = 1;
            $setMin = true;
            $ects = true;

            //Array fuer verschiedene Zeilenformatierungen
            $stylearray = array(1 => "modpflicht", 2 => "modwpflicht", 3 => "modwahl",
                4 => "tmodpflicht", 5 => "tmodwpflicht", 6 => "tmodwahl");

            //Schleife fuer Semesterformatierung
            foreach ($resRs as $rowRs)
            {
                //Wenn aktuelles Sem. direkter Nachfolger von Vorhergehenden
                if (($rowRs[1] - $temp) == 1)
                {
                    if ($setMin)
                    {
                        $setMin = false;
                        $temp = $rowRs[1];
                    }
                    //Setze Zellenformatierung fuer direkt nacheinander folgende Semester
                    $colspan++;
                    //Temporaeres Sem. = Aktuelles
                }
                else
                {
                    //Sammle einzelne Regelsemester mit CP im Array
                    //Addiere auf das Semester die Spaltenverschiebung fuer $i
                    $modarray = array($rowRs[1] + 4);
                    $temp = $rowRs[1];
                }
            }
            //Ausgabe des Moduls mit Formatierung
            $output .= "<tr>";

            for ($i = 1; $i <= $maxRs + 4; $i++)
            {

                if ($i == 1)
                {
                    $output .='<td style="width: 50px; text-align:center;" class="' . $stylearray[$rowModule[2]] . '">'
                            . $rowModule[0] . '</td>';
                }
                //Bezeichnungsspalte mit Link zu Moduldetails
                else if ($i == 2)
                {
                    $output .='<td style="min-width: 200px; max-width: 335px; padding-left:5px;" 
                               class="' . $stylearray[$rowModule[2]] . '">
	         		       <a href="index.php?sid=17&action=showDetails&module='
                                  . $rowModule[0] . '">' . $rowModule[1] . '</a></td>';
                }
                else if ($i == 3)
                {
                    $output .='<td style="width: 5px;" class="' . $stylearray[$rowModule[2]] . '">';
                    if ($colspan > 1)
                        $output .='<a href="javascript:toggle(' . $rowModule[0] . ');">
                              <img src="/images/plus_icon.gif" alt="Teilmodule"></img></a></td>';
                }
                else if ($i == 4)
                {
                    $output .='<td style="width: 5px; border: 1px solid #FFFFFF">&nbsp;</td>';
                }

                //Zellenformatierung fuer direkte Nachfolger
                //Verbinde aufeinander folgende Spalten (Semester)
                else if (($i == ($minRs + 4)) && ($colspan > 1))
                {
                    $output .='<td style="text-align:center;" class="' . $stylearray[$rowModule[2]] . '" 
                           colspan="' . $colspan . '">' . $rowModule[5] . ' CP</td>';

                    //nach Colspan-Formatierung zu erstellende Zellen reduzieren
                    $i += $colspan - 1;
                    //ECTS werden in einer nachfolgenden Spalte nicht mehr angezeigt
                    $ects = false;
                    //minimales Semester kann wieder gesetzt werden
                    $setMin = true;
                }

                //Formatierung fuer ein alleinstehendes Semester
                else if (in_array($i, $modarray))
                {
                    if ($ects)
                    {
                        $output .='<td style="text-align:center;" class="' . $stylearray[$rowModule[2]] . '">' . $rowModule[5] . ' CP</td>';
                    }
                    else
                    {
                        $output .='<td style="text-align:center;" class="' . $stylearray[$rowModule[2]] . '"></td>';
                    }
                }
                else
                {
                    //Sonst Formatierung fuer einfache Spalte  
                    $output .='<td style="width: 50px;" class="modsemester">&nbsp;</td>';
                }
            }
            $output .="</tr>";


            //Wenn Teilmodul vorhanden -> Ausgabe mit Formatierung   
            if ($numRows != 0)
            {
                //Abfragen der Teilmodule
                $querySm = "SELECT l.teilmodul_modulnr, l.regelsemester, t.bezeichnung, sum(l.ects)
		  	             FROM lehrveranstaltung l JOIN teilmodul t ON l.teilmodul_modulnr = t.modulNr	  	               
	                         WHERE l.studiengang_id = $_args[1] AND l.modul_modulnr ='$rowModule[0]' AND t.aktiv = 1
	                         GROUP BY l.teilmodul_modulnr
		                   ORDER BY 2";

                $resSm = $this->db->select($querySm, "mysql_fetch_array");

                foreach ($resSm as $rowTmodule)
                {
                    $output .= '<tr class="' . $rowModule[0] . '" style="display:none;" >';
                    for ($k = 1; $k <= $maxRs + 4; $k++)
                    {
                        if ($k == 1)
                        {
                            $output .='<td style="width: 50pxpx; text-align:center;" 
                            class="' . $stylearray[$rowModule[2] + 3] . '">' . $rowTmodule[0] . '</td>';
                        }
                        else if ($k == 2)
                        {
                            $output .='<td style="min-width:200px; max-width: 335px; padding-left:10px;" 
                              class="' . $stylearray[$rowModule[2] + 3] . '" >
                          <a href="index.php?sid=17&action=showDetails&module='.$rowTmodule[0].'">'.$rowTmodule[2].'</a></td>';
                        }
                        else if ($k <= 4)
                        {
                            $output .='<td style="width: 5px; border: 1px solid #FFFFFF" >&nbsp;</td>';
                        }

                        //Wenn aktuelle Spalte = min Regelsemester von TM
                        //Semester Formatierung f&#271;&#380;&#733;r aktuelle Spalte
                        else if ($k == ($rowTmodule[1] + 4))
                        {
                            $output .='<td style="text-align:center;" class="' . $stylearray[$rowModule[2] + 3] . '">'
                                    . $rowTmodule[3] . ' CP</td>';
                        }
                        else
                        {
                            //Sonst Formatierung f&#271;&#380;&#733;r einfache Spalte  
                            $output .='<td style="width: 50px;" class="modsemester">&nbsp;</td>';
                        }
                    }
                    $output .="</tr>";
                }
            }
        }
        $output .="</table>";

        return $output;
    }

//----------------------------------------------------------------------------------------------------------------------

    public function showModuleList($_args = NULL)
    {

        $nofTmod = 0;
        $nofMod = 0;
        $subject = (isset($_args[0])) ? $_args[0] : 0;
        $major = $_args[1];
        $modType = (isset($_args[2])) ? $_args[2] : 0;
        $owner = (($_args[3]) != 0) ? ' AND a.persnr = '.$_args[3] : '';
        $modInactive = ($_args[4] == 1) ? ' AND m.aktiv = 0' : '';

        //Dynamische Auswahl bedingt durch Dropdowns
        if ($subject == 0)
        {
            if ($this->dpload->isAccessAllowed(zugriff_modadmin))
            {
                $sqlpraefix = 'WHERE s.fachrichtung_id IS NOT NULL ' . $modInactive. $owner;
            }
            else if ($this->dpload->isAccessAllowed(zugriff_modbearbeiten))
            {
                $sqlpraefix = 'WHERE f.fakultaet_id = (SELECT fakultaet_id FROM mitarbeiter WHERE 
                                                       account_id = '.Session::get('accountId').')'.$modInactive.$owner;
            }
        }
        else if ($major == 0)
        {
            $sqlpraefix = 'WHERE s.fachrichtung_id = ' . $subject . $modInactive .$owner;
        }
        else
        {
            $sqlpraefix = 'WHERE l.studiengang_id = ' . $major . $modInactive .$owner;
        }

        //Wenn Suchfeld ausgef&#271;&#380;&#733;llt
        if (!empty($_args[5]))
        {
            //Abfragepr&#271;&#380;&#733;fix f&#271;&#380;&#733;r Module  
            $keyword = $_args[5];
            $sqlpraefix = "WHERE m.modulnr like '%" . $keyword . "%' 
                              OR m.bezeichnung like '%" . $keyword . "%'
                              OR concat(a.akadTitel,' ',a.nachname) like '%" . $keyword . "%'
                              OR l.inhalte like '%" . $keyword . "%'
                              OR l.thema like '%" . $keyword . "%'
                              OR l.methoden like '%" . $keyword . "%'
                              OR l.ziele like '%" . $keyword . "%'";
        }

        //Wenn Modulart Hauptmodul
        if (($modType == '0') || ($modType == 'mod'))
        {

            $query = "SELECT m.modulnr as modnr,  m.bezeichnung, l.niveaustufe, 
                             f.bez_kurz, concat(a.akadTitel,' ',a.nachname), m.aktiv, 
                             date_format(log.erstellt, '%d.%m.%y'), date_format(log.geaendert,  '%d.%m.%y'), 
                             l.regelsemester			          
                     FROM lehrveranstaltung l JOIN modul m ON m.modulnr = l.modul_modulnr
                           JOIN studiengang s ON s.id = l.studiengang_id
                           JOIN fachrichtung f ON f.id = s.fachrichtung_id
                           JOIN mitarbeiter a ON a.persnr = m.mitarbeiter_persnr
                           LEFT JOIN lehrender d ON d.lehrveranstaltung_lvnr = l.id
                           LEFT JOIN log ON log.modul_modulnr = m.modulnr AND log.teilmodul_modulnr = -1 
                           $sqlpraefix GROUP BY m.modulnr";

            //Abfrage der Module
            $resModule = $this->db->Select($query, "mysql_fetch_row");

            //Abfrage der Anzahl der Module
            $nofMod = $this->db->numRows;
        }
        //Wenn Modulart Teilmodul, oder alle
        if (($modType == '0' ) || ($modType == 'tmod'))
        {

            $query = "SELECT m.modulnr, m.bezeichnung, l.niveaustufe, f.bez_kurz, concat(a.akadTitel,' ',a.nachname), 
			     m.aktiv, date_format(log.erstellt, '%d.%m.%y'), date_format(log.geaendert,  '%d.%m.%y'), 
                             l.regelsemester 			          
	              FROM lehrveranstaltung l JOIN teilmodul m ON m.modulnr = l.teilmodul_modulnr
	                   JOIN studiengang s ON s.id = l.studiengang_id
	                   JOIN fachrichtung f ON f.id = s.fachrichtung_id
     		           JOIN modul t ON t.modulnr = m.modul_modulnr
	                   JOIN mitarbeiter a ON a.persnr = t.mitarbeiter_persnr
	                   LEFT JOIN lehrender d ON d.lehrveranstaltung_lvnr = l.id
	                   LEFT JOIN log ON log.teilmodul_modulnr = m.modulnr
	                   $sqlpraefix GROUP BY m.modulnr";

            //Abfrage der Module

            $resTmodule = $this->db->select($query, "mysql_fetch_row");

            //Abfrage der Anzahl der Module
            $nofTmod = $this->db->numRows;

            //Anzahl der gesamten gefundenen Eintr&auml;e
            
        }
        $entries = $nofMod + $nofTmod;
        
        //wenn keine Module f&#271;&#380;&#733;re Suche vorhanden, result = query von Teilmodul
        if (!$nofMod)
        {
            $resModule = $resTmodule;
        }

        //Ausgabe Tabellenkopf
        $output.= '<div id="seiten" style="float:right">
                <form method="post">
                    <span style="font-size:10pt">Anzahl: </span><select  class="pagesize">
                        <option selected="selected" value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                    </select>&nbsp;&nbsp;&nbsp;
                    <img style="vertical-align:middle;" alt="first" src="../images/first.png" class="first">
                    <img  style="vertical-align:middle;" alt="prev" src="../images/prev.png" class="prev">
                    <input type="text" id="page" class="pagedisplay" style="width: 30px; height: 14px">
                    <img  style="vertical-align:middle;" alt="next"src="../images/next.png" class="next">
                    <img style="vertical-align:middle;" alt="last" src="../images/last.png" class="last">
                </form>
            </div>';
        $output.= '<table id="myTable" class="tablesorter" style="border:0;" cellspacing="1" >
              <thead>
		<tr>
		<th style="width: 65px; height: 22px;">Modul</th>
		<th style="width: 260px; height: 22px;">Bezeichnung</th>
		<th style="width: 35px; height: 22px;">Art</th>
		<th style="width: 28px; height: 22px;">FR</th>
		<th style="width: 35px; height: 22px;">FS</th>
		<th style="width: 50px; height: 22px;">Niveau</th>
		<th style="width: 125px; height: 22px;">Vertantwortung</th>
		<th style="width: 40px; height: 22px;">akt.</th>
		<th style="width: 120px; height: 22px;">Aktion</th>
	     </tr>
	     </thead>
             <tbody>';

        //Zeilenweise Ausgabe der Module
        foreach ($resModule as $rowModule)
        {
            $modType = ($resModule == $resTmodule) ? 'TM' : 'M';

            $output.= '  
            <tr>
                <td class="spancenter" style="width: 60px;" >' . $rowModule[0] . '</td>
                <td style="width: 260px;"><span class="auto-style3"><strong>' . $rowModule[1] . '</strong></span><br>
                <span class="modspan_small">erstellt: ' . $rowModule[6] . '&nbsp;&nbsp; ge&auml;ndert: ' . $rowModule[7] . '</span></td>
                <td class="spancenter" style="width: 35px;">' . $modType . '</td>
                <td class="spancenter" style="width: 35px;">' . $rowModule[3] . '</td>
                <td class="spancenter" style="width: 35px;">X</td>
                <td class="spancenter" style="width: 55px;">' . $rowModule[2] . '</td>
                <td class="spancenter" style="width: 120px;">' . $rowModule[4] . '</td>
                <td class="spancenter" style="width: 40px;">';

            //Wenn Modul deaktiviert
            if ($rowModule[5] == 0)
            {
                $output.= '<a href="javascript:callAjaxRouter(\'act\', \'actMod\',\''.$rowModule[0]. '\')" id="act">
		      <img alt="deaktiviert" src="../images/deactivate_icon.png" style="height: 16px; width: 16px"></a>';
            }
            else
            {
                $output.= '<a href="javascript:callAjaxRouter(\'deact\', \'deactMod\',\''.$rowModule[0]. '\')" id="deact">
	              <img alt="aktiviert" src="../images/check_icon.png" style="height: 16px; width: 16px"></a>';
            }

            $output.= '</td>										
			<td style="width: 120px; ">&nbsp;<a href="index.php?sid=17&modul=' . $rowModule[0] . '">
                          <img alt="anzeigen"  src="../images/show_icon.png" class="icon"></a>
			<a href="index.php?sid=20&edit=' . $rowModule[0] . '">
                          <img alt="bearbeiten" src="../images/edit_icon.png" class="icon"></a>
			<a href="javascript:callAjaxRouter(\'del\', \'delMod\',\''.$rowModule[0]. '\')" id="del">
                          <img alt="l&#271;&#380;&#733;schen"  src="../images/delete_icon.png" class="icon"></a></td>
		     </tr>';

            //Wenn Modul mit Teilmodul vorhanden
            if ($nofTmod != 0 && $nofMod != 0)
            {

                //Ausgabe der Teilmodule
                foreach ($resTmodule as $rowTmodule)
                {

                    $output.= ' <tr>
                        <td class="spancenter" style="width: 63px;" >' . $rowTmodule[0] . '</td>
                        <td style="width: 260px;"><span class="auto-style3"><strong>' . $rowTmodule[1] . '</strong></span>
                        <br><span class="modspan_small">erstellt: ' . $rowTmodule[6] . '&nbsp;&nbsp; ge&auml;ndert '
                            . $rowTmodule[7] . '</span></td>
                        <td class="spancenter" style="width: 35px;">TM</td>
                        <td class="spancenter" style="width: 32px;">' . $rowTmodule[3] . '</td>
                        <td class="spancenter" style="width: 35px;">' . $rowTmodule[8] . '</td>
                        <td class="spancenter" style="width: 50px;">' . $rowTmodule[2] . '</td>
                        <td class="spancenter" style="width: 120px;">' . $rowTmodule[4] . '</td>
			<td class="spancenter" style="width: 40px;">';

                    //Wenn Modul deaktiviert
            //Wenn Modul deaktiviert
            if ($rowTmodule[5] == 0)
            {
                $output.= '<a href="javascript:callAjaxRouter(\'act\', \'actMod\',\''.$rowTmodule[0]. '\')" id="act">
		      <img alt="deaktiviert" src="../images/deactivate_icon.png" style="height: 16px; width: 16px"></a>';
            }
            else
            {
                $output.= '<a href="javascript:callAjaxRouter(\'deact\', \'deactMod\',\''.$rowTmodule[0]. '\')" id="deact">
	              <img alt="aktiviert" src="../images/check_icon.png" style="height: 16px; width: 16px"></a>';
            }

            $output.= '</td>										
			<td style="width: 120px; ">&nbsp;<a href="index.php?sid=17&modul=' . $rowTmodule[0] . '">
                          <img alt="anzeigen"  src="../images/show_icon.png" class="icon"></a>
			<a href="index.php?sid=20&edit=' . $rowTmodule[0] . '">
                          <img alt="bearbeiten" src="../images/edit_icon.png" class="icon"></a>
			<a href="javascript:callAjaxRouter(\'del\', \'delMod\',\''.$rowTmodule[0]. '\')" id="del">
                          <img alt="l&#271;&#380;&#733;schen"  src="../images/delete_icon.png" class="icon"></a></td>
		     </tr>';

                    
                    $nofTmod--;
                }
            }
        }
        //Wenn suche abgesendet zeige ANzahl Suchtreffer
        if (!empty($_REQUEST['search']))
        {

            $output.= '<span>Suche nach "' . $keyword . '" ergab ' . $entries . ' Treffer</span>';
        }
        else
        {
            $output.= 'Treffer gesamt:' . $entries;
        }
        //schlie&#271;&#380;&#733;e Tabelle
        $output.= '</tbody></table>';

        return $output;
    }

}

?>