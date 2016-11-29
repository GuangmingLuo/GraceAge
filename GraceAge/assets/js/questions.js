/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('.answer_button').click(function () {
    $('.answer_button').css('border-color', "#CDDC39");
    $(this).css('border-color', "#009688");
    var title = $(this).attr('title');
    $.post('answer_clicked', {clicked: title}, function(){
    });

});

function move(questionID) {
    //value = $("#progressbar").valueOf();
    
    var targetValue = (questionID/52)*100;
  //var interval = setInterval(function() {
      //value++;
      $("#progressbar")
      .css("width", targetValue + "%")
      .attr("aria-valuenow", targetValue);
      $("#pbQuestionCount").text(questionID + "/52");
      //.text(questionID + "/52");
      //if (value >= targetValue)
          //clearInterval(interval);
  //}, 10);
}

function previous() {
    $('.answer_button').css('border-color', "#CDDC39");
    $.getJSON("previous", function (data) {
        $('#question_placeholder').text(data[0].Question);
        $('#topic_placeholder').text(data[0].Topic);  
        move(data[0].QuestionNumber.valueOf());
    });
}

function next() {
    $('.answer_button').css('border-color', "#CDDC39");
    $.getJSON("next", function (data) {
        $('#question_placeholder').text(data[0].Question);
        $('#topic_placeholder').text(data[0].Topic);
        move(data[0].QuestionNumber.valueOf());
    });
}