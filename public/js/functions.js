//----------------------------------------------------------------------------------------------------------------------
function submitModAdminFilter() {

    var args = new Array();
    args[0] = $("#subjects option:selected").attr('value');
    args[1] = $("#majors option:selected").attr('value');
    args[2] = $("#modType option:selected").attr('value');
    args[3] = $("#owner option:selected").attr('value');
    
    if ($("#modInactive").attr('checked'))
        {
            args[4] = $("#modInactive").attr('value');
        }
    
    args[5] = $("#search_mod").attr('value');

    $.post("./libs/ajax_router.php?do=showModuleList", {args: args}, function(data) {
        $("#show_elements").html(data);
        $("#myTable").tablesorter({sortList: [[0, 0]], widgets: ['zebra']});
        $("#myTable").tablesorterPager({container: $("#seiten")});
    });

}


//----------------------------------------------------------------------------------------------------------------------
function submitModFilter() {

    var status = document.getElementsByName("status[]");
    var statusarray = new Array();

    for (i = 0; i < status.length; i++)
    {
        if ($(status[i]).attr('checked'))
        {
            statusarray[i] = $(status[i]).attr('value');
        }
        else
        {
            statusarray[i] = 0;
            $(status[i]).removeAttr('checked');
        }

    }

    var args = [];
    args[0] = $("#subjects option:selected").attr('value');
    args[1] = $("#majors option:selected").attr('value');
    args[2] = $("#areaOfSpec option:selected").attr('value');
    args[3] = statusarray;
    args[4] = $("input:radio:checked[name='view']").val();
    args[5] = $("#search_mod").attr('value');

    $.post("./libs/ajax_router.php?do=getModules", {args: args}, function(data) {
        $("#show_elements").html(data);
        $("#myTable").tablesorter({sortList: [[0, 0]], widgets: ['zebra']});
        $("#myTable").tablesorterPager({container: $("#seiten")});
    });

}


//Helper function ajaxRouter for ModuleOverview---------------------------------------------------------------------

function callAjaxRouter(element, action, arg)
{
    
    var screenX = $(window).scrollLeft();
    var screenY = $(window).scrollTop();
    var param = arg || 0;
    var id = param;
    var changeElm = "";
    
    
    if (element == "subjects")
    {
        if ($("#subjects option:selected").attr('value') == 0)
        {
            $("#majors").attr("disabled", "disabled");
            $("input:radio[name='view']").attr("disabled","disabled");
            changeElm = "";
        }
        else
        {
            $("#areaOfSpec").attr("disabled", "disabled");
            $("#areaOfSpec").html("<option>---alle anzeigen---</option>");
            id = $("#subjects option:selected").attr('value');
            changeElm = "#majors";
        }
    }
    
    if (element == "majors")
    {
        id = $("#majors option:selected").attr('value');
        changeElm = "#areaOfSpec";
        $("input:radio[name='view']").removeAttr("disabled");
    }
    
    if (in_array(Array("subMod", "unsubMod","subExam","unsubExam"),element))
    {
        changeElm = "#modDetailsHeader";
    }
    
    if (in_array(Array("act", "deact","del"),element))
    {
        if(element === "del")
            {
                if(!checkForDelete(action)) 
                    return false; 
            }
        changeElm = "#show_elements";
    }
    
    $.post("./libs/ajax_router.php?do=" + action, {id: id}, function(data) {
        window.scrollTo(screenX,screenY);
        $(changeElm).removeAttr("disabled");
        $(changeElm).html(data);
        $("#myTable").tablesorter({sortList: [[0, 0]], widgets: ['zebra']});
        $("#myTable").tablesorterPager({container: $("#seiten")});
    });    
}

