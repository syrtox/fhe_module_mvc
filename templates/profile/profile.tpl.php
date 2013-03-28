
<div class="postmetadataheader">
    <h2 class="postheader"><span class="postheadericon">
            Profil
        </span></h2>
</div>
<div class="postcontent">
    <h2 class="postheader">Willkommen, <?php echo $this->input['name']; ?>
    </h2>
    <div id="profil-content">
        <div class="profil-box rounded">
            <div class="titlebar">   
                <h2>Einstellungen</h2>
                <img src="../images/1360864934_042.png" alt="einstellungen">
            </div>
            <div class="listbar">
                <div class="head">
                    <span>Profil ansehen, &auml;ndern und Einstellungen vornehmen</span>
                </div>
                <div class="listbox">
                    <h4>&Uuml;bersicht aller Einstellungen</h4>
                    <ul>
                        <li><a href="index.php?sid=">
                                Profil ansehen</a></li>
                        <li><a href="index.php?sid=">
                                Profil &auml;ndern</a></li>
                        <li><a href="index.php?sid=">
                                Passwort &auml;ndern</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="profil-box rounded">
            <div class="titlebar">   
                <h2>Module</h2>
                <img src="../images/1360864995_062.png" alt="module" height="20px">
            </div>
            <div class="listbar">
                <div class="head">
                    <span>Module ansehen, Einschreibungen &auml;ndern, zu Pr&uuml;fungen anmelden</span>
                </div>
                <div class="listbox">
                    <h4>&Uuml;bersicht eigene Module</h4>
                    <ul>
                        <li><a href="index.php?sid=">
                                Meine Module ansehen</a> </li>
                        <?php if (Session::get('isStudent')) ?>
                                <li><a href="index.php?sid=">
                                        Meine Pr&uuml;fungen ansehen</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="profil-box rounded">
            <div class="titlebar">   
                <h2>News &amp; Infos</h2>
                <img src="../images/1360865023_072.png" alt="neuigkeiten">
            </div>
            <div class="listbar">
                <div class="head">
                    <span>Neuigkeiten und Nachrichten rund um Ihren Studiengang</span></div>
                <div class="listbox">
                    <h4>&Uuml;bersicht aller Neuigkeiten</h4>
                    <ul>
                        <li><a href="#">
                                Meine Nachrichten</a></li>
                        <li><a href="#">
                                Neues im Studiengang</a></li>
                        <li><a href="#">
                                Neue Module</a></li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div id="profil-sidebar">
        <div class="profil-box rounded">
            <!--<div class="titlebar">   
              <h2>Sidebar</h2>
            </div>-->
            <div style="padding: 5px;">
                <h4 style="font-size: 12px;">Sie sind eingeloggt in der Rolle: </h4><?php echo $this->input['userGroup']; ?>
                <h4 style="font-size: 12px;">Ihr Benutzername: </h4><?php echo $this->input['username']; ?>
                <h4 style="font-size: 12px;"><a href="index.php?sid=5&action=doLogout">TEST LOGOUT</a></h4>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
