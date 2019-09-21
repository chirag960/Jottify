function validateStatus(){
    var title = document.getElementById("status-title").value;
    var ele = document.getElementById("invalidStatusTitle");
    if(title.length >= 30 || title.length < 3){
        ele.innerHTML = "<strong>The title should not be more than 30 letters or less than 3.</strong>";
        ele.style.display = "block";
    }
    else {
        var order = document.getElementById("select-options").value;
        ele.innerHTML = "";
        ele.style.display = "none";
        var message= JSON.stringify({'title':title,'status_id':order});
        makePostRequest("/project/"+project_id+"/status",message,displayNewStatus);
    }
}

function validateTaskTitle(){
    var title = document.getElementById("task-title").value;
        var ele = document.getElementById("invalidTaskTitle");
        if(title.length < 3 || title.length > 30){
            ele.innerHTML = "<strong>The title should not be more than 30 letters or less than 3.</strong>";
            ele.style.display = "block";
            return false;
        }
        else {
            ele.innerHTML = "";
            ele.style.display = "none";
            return true;
        }
}

// function validateTaskDesc(){
//     var desc = document.getElementById("task-desc").value;
//     var ele = document.getElementById("invalidTaskDesc");
//     if(desc.length != 0 && desc.length > 255){
//         ele.innerHTML = "<strong>The description should not be more than 255 letters.</strong>";
//         ele.style.display = "block";
//         return false;
//     }
//     else {
//         ele.innerHTML = "";
//         ele.style.display = "none";
//         return true;
//     }
// }


function validateTask(){
    var title = validateTaskTitle();
    //var desc = validateTaskDesc();
    var desc = true;

    if(title && desc){
        var title = document.getElementById("task-title").value;
        //var desc = document.getElementById("task-desc").value;
        var status = document.getElementById("select-task-options").value;
        var message;
        // if(desc.length != 0){
        //     message = JSON.stringify({"title":title,"description":desc,"status_id":status});
        // }
        // else{
        //     message = JSON.stringify({"title":title,"status_id":status});
        // }
        message = JSON.stringify({"title":title,"status_id":status});
        makePostRequest("/project/"+project_id+"/task",message,displayNewTask);
    }
}

function validateMembers(){
    return true;
}

function validateInviteMessage(){
    var message = document.getElementById("invite-message").value;
    var ele = document.getElementById("invalidInviteMessage");
    if(message.length < 3 && message.length > 255){
        ele.innerHTML = "<strong>The message should not be less than 3 and more than 255 letters.</strong>";
        ele.style.display = "block";
        return false;
    }
    else {
        ele.innerHTML = "";
        ele.style.display = "none";
        return true;
    }
}

function validateInvite(){
    //var checkMembers = validateMembers();
    //var checkMessage = validateInviteMessage();
    //if(checkMembers && checkMessage){
        if(true){
        //var membersList = document.getElementById("invite-members").value;
        //var inviteMessage = document.getElementById("invite-message").value;
        var membersList = [1,2,3];
        var inviteMessage = "New invitation";
        var message;
        message = JSON.stringify({"membersList":membersList,"inviteMessage":inviteMessage});
        makePostRequest("/project/"+project_id+"/members",message,displayInviteMembers);
    }
}