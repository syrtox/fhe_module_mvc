<script type="text/javascript">
    $(document).ready(function() {
        $("#majors").attr("disabled", "disabled");
        $("#areaOfSpec").attr("disabled", "disabled");
    });

</script>
<div class="postmetadataheader">
    <h2 class="postheader"><span class="postheadericon">
            Modul&uuml;bersicht
        </span></h2>
</div>
<div class="postcontent">
    <form id="modulfilter" class="modulfilter" onsubmit='return false;'>
        <input type="hidden" name="sid" value="<?php echo $_GET['sid']; ?>" />
        <fieldset>
            <legend>Modulfilter</legend>
            <table>
                <tr>
                    <th><label for="subjects">Fachrichtung&nbsp;</label></th>
                    <td style="width: 320px" colspan="2">
                        <select id="subjects" name="subjects" onchange="callAjaxRouter(this.id, 'getMajorsAsDrop');" style="width: 250px;">
                            <?php echo $this->university('getSubjectsAsDrop'); ?>
                        </select>
                    </td>
                    <th style="width: 4px;"><label for="view">Ansicht&nbsp;</label></th>
                    <td colspan="2" style="text-align:left">
                        <input name="view" type="radio" id="table" value="1" checked="checked" disabled>
                        <label for="table">Tabelle</label>
                        <input name="view" type="radio" id="timeline" value="2" disabled>
                        <label for="timeline">Timeline</label></td>
                </tr>
                <tr>
                    <th><label for="majors">Studiengang&nbsp;</label></th>
                    <td style="width: 320px" colspan="2">
                        <select id="majors" name="majors" onchange="callAjaxRouter(this.id, 'getAreaOfSpecAsDrop');" style="width: 250px;">
                            <option value="0">---alle anzeigen---</option>
                        </select>
                    </td>
                    <th style="width: 4px;"><label for="status">Status&nbsp;</label></th>
                    <td colspan="2">
                        <input name="status[]" type="checkbox" value="1" checked="checked">
                        <label for="requiredSubj">Pflicht </label>
                        <input name="status[]" type="checkbox" value="2" checked="checked">
                        <label for="electiveSubj">Wahlpflicht </label>
                        <input name="status[]" type="checkbox" value="3"  checked="checked">
                        <label for="optionalSubj">Wahl </label>
                    </td>
                </tr>
                <tr>
                    <th><label for="areaOfSpec">Vertiefung&nbsp;</label></th>
                    <td style="width: 320px" colspan="2">
                        <select id="areaOfSpec" name="areaOfSpec" style="width: 250px;">
                            <option value="0">---alle anzeigen---</option>
                        </select></td>
                    <th style="width: 4px;">Suchen&nbsp;</th>
                    <td colspan="2">
                        <input name="search" id="search_mod" style="width: 200px; text-align: left;" 
                               type=search results=5 autosave=some_unique_value></td>
                </tr>
                <tr>
                    <th style="text-align: right;">&nbsp;</th>
                    <td style="text-align: left;">
                        &nbsp;</td>
                    <td colspan="3" style="text-align: right;">&nbsp;</td>
                    <td style="text-align: right;">
                        <input id="submit" onclick="submitModFilter();" type="button" value="anzeigen"><input name="Reset1" type="reset" value="reset"></td>
                </tr>
            </table>
        </fieldset>
    </form>
    <div id="show_elements" class="ajaxload" style="margin-top:50px">
        <?php echo $this->module_list('getModuleOverview'); ?>
    </div>
</div>         
