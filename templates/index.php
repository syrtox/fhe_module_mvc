<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>IMOLE - Fachhochschule Erfurt</title>

        <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="./public/css/style.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="./public/js/jquery-ui/jquery-ui.css" type="text/css"/>
        <script type="text/javascript" src="./public/js/functions.js"></script>  
        <script type="text/javascript" src="./public/js/jquery-1.8.3.js"></script>
        <script type="text/javascript" src="./public/js/jquery-bramus-progressbar.js"></script>
        <script type="text/javascript" src="./public/js/jquery-ui/jquery-ui.js"></script>
        <script type="text/javascript" src="./public/js/tablesorter/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="./public/js/jRating.jquery.min.js"></script>
        <script type="text/javascript">
         $(document).ready(function() {
                $("#myTable").tablesorter({sortList: [[0, 0]], widgets: ['zebra']});
                $("#myTable").tablesorterPager({container: $("#seiten")});
                
                 $(this).ajaxStart(function()
                 {
                   $(".ajaxload").append("<div id='overlay'><img src='./public/images/ajax-loader.gif' /></div>");
                 });
                 
                $(this).ajaxStop(function()
                {
                  $("#overlay").remove();
                });
    
  });
</script>
        </script>
    </head>
    <body>
        <noscript>
        <div class="status_error" style="text-align: center;">
            Um das System im vollen Umfang nutzen zu k&ouml;nnen, ben&ouml;tigen Sie JavaScript! 
            Bitte aktivieren Sie es oder updaten Sie Ihren Browser.
        </div>
        </noscript>
        <div id="main">
            <div class="cleared reset-box"></div>
            <div class="pageheader">
                <div style="float:right; text-align: right; color:red; z-index: 1; margin:5px 15px 0px 0px;">
                    <p>IMOLE MVC-OOP v1.5</p>
                        <?php
                    if (Session::get('groupId') == 5)
                    {
                        //TODO: APC Apache extension
                        $cmemory = number_format((memory_get_peak_usage() / 1024 / 1024), 4, ',', '.');
                        $amemory = number_format((memory_get_usage() / 1024 / 1024), 4, ',', '.');
                        echo 'Request memory usage: ' . $cmemory . ' MB<br>';
                        echo 'Ammount memory usage: ' . $amemory . ' MB';
                    }
                    ?>
                </div>   

            </div>
                <div class="cleared reset-box"></div>
                <div class="box sheet">
                    <div class="box-body sheet-body">
                        <div class="bar nav">
                            <div class="nav-outer">  
                                <ul class="hmenu"> 
                                    <?php echo $this->input['nav']; ?> 
                                </ul>
                            </div>

                        </div>
                        <div class="cleared reset-box"></div>
                        <div class="layout-wrapper">
                            <div class="post-inner">
                                <?php echo Notice::$globalAlert; ?>
                                <?php echo $this->input['content']; ?>                          
                        </div>
                    </div>
                    <div class="cleared"></div>
                    <div class="footer">
                        <div class="footer-body">
                            <a href="#" class="rss-tag-icon" title="RSS"></a>
                            <div class="footer-text">
                                <p>Copyright © 2013. Alle Rechte vorbehalten</p>
                            </div>
                            <div class="cleared"></div>
                        </div>
                    </div>
                    <div class="cleared"></div>
                </div>
            </div>
            <div class="cleared"></div>
            <p class="page-footer">Designed by Steven Gei&szlig;er.</p>
            <div class="cleared"></div>
        </div>
    </body>
</html>
