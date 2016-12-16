function update_note(id) {
    var new_note = document.getElementById(id + "s").value;
    $.post("update_note", {new_note: new_note, id: id});
    document.getElementById(id + "sss").innerHTML = "Updated!";
    $('#'+id+'sss').show();
    setTimeout(function () {
        $('#'+id+'sss').fadeOut();
    }, 1000); // <-- time in milliseconds
}
;
