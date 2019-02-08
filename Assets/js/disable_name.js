function disable_name() {
    var z = document.getElementById("pc_id").value;
    if (z != "") {
        document.getElementById("prefix").disabled = true;
        document.getElementById("first_name").disabled = true;
        document.getElementById("last_name").disabled = true;
        document.getElementById("suffix").disabled = true;
    }
    else {
        document.getElementById("prefix").disabled = false;
        document.getElementById("first_name").disabled = false;
        document.getElementById("last_name").disabled = false;
        document.getElementById("suffix").disabled = false;
    }
}