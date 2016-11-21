
<head>
    <link href="../../assets/css/Questionnaire.css" rel="stylesheet" type="text/css"/>
</head>
<div id='questionnairy_content' class="container">

    <main id="q_and_a">
        <h1>{header1}</h1>
        <div class="row">
            <div class="col-sm-1">
            </div>
            <div class="col-sm-10">
                <div id="question_panel" class="panel panel-default">
                    <table>
                        <tr>
                            <th id="tableheader_topic">Topic</th>
                            <th id="tableheader_Question">Question</th>
                        </tr>
                        {questions}
                        <tr>
                            <td id="topic_placeholder">{Topic}</td>
                            <td id="question_placeholder">{Question}</td>
                        </tr>
                        {/questions}
                    </table>
                </div>
            </div>
            <div class="col-sm-1">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1">
            </div>

            {answers}
            <div class="col-sm-2">
                <button type="button" title="{title}" class="{className}">{name}</button>
            </div>
            {/answers}
            <div class="col-sm-1">
            </div>
        </div>
    </main>

</div>
<footer>
    <div class="row">
        <div class="col-sm-1">
        </div>
        {navigationbuttons}
        <div class="col-sm-5">
            <button type="button" class ="{class}" onclick="{func}" >{name}</button>
        </div>
        {/navigationbuttons}
        <div class="col-sm-1">
        </div>
    </div>
</footer>
