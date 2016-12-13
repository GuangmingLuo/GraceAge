function update_note(id){
    var new_note = document.getElementById(id+"s").value;
    $.post("update_note", {new_note : new_note, id: id});
};
