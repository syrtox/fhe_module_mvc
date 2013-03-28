<div class="postmetadataheader">
<h2 class="postheader"><span class="postheadericon">Login
</span></h2>
</div>
<div class="postcontent">
    <p style="width: 500px; padding-left:40px;"><h2>Herzlich Willkommen!</h2><br>
	<p style="width: 680px; padding-left:40px;">
	Mit Unserem System IMOLE haben
	Sie die Möglichkeit, sich über einzelne Module zu informieren und Lehrende 
	zu diesen abzufragen. Um an den verschiedenen Modulen teilnehmen zu können, müssen Sie 
	sich einen Nutzerzugang für diese Website anlegen. Dazu klicken Sie 
	bitte auf <a href="index.php?sid=8">Registrierung</a>
	<p style="width: 500px; padding-left:40px;"><br>
    
    <div style="width:300px; height:200px; margin:40px auto;">
        <p style="color:red;"><?php echo $this->input['alert']; ?></p>
        <form method="post" action="index.php?sid=5&action=doLogin">
            <fieldset name="Login">
                <legend><span lang="de">Anmeldung</span></legend>

                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width: 100px">Benutzername:</td>

                            <td>
                                <input value="" name="username" type="text" style="width: 150px"></td>
                        </tr>
                        <tr>
                            <td style="width: 100px">Passwort:</td>

                            <td>
                                <input value="" name="password" type="password" style="width: 150px"></td>
                        </tr>

                        <tr>
                            <td style="height: 40px; text-align: right;" colspan="2">
                                <input name="login" type="submit" value="Anmelden"><input name="reset" type="reset" value="Zur&uuml;cksetzen"></td>

                        </tr>

                        <tr>
                            <td style="width: 100px"> <br></td>
                            <td style="text-align:right;"><a href="index.php?sid=25&getpwd">Passwort 
							vergessen?</a> </td>
                        </tr>

                        <tr>
                            <td colspan="2" style="height: 21px">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
        </form>
    </div>
    </div>
