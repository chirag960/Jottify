function loadDoc(method,url,requestBody,callFunction) {
    method = method.toUpperCase();
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && (this.status == 200 || this.status == 201)) {
            callFunction(this);
        }
    };
    if(method=="GETPDF"){
        xhttp.open("GET", url, true);
    }
    else if(method=="POST2"){
         xhttp.open("POST", url, true);
    }
    else{
    xhttp.open(method, url, true);
    }
    if(method == "GET"){
    xhttp.send();
    }
    else if(method == "GETPDF"){
        console.log("This is sent");   
        xhttp.responseType="blob";
        xhttp.send();
    }
     else if(method == "POST"){
         console.log("This is sent");   
         var t = document.getElementsByTagName("META")[2].content;
         var params = requestBody;
         xhttp.setRequestHeader("X-CSRF-TOKEN",t);
         xhttp.setRequestHeader("Content-type", "application/json");
         xhttp.send(params);
    }
    else if(method=="POST2"){
         console.log("Post2 sent");
         var t = document.getElementsByTagName("META")[2].content;
         var params = requestBody;
         xhttp.setRequestHeader("X-CSRF-TOKEN",t);
         xhttp.send(params);
    }
    else if(method=="PATCH"){
         console.log("This is sent");
         var t = document.getElementsByTagName("META")[2].content;
         var params = requestBody;
         xhttp.setRequestHeader("X-CSRF-TOKEN",t);
         xhttp.setRequestHeader("Content-type", "application/json");
         xhttp.send(params);
    }
}