//delete Todo--------------------------------------------------------------------------------------
function deleteTodo(id) {

//send confirm
    var check = confirm("Bist du dir sicher?");

//if confirmed
    if (check)
    {
//using ajax, based on jquery
//post the id of the deleting todo to php
        $.ajax({
            type: "POST",
            url: "../temp/settodo.php",
            data: {deltodo: id},
            success: function(result) //request the result
            {
                if (result)
                {
                    alert("erfolgreich!");
                    window.location.reload();
                }

                if (!result)
                    alert("fehlgeschlagen!");
            }
        });
    }
}
//create Todo--------------------------------------------------------------------------------------
function submitTodo() {

    var txt = $("#new").attr("value");

    $.ajax({
        type: "POST",
        url: "../temp/settodo.php",
        data: {newtodo: txt},
        success: function(result)
        {
            if (result)
            {
                alert("Anlegen erfolgreich!");
                window.location.reload();
            }

            if (!result)
                alert("Anlegen fehlgeschlagen!");

        }
    });
}
//change Todo--------------------------------------------------------------------------------------
function changeTodo(id) {

//initialize the vars with the values of the inputs
    var perc = $("#pb" + id + "_percentImage").attr("title");
    var txt = $("#text" + id + "").attr("value");
    var id = $("#id" + id + "").attr("value");
    var prio = $("#prio" + id + "").attr("value");


    $.ajax({
        type: "POST",
        url: "../temp/settodo.php",
        data: {text: txt, id: id, perc: perc, prio: prio},
        success: function(result)
        {
            if (result)
                alert("Speichern erfolgreich!");

            if (!result)
                alert("Speichern fehlgeschlagen!");

        }
    });

    toggleTodo(id)
}

//toogle icons and the visibility of the inputs-------------------------------------------------------------
function toggleTodo(id)
{
    var bm = document.getElementById('bminus' + id + '');
    var bp = document.getElementById('bplus' + id + '');
    var edt = document.getElementById('edit' + id + '');
    var save = document.getElementById('save' + id + '');
    var txt = document.getElementById('text' + id + '');
    var prio = document.getElementById('prio' + id + '');

    bm.style.display = (bm.style.display === "none") ? "" : "none";
    bp.style.display = (bp.style.display === "none") ? "" : "none";
    edt.style.display = (edt.style.display === "none") ? "" : "none";
    save.style.display = (save.style.display === "none") ? "" : "none";
    txt.disabled = (txt.disabled === true) ? false : true;
    txt.style.border = (txt.disabled === true) ? "none" : "1px dotted grey";
    prio.disabled = (prio.disabled === true) ? false : true;
    prio.style.border = (prio.disabled === true) ? "none" : "1px dotted grey";

}


//Helper-Funktion Modanlegen-------------------------------------------- 
function submitMod() {
    //makiere alle Dozenten im Dropdown
    checkAuswahl();
    document.forms[0].submit();
}


//Best�tigung f�r Modul l�schen-------------------------------------------- 
function checkForDelete(opt) {

    var opt = opt || 'no';
    if (opt === 'delMod')
    {
        return confirm(unescape('Dieses Modul und alle dazugeh%F6rigen Teilmodule l%F6schen?'));
    }
    else
    {
        return confirm(unescape('Dieses Eintrag wirklich l%F6schen?'));
    }
}


//Generiere Remote Popup f�r Dozenten Verwaltung-------------------------------------------- 
function makeRemote() {

    //neues Array f�r gew�hlte dozenten in mod_anlegen  
    doz = new Array();
    doz[0] = 0;

    //Quelle multiple Dropdown    
    var source = document.getElementById('lecturer');

    //Wenn Feld nicht richtig �bergeben
    if (!source)
        return alert(from + ' existiert nicht im Formular!');

    //Solange wie Eintr�ge im Dropdown
    for (var i = 0; i < source.length; i++)
    {
        var s = source.options[i];
        doz[i] = s.value;

    }
    //l�schen der werte im dropdown
    while (i--)
        source.options[i] = null;

    //erstelle popup f�r Dozentenauswahl
    remote = window.open("", "remotewin", "width=500,height=350");

    if (remote.opener == null)
        remote.opener = window;
    remote.opener.name = "opener";
    remote.location.href = "/administration/module/module_lecturer_applet.php?doz=" + doz;
}

