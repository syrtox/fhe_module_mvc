<div class="postmetadataheader">
    <h2 class="postheader"><span class="postheadericon">
            Modul anlegen
        </span></h2>
</div>
<div class="postcontent">
    <div id="addMod">
        <form method="post" name="addMod" onsubmit="return checkModForm();" action="<?php echo $this->input['action']; ?>">
            <fieldset name="addMod">
                <legend>Modul anlegen<br></legend>
                <div style="font-size:14px; margin-bottom:10px;">
                </div>
                <div style="float:right; text-align:right; margin-bottom:20px;">

                    <a href="javascript:history.back();" >
                    zur &Uuml;bersicht</a><br><!--<strong>Modul:</strong> 
                          <input name="modType" type="radio" value="1">aktiviert&nbsp;
                          <input name="modType" type="radio" value="2">deaktiviert
                    -->				</div>
                <table>
                    <tr class="tHead">
                        <td colspan="9">&nbsp;
                            <span class="postheadericon"><b>Allgemeine Informationen&nbsp;</b></span><hr></td>
                    </tr>
                    <tr>
                        <td colspan="8" style="height: 1px; background-color: #FFFFFF"></td>
                        <td style="height: 2px; background-color: #FFFFFF; width: 0px;"></td>
                    </tr>
                    <tr>
                        <th style="width: 100px; background-color: #FFFFFF;">
                            Modulart:*</th>
                        <td colspan="3" style="background-color: #FFFFFF; width: 240px;" valign="top">
                            <select name="modType" id="modType" style="width: 190px" onchange="this.form.submit();">
                                <option <?php if ($this->input['post']['modType'] == "mod") echo 'selected'; ?> 
                                    value="mod">Hauptmodul ohne Teilmodul</option>
                                <option <?php if ($this->input['post']['modType'] == "modt") echo 'selected'; ?> 
                                    value="modt">Hauptmodul mit Teilmodul</option>
                                <option <?php if ($this->input['post']['modType'] == "tmod") echo 'selected'; ?> 
                                    value="tmod">Teilmodul von Hauptmodul</option>
                            </select></td>
                        <td style="text-align: right; height: 10px; background-color: #FFFFFF; width: 110px;">
                            <b>Modulnr.*</b></td>
                        <td style="width: 91px; height: 10px; background-color: #FFFFFF;">
                            <input onchange="this.form.submit();" name="moduleNr" id="modNr" style="width: 60px" 
                                   type="text" value="<?php echo $this->input['post']['moduleNr']; ?>"></td>
                        <td style="text-align: right; width: 97px; height: 10px; background-color: #FFFFFF;">
                            <b>Teilmodulnr.*</b></td>
                        <td style="height: 10px; background-color: #FFFFFF; ">
                            <input  name="tmoduleNr" id="tmodNr" style="width: 64px" type="text" 
                                    value="<?php echo $this->input['post']['tmoduleNr']; ?>"></td>
                        <td style="height: 10px; background-color: #FFFFFF; width: 0px;">
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 100; background-color: #FFFFFF;" valign="top">
                            Bezeichnung*</th>
                        <td colspan="3" style="background-color: #FFFFFF; width: 240px;" valign="top">
                            <input name="modDescrb" id="modDescrb" style="width: 240px" type="text"
                                   value="<?php echo $this->input['post']['modDescrb']; ?>"></td>
                        <td style="height: 40px; text-align:right; background-color: #FFFFFF; width: 110px;" valign="top">
                            <b>Status*</b></td>
                        <td style="height: 40px; width: 91px; background-color: #FFFFFF;" valign="top">
                            <select name="status" id="status"style="width: 95px">
                                <option value="0" >ausw&auml;hlen</option>
                                <option value="1" <?php if ($this->input['post']['status'] == 1) echo 'selected = "selected"'; ?>>
                                    Pflichtmodul</option>
                                <option value="2" <?php if ($this->input['post']['status'] == 2) echo 'selected = "selected"'; ?>>
                                    Wahlpflicht</option>
                                <option value="3" <?php if ($this->input['post']['status'] == 3) echo 'selected = "selected"'; ?>>
                                    Wahlmodul</option>
                            </select></td>
                        <th style="height: 40px; width: 97px; background-color: #FFFFFF;">
                            Niveaustufe*</th>
                        <td style="height: 40px; background-color: #FFFFFF; " valign="top">
                            <input name="niveau" type="radio" checked="checked" value="Bachelor">BA<br>
                            <input name="niveau" type="radio" <?php if ($this->input['post']['niveau'] == 'Master')
                             echo 'checked="true"';?> value="Master">MA</td>
                        <td style="height: 40px; background-color: #FFFFFF; width: 0px;"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #FCFCFC; height: 5px;" colspan="9">
                    <hr style="background-color: #FFFFFF"></th>
                    </tr>
                    <tr>
                        <th style="width: 100px; background-color: #FCFCFC; height: 27px;">
                            Studiengang*</th>
                        <td colspan="4" style="background-color: #FCFCFC; height: 27px;">
                            <select name="major" id="studyPath" style="width: 260px" onchange=this.form.submit();>						
                                <?php
                               
                                ?>
                            </select></td>
                        <th style="width: 91px; background-color: #FCFCFC;" rowspan="3">
                            Dozent(in)*<br><input id="chooseLecturer" type="button" value="ausw&auml;hlen" onclick="makeRemote()"></th>
                        <td colspan="2" style="background-color: #FCFCFC; width : 160px;" rowspan="3" valign="top">
                            <select multiple="multiple" name="lecturer[]" id="lecturer" style="width: 160px; height: 95px">
                                <?php
                             
                                ?>
                            </select></td>
                        <td style="background-color: #FCFCFC; width: 0px;" rowspan="3"></td>
                    </tr>
                    <tr>
                        <th style="width: 100px; background-color: #FCFCFC;" rowspan="2">
                            Voraussetzung<br><span class="modspan">f&uuml;r anderes Modul 
                                -&gt;</span></th>
                        <td colspan="4" style="height: 19px; background-color: #FCFCFC" valign="top">
                            <input name="requiredForMod" id="requiredForMod" style="width: 256px" type="text" 
                                   value="<?php echo $this->input['post']['requiredForMod']; ?>" placeholder="f&uuml;r dieses Modul"><br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="height: 45px; background-color: #FCFCFC" valign="top">
                            <input name="isRequired" id="isRequired" style="width: 256px" type="text" 
                                   value="<?php echo $this->input['post']['isRequired']; ?>" placeholder="f&uuml; andere Module"></td>
                    </tr>
                    <tr>
                        <th style="background-color: #FCFCFC; height: 5px;" colspan="9">
                    <hr style="background-color: #FFFFFF"></th>
                    </tr>

                    <tr class="tmoddetails">
                        <th style="border-width: 1px; width: 100px; background-color: #F7F7F7; ">
                            <label id="reglab" for="regist">Anmeldung*</label></th>
                        <td style="border-width: 1px; width: 90px; background-color: #F7F7F7; ">
                            <input name="regist" id="reg0" type="radio" value="keine" 
                                   <?php if ($this->input['post']['regist'] == 'keine') echo 'checked="true"'; ?>>keine<br>
                            <input name="regist" id="reg1" type="radio" value="pflicht" 
                                   <?php if ($this->input['post']['regist'] == 'pflicht') echo 'checked="true"'; ?>>pflicht</td>
                        <th colspan="2" style="border-width: 1px; background-color: #F7F7F7; ">
                            <label id="readingLoclab" for="readingLoc">Veranstaltungsort*</label> </th>
                        <td rowspan="2" style="border-width: 1px; width: 110px; background-color: #F7F7F7; " valign="top">
                            <input name="readingLoc[]" id="readingLoc1" type="checkbox" 
                              <?php if (in_array(html_entity_decode("H&ouml;rsaal", ENT_QUOTES, "UTF-8"), 
                               $this->input['post']['readingLoc'])) echo 'checked="true"'; ?> value="H&ouml;rsaal">H&ouml;rsaal<br>
                            <input name="readingLoc[]" id="readingLoc2 "type="checkbox" 
                                   <?php if (in_array('Seminarraum', $this->input['post']['readingLoc']))
                                    echo 'checked="true"'; ?> value="Seminarraum">Seminarraum<br>
                            <input name="readingLoc[]" id="readingLoc" type="checkbox"  
                                   <?php if (in_array('Labor', $this->input['post']['readingLoc']))
                                           echo 'checked="true"'; ?> value="Labor">Labor<br>
                            <input name="readingLoc[]" id="readingLoc4" type="checkbox" 
                                   <?php if (in_array('Exkursion', $this->input['post']['readingLoc'])) 
                                           echo 'checked="true"'; ?> value="Exkursion">Exkursion</td>
                        <th rowspan="2" style="border-width: 1px; width: 91px; background-color: #F7F7F7; ">
                            <label id="readingTypelab" for="readingType">Veranstaltung</label></th>
                        <td colspan="2" rowspan="2" style="border-width: 1px; background-color: #F7F7F7; " valign="top">
                            <input name="readingType[]" type="checkbox" id="readingType1" 
                                   <?php if (in_array('Vorlesung', $this->input['post']['readingType'])) 
                                           echo 'checked="true"'; ?> value="Vorlesung">Vorlesung<br>
                            <input name="readingType[]" type="checkbox" id="readingType2"
                                   <?php if (in_array(html_entity_decode("&Uuml;bung", ENT_QUOTES, "UTF-8"), 
                           $this->input['post']['readingType'])) echo 'checked="true"'; ?> value="&Uuml;bung">&Uuml;bung<br>
                            <input name="readingType[]" id="readingType3" type="checkbox" 
                                   <?php if (in_array('Labor', $this->input['post']['readingType']))
                                           echo 'checked="true"'; ?> value="Labor">Labor<br>
                            <input name="readingType[]" id="readingType4" type="checkbox"
                                   <?php if (in_array('Exkursion', $this->input['post']['readingType'])) 
                                           echo 'checked="true"'; ?> value="Exkursion">Exkursion</td>
                        <td rowspan="2" style="border-width: 1px; background-color: #F7F7F7; width: 0px;">
                            &nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <td style="width: 100px; background-color: #F7F7F7; height: 37px; text-align: right;">
                            <strong>Teilnehmer*</strong></td>
                        <td style="background-color: #F7F7F7; vertical-align:middle; height: 37px;" colspan="3">
                            <input name="subscriber" id="subscriber" style="width: 36px" type="number" 
                                   value="<?php echo $this->input['post']['subscriber']; ?>"> 
                            max.</td>
                    </tr>
                    <tr class="tkopf">
                        <td colspan="9"> 
                     <span class="postheadericon"><strong>&nbsp;Informationen zu Dauer und Angebot</strong></span><hr></td>
                    </tr>
                    <tr class="tmoddetails">
                        <td style="height: 1px; background-color: #F7F7F7;" colspan="9"></td>
                    </tr>
                    <tr class="tmoddetails">
                        <th style="width: 100px; background-color: #F7F7F7; height: 6px;">
                            Regelsemester*</th>
                        <td colspan="2" style="width: 111px; height: 6px; background-color: #F7F7F7;" valign="top">
                            <input name="regularSem" id="regularSem" style="width: 36px" type="number" 
                                 value="<?php echo $this->input['post']['regularSem']; ?>"> 
                            <span class="modspan">FS</span></td>
                        <th style="background-color: #F7F7F7; width: 79px;">Pr&auml;senszeit*</th>
                        <td style="padding:0px; background-color: #F7F7F7; width: 110px;">
                            <font size="2">
                            <input name="pz" id="pz" style="width: 36px" type="number" 
                                   value="<?php echo $this->input['post']['pz']; ?>">&nbsp;Stunden</font></td>
                        <td style="height: 6px; background-color: #F7F7F7; text-align: left;" valign="top" colspan="3">
                            &nbsp; 
                            <strong><font size="2">ECTS*</font> </strong>
                            <input name="cp" id="cp" style="width: 36px" type="number" 
                                   value="<?php echo $this->input['post']['cp']; ?>">&nbsp;&nbsp;&nbsp;
                            <strong><font size="2">SWS*</font> </strong>
                            <input name="sws" id="sws" style="width: 36px" type="number" 
                                  value="<?php echo $this->input['post']['sws']; ?>"></td>
                        <td style="height: 6px; background-color: #F7F7F7; width: 0px;" valign="top">
                        </td>
                    </tr>
                    <tr class="tmoddetails">
                        <th style="width: 100px; background-color: #F7F7F7;" rowspan="2">
                            <label id="regularitylab" for="regularity">Regelm&auml;&szlig;igkeit des Angebots</label></th>
                        <td colspan="2" rowspan="2" style="width: 111px; background-color: #F7F7F7;" valign="top">
                            <input name="regularity[]" id="regularity1" type="checkbox" value="Wintersemester"
                                   <?php if (in_array('Wintersemester', $this->input['post']['regularity']))
                                           echo 'checked="true"'; ?> >
                            <span class="modspan">WS</span><br>
                            <input name="regularity[]" id="regularity2" type="checkbox" value="Sommersemester"
                               <?php if (in_array('Sommersemester', $this->input['post']['regularity']))
                             echo 'checked="true"'; ?> ><font size="2">
                            SS</font></td>
                        <th valign="top" style="padding:0px; background-color: #F7F7F7; width: 79px;">
                            Vorbereitungszeit*&nbsp;</th>
                        <td valign="top" style="padding:0px; background-color: #F7F7F7; width: 110px;">
                            <input name="vz" id="vz" style="width: 36px" type="number"
                                   value="<?php echo $this->input['post']['vz']; ?>"><font size="2"> 
                            Stunden</font></td>
                        <td style="background-color: #F7F7F7; vertical-align:bottom;"  colspan="3">
                            <strong>&nbsp; Verwendbarkeit</strong></td>
                        <td style="background-color: #F7F7F7; width: 0px;">&nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <th valign="top" style="padding:0px; background-color: #F7F7F7; width: 79px;">
                            Selbsstudienzeit*&nbsp; </th>
                        <td valign="top" style="padding:0px; background-color: #F7F7F7; width: 110px;">
                            <input name="sz" id="sz" style="width: 36px" type="number"
                                   value="<?php echo $this->input['post']['sz']; ?>"><font size="2"> Stunden</font></td>
                        <td style="background-color: #F7F7F7;" valign="top" colspan="3" rowspan="2">                          &nbsp;
                            <textarea name="usability" id="usability" style="height: 22px; width: 240px" rows="3"
                                      value=""><?php echo $this->input['post']['usability']; ?></textarea></td>
                        <td style="background-color: #F7F7F7; width: 0px;">&nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <th style="width: 100px; background-color: #F7F7F7; height: 1px;">
                        </th>
                        <td colspan="3" style="background-color: #F7F7F7; height: 1px;" valign="top">
                        </td>
                        <td valign="top" style="padding:0px; background-color: #F7F7F7; width: 110px; height: 1px;">
                        </td>
                        <td style="background-color: #F7F7F7; height: 1px; width: 0px;"></td>
                    </tr>
                    <tr class="tmoddetails">
                        <td style="height: 2px; background-color: #F7F7F7;" colspan="9"></td>
                    </tr>
                    <tr class="tkopf">
                        <td colspan="9"> <span class="postheadericon">
                                <strong>&nbsp;Informationen zum vermittelten Inhalt&nbsp;  </strong></span><hr></td>
                    </tr>
                    <tr class="tmoddetails">
                        <td style="width: 100px; height: 1px;"></td>
                        <td colspan="4" style="height: 1px"></td>
                        <td style="width: 91px; height: 1px;"></td>
                        <td colspan="2" style="height: 1px"></td>
                        <td style="height: 1px; width: 0px;">&nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <th style="width: 100px; height: 21px;">Thema</th>
                        <td colspan="7" style="height: 21px">
                            <input name="topic" id="topic" style="width: 350px" type="text"
                                   value="<?php echo $this->input['post']['topic']; ?>"></td>
                        <td style="height: 21px; width: 0px;">&nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <th style="width: 100px">Inhalt*</th>
                        <td colspan="7">
                            <textarea name="contents" id="content" style="height: 130px; width: 550px"
                                      ><?php echo $this->input['post']['content']; ?></textarea></td>
                        <td style="width: 0px">&nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <th style="width: 100px">Ziele*</th>
                        <td colspan="7">
                            <textarea cols="20" name="targets" id="targets" rows="1" style="height: 130px; width: 550px"
                                      ><?php echo $this->input['post']['targets']; ?></textarea></td>
                        <td style="width: 0px">&nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <th style="width: 100px">Methoden*</th>
                        <td colspan="7">
                            <textarea cols="20" name="methodes" id="methodes" rows="1" style="height: 130px; width: 550px"
                                      value=""><?php echo $this->input['post']['methodes']; ?></textarea></td>
                        <td style="width: 0px">&nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <th style="width: 100px" rowspan="2"><label id="languagelab" for="language">Sprache*</label></th>
                        <td colspan="4" rowspan="2">
                        <input name="language[]" id="language1" type="checkbox" value="deutsch"
                               <?php if (in_array('deutsch', $this->input['post']['language'])) echo 'checked="true"'; ?>>deutsch
                        <input name="language[]" id="language2" type="checkbox" value="englisch"
                               <?php if (in_array('englisch', $this->input['post']['language'])) echo 'checked="true"'; ?>>englisch
                        <input name="language[]" id="language3" type="checkbox" value="franz&#65533;sisch"
                               <?php if (in_array('franz&#65533;sisch', $this->input['post']['language'])) echo 'checked="true"'; ?>>franz&ouml;sisch
                        <input name="language[]" id="language4" type="checkbox" value="russisch"
                               <?php if (in_array('russisch', $this->input['post']['language'])) echo 'checked="true"'; ?>>russisch
                        <br>
                        <input name="language[]" id="language5" type="checkbox" value="spanisch"
                               <?php if (in_array('spanisch', $this->input['post']['language'])) echo 'checked="true"'; ?>>spanisch
                        <input name="language[]" id="language6" type="checkbox" value="italienisch"
                               <?php if (in_array('italienisch', $this->input['post']['language'])) echo 'checked="true"'; ?>> 
                                  italienisch
                        <input name="language[]" id="language7" type="checkbox" value="chinesisch"
                          <?php if (in_array('chinesisch', $this->input['post']['language'])) echo 'checked="true"'; ?>>
                                  chinesisch </td>
                        <td style="width: 91px" valign="top">&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                        <td style="width: 0px">&nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <td colspan="3" valign="top">
                            &nbsp;</td>
                        <td valign="top" style="width: 0px">
                            &nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <td style="width: 100px; height: 2px;"></td>
                        <td colspan="4" style="height: 2px"></td>
                        <td style="width: 91px; height: 2px;"></td>
                        <td colspan="2" style="height: 2px"></td>
                        <td style="height: 2px; width: 0px;">&nbsp;</td>
                    </tr>
                    <tr class="tkopf" >
                        <td colspan="9"> <span class="postheadericon"><strong>
                                    &nbsp;Informationen zu der Pr&uuml;fung&nbsp;</strong></span><hr></td>
                    </tr>
                    <tr >
                        <td style="width: 100px; height: 2px;"></td>
                        <td colspan="4" style="height: 2px"></td>
                        <td style="width: 91px; height: 2px;"></td>
                        <td colspan="2" style="height: 2px"></td>
                        <td style="height: 2px; width: 0px;">&nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <th style="width: 100px">Pr&uuml;fungsart*</th>
                        <td colspan="4">
                            <input name="examType" id="examType" style="width: 282px" type="text"
                                   value="<?php echo $this->input['post']['examType']; ?>"></td>
                        <td colspan="3"style="vertical-align:bottom;"><strong>
                                Pr&uuml;fungsart*
                            </strong><font size="2"><strong>(Wdh.)</strong></font></td>
                        <td valign="bottom" style="width: 0px">&nbsp;</td>
                    </tr>
                    <tr class="tmoddetails">
                        <th style="width: 100px">Vorraussetzung Teilnahme* </th>
                        <td colspan="4">
                            <textarea name="requiredForExam" id="requiredForExam" style="height: 40px; width: 280px"
                                      value=""><?php echo $this->input['post']['requiredForExam']; ?></textarea></td>
                        <td style="padding-bottom:0px;" colspan="3" valign="top">
                            <input name="examTypeRep" id="wdhexamType" style="width: 250px" type="text"
                                   value="<?php echo $this->input['post']['examTypeRep']; ?>"><br>
                        </td>
                        <td style="padding-bottom:0px; width: 0px;" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <th style="width: 100px; height: 5px;"> </th>
                        <td colspan="4" style="height: 5px; padding-bottom:0px;" valign="bottom">
                            <strong>Gewichtung der Note*</strong></td>
                        <td style="padding-bottom:0px; height: 5px;" colspan="3" valign="bottom">
                            <span ><strong>Leistungsnachweis*</strong></span></td>
                        <td style="padding-bottom:0px; height: 5px; width: 0px;" valign="bottom">
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100px; text-align:right; vertical-align:top;" rowspan="2">
                            &nbsp;</td>
                        <td colspan="4" rowspan="2" valign="top">
                            <input name="weightMark" id="weightMark" style="width: 282px" type="text"
                                   value="<?php echo $this->input['post']['weightMark']; ?>"></td>
                        <td colspan="3" valign="top">
                            <select name="performance" id="performance" style="width: 220px">
                            <option <?php if ($this->input['post']['performance'] == '0') echo 'selected = "selected"';?>
                                value="0">keine Auswahl</option>
                            <option <?php if ($this->input['post']['performance'] == 'PL') echo 'selected = "selected"';?>
                                value="PL" >
                                Pr&uuml;fungsleistung</option>
                            <option <?php if ($this->input['post']['performance'] == 'TPL') echo 'selected = "selected"';?>
                                value="TPL">
                                Teilpr&uuml;fungsleistung</option>
                            <option <?php if ($this->input['post']['performance'] == 'STPL') echo 'selected = "selected"';?>
                                value="STPL">
                                studienbegl. Teilpr&uuml;fungsleistung
                            </option>
                            <option <?php if ($this->input['post']['performance'] == 'SPL') echo 'selected = "selected"';?>
                                value="SPL">
                                studienbegl. Pr&uuml;fungsleistung
                            </option>
                            <option <?php if ($this->input['post']['performance'] == 'SL') echo 'selected = "selected"';?>
                                value="SL">Studienleistung</option>
                            </select></td>
                        <td valign="top" style="width: 0px">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 91px">&nbsp;</td>
                        <td colspan="2">&nbsp;</td>
                        <td style="width: 0px">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="9"><hr></td>
                    </tr>
                    <tr>
                        <td style="width: 100px; height: 30px;"></td>
                        <td colspan="4" style="height: 30px"></td>
                        <td style="height: 30px; text-align:right; vertical-align:bottom; padding:10px" colspan="4">
                            <font size="2">*Plichtfelder, m&uuml;ssen ausgef&uuml;llt sein</font><br>
                            <input name="addMod" type="submit" value="speichern" onclick="checkAuswahl();">
                            <input name="reset" type="reset" value="zur&uuml;cksetzen"></td>
                    </tr>
                    <tr>
                        <td style="width: 100px; height: 10px;"></td>
                        <td colspan="4" style="height: 10px"></td>
                        <td colspan="4" style=" height: 10px;">
                        </td>
                    </tr>
                </table>
            </fieldset></form>
    </div>
</div>
