/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('.answer_button').click(function () {
    $('.answer_button').css('border-color', "#CDDC39");     //reset borders of all answerbuttons
    $(this).css('border-color', "#009688");                 //set border of selected answer to highlight color
    var title = $(this).attr('title');                      
    $.post('answer_clicked', {clicked: title}, function(){
    });

});

function updateProgressbar(questionID) {
    var targetValue = (questionID/52)*100;              //convert questionNumber to percentage
      $("#progressbar")
      .css("width", targetValue + "%")                  //set new length of progress on progressbar
      .attr("aria-valuenow", targetValue);              //set controlValue of progressbar
      $("#pbQuestionCount").text(questionID + "/52");   //update text on bar
      
}

function previous() {
    $('.answer_button').css('border-color', "#CDDC39");         //reset border of answerbutton
    $.getJSON("previous", function (data) {
        $('#question_placeholder').text(data[0].Question);      //set question
        $('#topic_placeholder').text(data[0].Topic);            //set topic
        updateProgressbar(data[0].QuestionNumber.valueOf());    //call function to update progressbar
    });
}

function next() {
    $('.answer_button').css('border-color', "#CDDC39");         //reset border of answerbutton
    $.getJSON("next", function (data) {
        $('#question_placeholder').text(data[0].Question);      //set question
        $('#topic_placeholder').text(data[0].Topic);            //set topic
        updateProgressbar(data[0].QuestionNumber.valueOf());    //call function to update progressbar
        
         if(data[0].QuestionNumber.valueOf()== 1){ // show congratualtions at and of questionnaire -> when 52nd question is answered questionumber equals 1
        window.location.href = "congratulations";
    }
    });
    
   
}