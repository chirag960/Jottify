
function validateEmail(){
    var email = document.getElementById("email").value;
    var ele = document.getElementById("invalidEmail");

    var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
    var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
    
    if(email.length == 0){
        ele.innerHTML = "<strong>Please enter a email id</strong>";
        ele.style.display = "block";
        return false;
    }
    else if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))){
        ele.innerHTML = "<strong>The email id is not valid</strong>";
        ele.style.display = "block";
        return false;
    }
    else {
        ele.style.display = "none";
        return true;
    }
}


function validatePassword(){
    var pwd = document.getElementById("password").value;
    var ele = document.getElementById("invalidPwd");
    if(pwd.length == 0){
        ele.innerHTML = "<strong>Please enter a password</strong>";
        ele.style.display = "block";
        return false;
    }
    else if(pwd.length < 8 || pwd.length > 20){
        ele.innerHTML = "<strong>Length should not be less than 8 or greater than 20</strong>";
        ele.style.display = "block";
        return false;
    }
    else {
        ele.style.display = "none";
        return true;
    }
}

function validateForm(){
    var email = validateEmail();
    var pwd = validatePassword();
    if(email && pwd) 
    {
        console.log("all valid");
        return true;
    }
    else return false;
}