//Optimieren f�r einzelnes Element
//-----Alle Listenelemente selektieren--------------------------------------------------------------- 
function checkAuswahl() {
    var elm = document.getElementById('lecturer');
    if (elm.options) {
        var o = null;
        for (var k = 0; o = elm.options[k++]; )
            o.selected = true;
    }
    return false;
}

//--Speichere Dozenten Auswahl-----------------------------------------------------------------------

function saveDozent() {

    checkAuswahl();
    moveTo(document.getElementById('lecturer'), opener.document.getElementById('lecturer'), true);
    self.close();

}


//---Kopiere Elemente zwischen zwei Listen-----------------------------------------------------------

function moveTo(from, to, del) {

    var source = from;
    var target = to;

    if (!source)
        return alert(from + ' existiert nicht im Formular!');
    var idx = source.selectedIndex;

    if (target) {
        for (var i = 0; i < source.length; i++)
        {
            var s = source.options[i];
            if (s.selected)
                target[target.length] = new Option(s.text, s.value, s.selected);
        }
    }
    // Eintr�ge l�schen?
    if (!target || del)
    {
        var i = source.length
        while (i--)
            if (source.options[i].selected)
                source.options[i] = null;
    }
    source.selectedIndex = idx;
}


//---------------------------------------------------------------------------------------------

function checkRegistForm() {

//Pr�fung auf vollst�ndige und korrekte Eingabe

    var strFehler = '';

    if ((document.getElementById('matrikelnr').value == "") || (validText(document.getElementById('matrikelnr').value)))
    {
        strFehler += "Matrikel NR. ung%FCltig\n";
        document.getElementById('matrikelnr').style.background = "#FFFFCC";
    }

    if ((document.getElementById('vname').value == "") || (!validText(document.getElementById('vname').value)))
    {
        strFehler += "Feld Vorname ist nicht korrekt ausgef%FCllt!\n";
        document.getElementById('vname').style.background = "#FFFFCC";
    }

    if ((document.getElementById('nname').value == "") || (!validText(document.getElementById('nname').value)))
    {
        strFehler += "Feld Nachname ist nicht korrekt ausgef%FCllt!\n";
        document.getElementById('nname').style.background = "#FFFFCC";
    }

    if (!validEmail(document.getElementById('email').value))
    {
        strFehler += "E-Mail-Adresse muss auf fh-erfurt.de enden\n";
        document.getElementById('email').style.background = "#FFFFCC";
    }
    if (strFehler.length > 0) {

        alert(unescape("Es wurden folgende Probleme festgestellt: \n\n" + strFehler + "\n\nSonderzeichen sind nicht erlaubt!"));

        return(false);

    }

    return (true);
}

//---------------------------------------------------------------------------------------------------------------------------------------------------

