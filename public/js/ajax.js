function makeGetRequest(url,callFunction){
    var xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            
            callFunction(this);
        }
    };
    xhttp.open("GET",url,true);
    xhttp.send();
}

function makeGetRequestForPDF(url,callFunction){
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            callFunction(this);
        }
    };
    xhttp.open("GET", url, true);
    xhttp.responseType="blob";
    xhttp.send();
}

function makePostRequest(url,requestBody,callFunction){
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
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
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {
            callFunction(this);
        }
    };
    xhttp.open("POST", url, true);
    var t = document.getElementsByTagName("META")[2].content;
    xhttp.setRequestHeader("X-CSRF-TOKEN",t);
    xhttp.send(requestBody)
}

function makePatchRequest(url,requestBody,callFunction){
    var xhttp;
    xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && (this.status == 200 || this.status == 201)) {
            callFunction(this);
        }
    };
    xhttp.open("PATCH", url, true);
    var t = document.getElementsByTagName("META")[2].content;
    xhttp.setRequestHeader("X-CSRF-TOKEN",t);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send(requestBody);
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