function validate(){
    var username = document.forms["registration-from"]["txt-username"].value;
    var email = document.forms["registration-from"]["txt-email"].value;
    var password = document.forms["registration-from"]["txt-password"].value;
    var name = document.forms["registration-from"]["txt-name"].value;

    
    if(username==="" || email==="" || password==="" || name===""){
       document.getElementById("signin-error").textContent="Please fill all the fields";
        return false;
    }

    if(!isNaN(name)){
        document.getElementById("signin-error").textContent="Please enter the correct name";
        return false;
    }

    if (!email.includes("@") || !email.includes(".")) {
        document.getElementById("signin-error").textContent="Please enter a valid email";
        return false;
    }

    return true;
    
}