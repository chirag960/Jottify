function makeGetRequest(url,callFunction,overlay = true){
    if(overlay == true){
        $("#overlay").show();
    }
    var xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            if(overlay == true){
                $("#overlay").hide();
            }
            callFunction(this);
        }
    };
    xhttp.open("GET",url,true);
    xhttp.send();
}

function makeGetRequestForPDF(url,callFunction){
    $("#overlay").show();
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            $("#overlay").hide();
            callFunction(this);
        }
    };
    xhttp.open("GET", url, true);
    xhttp.responseType="blob";
    xhttp.send();
}

function makePostRequest(url,requestBody,callFunction){
    $("#overlay").show();
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            $("#overlay").hide();
            callFunction(this);
        }
    };
    xhttp.open("POST", url, true);
    var t = document.getElementsByTagName("META")[2].content;
    xhttp.setRequestHeader("X-CSRF-TOKEN",t);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(requestBody);
}

function makePostRequestForImages(url,requestBody,callFunction){
    $("#overlay").show();
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            $("#overlay").hide();
            callFunction(this);
        }
    };
    xhttp.open("POST", url, true);
    var t = document.getElementsByTagName("META")[2].content;
    xhttp.setRequestHeader("X-CSRF-TOKEN",t);
    xhttp.send(requestBody)
}

function makePatchRequest(url,requestBody,callFunction){
    $("#overlay").show();
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && (this.status == 200 || this.status == 201)) {
            $("#overlay").hide();
            callFunction(this);
        }
    };
    xhttp.open("PATCH", url, true);
    var t = document.getElementsByTagName("META")[2].content;
    xhttp.setRequestHeader("X-CSRF-TOKEN",t);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(requestBody);
}

function makeDeleteRequest(url,callFunction){
    console.log("inside ajax");
    $("#overlay").show();
    var xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            $("#overlay").hide();
            callFunction(this);
        }
    };
    xhttp.open("DELETE",url,true);
    var t = document.getElementsByTagName("META")[2].content;
    xhttp.setRequestHeader("X-CSRF-TOKEN",t);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send();
}

// function loadDoc(method,url,requestBody,callFunction) {
//     method = method.toUpperCase();
//     console.log("inside loadDoc");
//     var xhttp;
//     xhttp=new XMLHttpRequest();
//     xhttp.onreadystatechange = function() {
//         if (this.readyState == 4 && (this.status == 200 || this.status == 201)) {
//             callFunction(this);
//         }
//     };
//     if(method=="GET"){
//         xhttp = makeGetRequest(xhttp,url);
//         xhttp.send();
//     }
//     else if(method=="GETPDF"){
//         xhttp = makeGetRequestForPDF(xhttp,url);
//         xhttp.send();
//     }
//     else if(method=="POST"){
//          xhttp = makePostRequest(xhttp,url);
//          xhttp.send(requestBody);
//     }
//     else if(method=="POST2"){
//         xhttp = makePostRequestFOrImages(xhttp,url);
//         xhttp.send(requestBody);
//     }
//     else if(method=="PATCH"){
//         console.log("sending patch request");
//         xhttp = makePatchRequest(xhttp,url);
//         xhttp.send(requestBody);
//     }
// }