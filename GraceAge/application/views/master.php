<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">        
        <link href="../../assets/css/login.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        <title>{page_title}</title>
    </head>
    <body>

        <?php
        if (!$show_navbar == false) {
            include($navbar_content);
        }
        ?>

        <?php include($page_content); ?>

        <script src="../../assets/js/jquery.min.js" type="text/javascript"></script>
        <script src="../../assets/js/bootstrap.min.js"></script>
        <script src="../../assets/js/jsqrcode-combined.min.js" type="text/javascript"></script>
        <script src="../../assets/js/html5-qrcode.min.js" type="text/javascript"></script>
        <script src="../../assets/js/getqrdata.js" type="text/javascript"></script>
        <script src="../../assets/js/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
        <script src="../../assets/js/questions.js" type="text/javascript"></script>
    </body>
</html>