

function validateComment(){
    var message = document.getElementById("comment-message").value;
    var ele = document.getElementById("invalidComment");
    if(message.length == 0){
        ele.innerHTML = "<strong>The comment cannot be empty.</strong>";
        ele.style.display = "block";
    }
    if(message.length >= 255){
        ele.innerHTML = "<strong>The comment should not be more than 255 letters.</strong>";
        ele.style.display = "block";
    }
    else {
        ele.innerHTML = "";
        ele.style.display = "none";

        var message_json = JSON.stringify({"comment":message});
        makePostRequest("/project/" + project_id + "/task/" +task_id  + "/comment",message_json,displayNewComment);
        document.getElementById("comment-message").value = null;
    }
}