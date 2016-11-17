/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('.answer_button').click(function () {
    //alert("Dit gebeurt wel...");
    $('.answer_button').css('background-color', "Lime");
    $(this).css('background-color', "White");
    var title = $(this).attr('title');
    $.post('answer_clicked', {clicked: title}, function(){
        alert("success");
    });

});

function previous() {
    $.getJSON("previous", function (data) {
        $('.answer_button').css('background-color', "Lime");
        $('#question_placeholder').text(data[0].Question);
        $('#topic_placeholder').text(data[0].Topic);
        //alert('something came in');
    });
}

function next() {
    alert('hmmm');
    $.getJSON("next", function (data) {
        $('.answer_button').css('background-color', "Lime");
        $('#question_placeholder').text(data[0].Question);
        $('#topic_placeholder').text(data[0].Topic);
        //alert('something came in');
    });
}