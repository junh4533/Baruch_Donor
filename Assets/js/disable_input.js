
// function disable_pc(a) {
//     var a = document.getElementById("prefix");
//     var b = document.getElementById("first_name");
//     var c = document.getElementById("last_name");
//     var d = document.getElementById("suffix");
//     var e = document.getElementById("pc_id");
    
//     if(a != "" || b != "" || c != "" || d != ""){
//         e.disabled = true;
//     }
//     else{
//         e.disabled = false;
//     }

//     if(e != ""){
//         a.disabled = true;
//         b.disabled = true;
//         c.disabled = true;
//         d.disabled = true;
//     }
//     else{
//         a.disabled = false;
//         b.disabled = false;
//         c.disabled = false;
//         d.disabled = false;
//     }

//     var x = document.getElementById(a).value;
//     if(document.getElementById(a) != ""){
//         document.getElementById(a).disabled = true;
//         console.log(x);
//     }else{
//         document.getElementById(a).disabled = false;
//     }
    
// }

function disable_pc() {
    var a = document.getElementById("prefix").value;
    var b = document.getElementById("first_name").value;
    var c = document.getElementById("last_name").value;
    var d = document.getElementById("suffix").value;
    if(a != "" || b != "" || c != "" || d != ""){
        document.getElementById("pc_id").disabled = true;
    }
    else{
        document.getElementById("pc_id").disabled = false;
    }
}

