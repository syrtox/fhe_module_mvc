<script language="JavaScript">
    $(document).ready(function()
    {
        var data = [<?php echo $tags; ?>];
        $("#search_mod").catcomplete({
            delay: 0,
            source: data
        });
        
         $("#majors").attr("disabled", "disabled");
    }
    );

    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _renderMenu: function(ul, items) {
            var that = this,
                    currentCategory = "";
            $.each(items, function(index, item) {
                if (item.category != currentCategory) {
                    ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                    currentCategory = item.category;
                }
                that._renderItemData(ul, item);
            });
        }
    });

</script>

<div class="postmetadataheader">
    <h2 class="postheader"><span class="postheadericon">
            Module verwalten
        </span></h2>
</div>
<div class="postcontent">
    <div class="modulfilter">
        <form action="index.php?sid=28" method="post" onsubmit="return false;">
            <fieldset name="modanzeigen">
                <legend>Module anzeigen</legend>
                <table class="clear">
                    <tr>
                        <th style="width: 90px; text-align: right;">Fachrichtung</th>
                        <td style="width: 280px">				
                            <select id="subjects" name="subject" style="width: 250px" 
                                    onChange="callAjaxRouter(this.id,'getMajorsAsDrop')"> 
                                <?php echo $this->university("getSubjectsAsDrop"); ?>
                            </select></td>
                        <th style="width: 109px; text-align: right;">Verantwortung</th>
                        <td style="width: 250px">
                            <select id="owner" name="owner" style="width: 180px">
                                <?php echo $this->university("getLecturerAsDrop"); ?>
                            </select></td>
                    </tr>
                    <tr>
                        <th style="width: 90px; text-align: right;">Studiengang</th>
                        <td style="width: 280px">
                            <select id="majors" name="major" style="width: 250px">
                                <option value="0" >---alle anzeigen---</option>
                            </select></td>
                        <th style="width: 109px; text-align: right;">Suchen</th>
                        <td style="width: 250px">
                            <input name="search_mod" id="search_mod" style="width: 250px; height: 22px;" type=search 
                                   results=5 autosave=some_unique_value ></td>			
                    </tr>
                    <tr>
                        <th style="width: 90px; text-align: right;">Modulart</th>
                        <td style="width: 280px">
                            <select id="modType" name="modType" style="width: 140px">
                                <option value="0" >---alle anzeigen---</option>
                                <option value="mod" >Hauptmodul</option>
                                <option value="tmod">Teilmodul</option>
                            </select> 
                            <input id="modInactive" name="modinaktiv" type="checkbox" 
                                   value="1" >nur inaktive</td>
                        <td colspan="2" style="text-align: right">
                        <input type="button" value="anzeigen" onclick="submitModAdminFilter()">
                        <input name="Reset1" type="reset" value="reset"></td>
                    </tr>
                </table>
            </fieldset></form>
        <div style="margin:25px 0px 10px 0px">
            <div style="display:block; margin-bottom: 0px;" >
                <?php
                if (isset($_REQUEST['act']))
                {
                    activateMod($_REQUEST['act']);
                }
                if (isset($_REQUEST['deact']))
                {
                    deactivateMod($_REQUEST['deact']);
                }
                if (isset($_REQUEST['del']))
                {
                    deleteMod($_REQUEST['del']);
                }
                ?>
            </div>
            </div>
        </div>
        <div id="show_elements" class="ajaxload">
        <?php
        
        echo $this->module_list("showModuleList");
     
        ?>

    </div>
</div>