function checkModForm() {
//Pr�fung auf vollst�ndige und korrekte Eingabe
    var strFehler = '';
    var pflichtfelder = false;
    readingLoclab = false;
    readingTypelab = false;
    languagelab = false;
    regularitylab = false;



    var form = document.forms[0]
    for (i = 0; i < form.elements.length - 2; i++) {
        form.elements[i].style.background = "#FFFFFF";
        document.getElementById('reglab').style.color = "black";
    }



    if ((document.getElementById('modNr').value == "") || (!validModnr(document.getElementById('modNr').value)))
    {
        strFehler += "Modul Nr. enth%E4lt Sonder- oder Leerzeichen\n";
        document.getElementById('modNr').style.background = "#FFFFCC";
    }

    if (document.getElementById('modType').value == 'tmod')
    {
        if ((document.getElementById('tmodNr').value == "") || (!validModnr(document.getElementById('tmodNr').value)))
        {
            strFehler += "Teilodul Nr. enth%E4lt Sonder- oder Leerzeichen\n";
            document.getElementById('tmodNr').style.background = "#FFFFCC";
        }
    }

    if (document.getElementById('modDescrb').value == "")
    {
        pflichtfelder = true;
        document.getElementById('modDescrb').style.background = "#FFFFCC";
    }

    if (document.getElementById('status').value == 0)
    {
        pflichtfelder = true;
        document.getElementById('status').style.background = "#FFFFCC";
    }
    if (document.getElementById('studyPath').value == 0)
    {
        pflichtfelder = true;
        document.getElementById('studyPath').style.background = "#FFFFCC";
    }

    if (document.getElementById('modType').value == "mod" || document.getElementById('modType').value == "modt")
    {

        if (document.getElementById('responsible').value == 0)
        {
            pflichtfelder = true;
            document.getElementById('responsible').style.background = "#FFFFCC";
        }
    }

    //Wenn Modulart Teilmodul oder Hauptmodul 
    if (document.getElementById('modType').value == "tmod" || document.getElementById('modType').value == "mod")
    {
        if (document.getElementById('lecturer').value == "")
        {
            pflichtfelder = true;
            document.getElementById('lecturer').style.background = "#FFFFCC";
        }

        //Pruefe Anmeldung     
        if ((document.getElementById('reg0').checked == false) && (document.getElementById('reg1').checked == false))
        {
            document.getElementById('reglab').style.color = "red";
            pflichtfelder = true;
        }

        if (document.getElementById('regularSem').value == "")
        {
            pflichtfelder = true;
            document.getElementById('regularSem').style.background = "#FFFFCC";
        }

        //Workload Studienzeit       
        if (document.getElementById('pz').value == "")
        {
            pflichtfelder = true;
            document.getElementById('pz').style.background = "#FFFFCC";
        }

        if (document.getElementById('vz').value == "")
        {
            pflichtfelder = true;
            document.getElementById('vz').style.background = "#FFFFCC";
        }

        if (document.getElementById('sz').value == "")
        {
            pflichtfelder = true;
            document.getElementById('sz').style.background = "#FFFFCC";
        }

        //SWS und ECTS       
        if (document.getElementById('cp').value == "")
        {
            pflichtfelder = true;
            document.getElementById('cp').style.background = "#FFFFCC";
        }

        if (document.getElementById('sws').value == "")
        {
            pflichtfelder = true;
            document.getElementById('sws').style.background = "#FFFFCC";
        }

        //Inhalte Ziele Methoden      
        if (document.getElementById('contents').value == "")
        {
            pflichtfelder = true;
            document.getElementById('contents').style.background = "#FFFFCC";
        }

        if (document.getElementById('targets').value == "")
        {
            pflichtfelder = true;
            document.getElementById('targets').style.background = "#FFFFCC";
        }


        if (document.getElementById('methodes').value == "")
        {
            pflichtfelder = true;
            document.getElementById('methodes').style.background = "#FFFFCC";
        }

        //Pruefungsart, prfVoraussetzung, Klausur Wdh

        if (document.getElementById('examType').value == "")
        {
            pflichtfelder = true;
            document.getElementById('examType').style.background = "#FFFFCC";
        }

        if (document.getElementById('requiredForExam').value == "")
        {
            pflichtfelder = true;
            document.getElementById('requiredForExam').style.background = "#FFFFCC";
        }


        if (document.getElementById('wdhexamType').value == "")
        {
            pflichtfelder = true;
            document.getElementById('wdhexamType').style.background = "#FFFFCC";
        }


        //Pruefe Checkboxen Veranstaltungsort
        for (i = 0; i < document.getElementsByName('readingLoc[]').length; i++)
        {
            if (document.getElementsByName('readingLoc[]')[i].checked == true)
            {
                document.getElementById('readingLoclab').style.color = "#000";
                readingLoclab = true;
            }

        }

        //Pruefe Checkboxen VeranstaltungsTyp
        for (i = 0; i < document.getElementsByName('readingType[]').length; i++)
        {
            if (document.getElementsByName('readingType[]')[i].checked == true)
            {
                document.getElementById('readingTypelab').style.color = "#000";
                readingTypelab = true;
            }
        }

        //Pruefe Checkboxen language
        for (i = 0; i < document.getElementsByName('language[]').length; i++)
        {
            if (document.getElementsByName('language[]')[i].checked == true)
            {
                document.getElementById('languagelab').style.color = "#000";
                languagelab = true;
            }
        }

        //Pruefe Checkboxen regularityaessigkeit
        for (i = 0; i < document.getElementsByName('regularity[]').length; i++)
        {
            if (document.getElementsByName('regularity[]')[i].checked == true)
            {
                document.getElementById('regularitylab').style.color = "#000";
                regularitylab = true;
            }
        }
    }
    //Wichtung der Note, Leistungsnachweis
    if (document.getElementById('weightMark').value == "")
    {
        pflichtfelder = true;
        document.getElementById('weightMark').style.background = "#FFFFCC";
    }


    if (document.getElementById('performance').value == 0)
    {
        pflichtfelder = true;
        document.getElementById('performance').style.background = "#FFFFCC";
    }



    if (!languagelab) {
        document.getElementById('languagelab').style.color = "red";
        pflichtfelder = true;
    }
    if (!readingLoclab) {
        document.getElementById('readingLoclab').style.color = "red";
        pflichtfelder = true;
    }
    if (!readingTypelab) {
        document.getElementById('readingTypelab').style.color = "red";
        pflichtfelder = true;
    }
    if (!regularitylab) {
        document.getElementById('regularitylab').style.color = "red";
        pflichtfelder = true;
    }



    if (pflichtfelder == true)
    {

        var strPflicht = "Nicht alle Pflichtfelder ausgef%FCllt!\n";
        alert(unescape("Es wurden folgende Probleme festgestellt: \n\n" + strFehler + strPflicht));
        return(false);
    }

    return (true);
}

