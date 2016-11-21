/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$('.answer_button').click(function () {
    $('.answer_button').css('border-color', "#CDDC39");
    $(this).css('border-color', "#009688");
    var value = $(this).attr('title');
    $.post('answer_clicked', {clicked: value}, function(){
    });

});

function previous() {
    $.getJSON("previous", function (data) {
        $('#question_placeholder').text(data[0].Question);
        $('#topic_placeholder').text(data[0].Topic);
    });
}

function next() {
    $.getJSON("next", function (data) {
        $('#question_placeholder').text(data[0].Question);
        $('#topic_placeholder').text(data[0].Topic);
    });
}