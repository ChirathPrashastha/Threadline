function validate(){
    var username = document.forms["index-form"]["txt-username"].value;
    var password = document.forms["index-form"]["txt-password"].value;

    if(username==="" || password===""){
        document.getElementById("error-index").textContent="Please fill all the fields";
        return false;
    }

    return true;
}