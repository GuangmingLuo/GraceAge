function mark_answer(id){
    $('.answer_button').css('box-shadow', "1px 1px 1px #666666");     //reset borders of all answerbuttons
    $('.answer_button').css('color', "#405250");
    $('.answer_button').css('background-color', "#CDDC39");
    $("#"+id).css('box-shadow', "none");                 //set border of selected answer to highlight color
    $("#"+id).css('color', "white");
    $("#"+id).css('background-color', "#009688");                      
    $.post('answer_clicked', {clicked: id}, function(){
    });
}

function Questionnaire_help(){
    var helpText = document.getElementById("helpText");
    // Toggle 
    helpText.style.display == "block" ? helpText.style.display = "none" : 
    helpText.style.display = "block"; 
}

function updateProgressbar(questionID) {
    var targetValue = (questionID/52)*100;              //convert questionNumber to percentage
      $("#progressbar")
      .css("width", targetValue + "%")                  //set new length of progress on progressbar
      .attr("aria-valuenow", targetValue);              //set controlValue of progressbar
      $("#pbQuestionCount").text(questionID + "/52");   //update text on bar
      
}

function previous() {
    $('.answer_button').css('box-shadow', "1px 1px 1px #666666");         //reset border of answerbutton
    $('.answer_button').css('color', "#405250");
    $('.answer_button').css('background-color', "#CDDC39");
    $.getJSON("previous", function (data) {
        $('#question_placeholder').text(data[0].Question);      //set question
        $('#topic_placeholder').text(data[0].Topic);            //set topic
        getscore();                                             // update the score
        updateProgressbar(data[0].QuestionNumber.valueOf());    //call function to update progressbar
    });
}

function next() {
    $('.answer_button').css('box-shadow', "1px 1px 1px #666666");         //reset border of answerbutton
    $('.answer_button').css('color', "#405250");
    $('.answer_button').css('background-color', "#CDDC39");
    $.getJSON("next", function (data) {
        $('#question_placeholder').text(data[0].Question);      //set question
        $('#topic_placeholder').text(data[0].Topic);            //set topic
        updateProgressbar(data[0].QuestionNumber.valueOf());    //call function to update progressbar
        getscore();                                             //update the score
         if(data[0].QuestionNumber.valueOf()== 1){ // show congratualtions at and of questionnaire -> when 52nd question is answered questionumber equals 1
        window.location.href = "congratulations";
    }
    });    
   
}

function getscore(){
    $.get("getJsonScore", function(data){
        $("#score").html(data.toString() +"<i id='starIcon' class='fa fa-star fa-1x'></i>"); // update de nieuwe score
    });
}