//---------------------------------------------------------------------------------------------

function validText(text)
{

    var strReg = "^([a-zA-Z]+$)";
    var regex = new RegExp(strReg);

    return(regex.test(text));

}

//---------------------------------------------------------------------------------------------

function validModnr(text)
{

    var strReg = "^([-,-1,0-9a-zA-Z]+$)";
    var regex = new RegExp(strReg);

    return(regex.test(text));

}

//---------------------------------------------------------------------------------------------


function validEmail(email)
{

    var strReg = "^([a-zA-Z0-9_\.\-])+\@(fh-erfurt.de)+$";
    var regex = new RegExp(strReg);

    return(regex.test(email));

}
//---------------------------------------------------------------------------------------------

function auswahlModart()
{

    document.getElementById('tmodNr').style.display = (document.getElementById('tmodNr').style.display == "none") ? "" : "none";
}

//--------------------------------------------------------------------------------------------------------

function toggle(name)
{
    if (name == 'moddetails')
    {

        tr = document.getElementsByClassName('tmoddetails');
        for (var i = 0; i < tr.length; i++)
        {
            tr[i].style.display = "";
        }
        document.getElementById('responsible').disabled = false;
        document.getElementById('tmodNr').disabled = true;
        document.getElementById('lecturer').disabled = false;
        document.getElementById('chooseLecturer').disabled = false;


    }
    else if (name == 'nomoddetails')
    {

        tr = document.getElementsByClassName('tmoddetails');
        for (var i = 0; i < tr.length; i++)
        {
            tr[i].style.display = "none";
        }
        document.getElementById('responsible').disabled = false;
        document.getElementById('tmodNr').disabled = true;
        document.getElementById('chooseLecturer').disabled = true;
        document.getElementById('performance').disabled = true;


    }
    else if (name == 'tmoddetails')
    {

        tr = document.getElementsByClassName('tmoddetails');
        for (var i = 0; i < tr.length; i++)
        {
            tr[i].style.display = "";
        }
        document.getElementById('responsible').disabled = true;
        document.getElementById('chooseLecturer').disabled = false;

        document.getElementById('studyPath').deaktivate = true;
        document.getElementById('tmodNr').disabled = false;
        document.getElementById('lecturer').disabled = false;
    }

    else
    {
        tr = document.getElementsByClassName(name);
        for (var i = 0; i < tr.length; i++)
        {
            tr[i].style.display = (tr[i].style.display == "none") ? "" : "none";
        }

    }
}

//---------------------------------------------------------------------------------------------------------------------
function in_array(arr, val) {
   for(var i = 0; i < arr.length; i++)
      if(arr[i] === val)
          return true;
   return false;
}

