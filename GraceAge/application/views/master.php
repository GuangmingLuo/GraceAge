<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">        
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" href="../../assets/font-awesome-4.7.0/css/font-awesome.css"type="text/css"/>
        <link rel="stylesheet" href="../../assets/font-awesome-4.7.0/css/font-awesome.min.css">
        <link href="../../assets/css/w3.css" rel="stylesheet" type="text/css"/>
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
        <script src="../../assets/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
        <script src="../../assets/js/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
        <script src="../../assets/js/questions.js" type="text/javascript"></script>
        <script src="../../assets/js/carefiver-personal-notes.js" type="text/javascript"></script>
        <script src="../../assets/js/tips.js" type="text/javascript"></script>
        <script src="../../assets/js/caregiver_tips.js" type="text/javascript"></script>
        <script src="../../assets/js/login.js" type="text/javascript"></script>
        <script src="../../assets/js/personal_datatable.js" type="text/javascript"></script>
        <script src="../../assets/js/profilescript.js" type="text/javascript"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.12/datatables.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
        <script src="../../assets/js/carefiver-personal-notes.js" type="text/javascript"></script>
        <script src="../../assets/js/caregiver_messages.js" type="text/javascript"></script>
    </body>
</html>