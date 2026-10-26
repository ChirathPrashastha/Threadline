function validate(){
    var title = document.forms["form-thread"]["txtTitle"].value;
    var content = document.forms["form-thread"]["txtContent"].value;

    if(title==="" || content ===""){
        document.getElementById("error-td").textContent="Please fill all the fields for create a thread";
        return false;
    }

    return true;
}