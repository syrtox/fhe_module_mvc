<div class="postmetadataheader">
    <h2 class="postheader">
        <span class="postheadericon">Moduldetails</span>
    </h2>
</div>
<div class="postcontent">
    <div style="float: left;">
        <?php echo $this->input['modBreadcrumb']; ?>  
    </div> 
    <div style="float: right;">
                    <form method="post" action="module/module2pdf.php" style="float: right; text-align: center;">
                        <input type="hidden" name="moduleID" value="<?php echo $modulNr; ?>"/>
                        <input id="pdf_download" style="vertical-align: top; height:26px;" type="image" 
                               src="../images/pdf_icon.png" alt="Modul&uuml;bersicht als PDF downloaden" />
                        <p style="font-size: 10px;">Download</p>
                    </form>
                    <form  method="post" action="index.php?sid=20&action=editModule&id=1011" style="float: right; text-align: center; margin-right: 10px;">
                        
                        <input id="pdf_download" style="vertical-align: top; height:26px;" type="image" 
                               src="../images/modedit_icon.png" alt="Modul&uuml;bersicht als PDF downloaden" />
                        <p style="font-size: 10px;">Bearbeiten</p>
                    </form>
    </div>
    <div id="show_elements">
        <div id ="modDetailsHeader" class="ajaxload" style="float: left;">
            <?php echo $this->module_details("getModDetailsHeader", $_GET['module']); ?>
        </div>
        <div style="float: left;">
            <?php echo $this->input['details'] ?>  
        </div>
    </div>
</div>
<div class="cleared"></div>
<br />