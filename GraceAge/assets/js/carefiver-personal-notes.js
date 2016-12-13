function updateTip(id){
    var new_note = document.getElementById('id').value;
    $.post("update_note", {note: new_note, id: id});
};