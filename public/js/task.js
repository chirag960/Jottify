$(document).ready(function(){
    $('.dropdown-trigger').dropdown();
    $('#addAttachmentModal').modal({'onCloseStart':resetAttachmentModal});
    $('#assignMemberTaskModal').modal({'onOpenStart': resetAssignMemberModal,'onOpenEnd' : focusInAssignModal});
    document.getElementById("searchBar").addEventListener("keyup", throttleSearchTask(showTasks, 500));

    var quill;
    if(description == ""){
        console.log(description);
        quill = new Quill('#quill-container', {
            modules: {
                "toolbar": false
            },
            placeholder: 'Add a description',
            theme: 'snow'  // or 'bubble'
        });

        quill.enable(false);
    }
    else{
        console.log(description);
        quill = new Quill('#quill-container', {
            modules: {
                "toolbar": false
            },
            placeholder: 'Add a description',
            theme: 'snow'  // or 'bubble'
        });
        quill.setContents(description);
        quill.enable(false);
    }

    $('.datepicker').attr("autocomplete"); 

    if(due_date != ""){


        $('.datepicker').datepicker({
            defaultDate : new Date(due_date),
            setDefaultDate:true,
            minDate: new Date(),
            setDate : Date.parse(due_date),
            onOpen : setDefaultDate
        });

    }
    else{

        $('.datepicker').datepicker({
            defaultDate : new Date(),
            setDefaultDate:true,
            minDate: new Date(),
            onOpen : setDefaultDate
        });
    }


    $('#comment-message').keydown(function(e){
        if(e.which == 13){
            e.preventDefault();
            console.log('key is pressed');
            validateComment();
        }

    });

    $('.datepicker').change(function(e){
        callFunction();
    });

    
});

$(document).on('keypress', 'input,select', function (e) {
    if (e.which == 13) {
        e.preventDefault();
    }
});

function resetAttachmentModal(){
    $("#invalidAttachment").empty();
}

function resetAssignMemberModal(){
    console.log("opening modal");
    $("#member-pattern").val("");
    //$("#member-pattern").html("");
    var allParas = $("#members-list").find("p");
    allParas.each(function(){
            $(this).show();
    });
}

function focusInAssignModal(){
    $("#member-pattern").focus();
}

function callFunction(){
    var date = $('.datepicker').val();
    if(!(date == due_date)){
        var valid = validateDate();
        $('.datepicker').datepicker('close');
        if(valid == true){
            
            var message = JSON.stringify({"date":date});
            makePostRequest("/project/" + project_id + "/task/" +task_id+ "/duedate",message,displayDueDate);
        }
    }
    
}

function setDefaultDate(){
    if(due_date != ""){
        
        $('.datepicker').datepicker('setDate', new Date(due_date));
    }
    else{
        
        $('.datepicker').datepicker('setDate', new Date());
    }
}

function redirectToProject(){
    window.location.href = "/project/" + project_id ;     
}

function backToProject(xhttp){
    var response = JSON.parse(xhttp.response);
    if(response.message == "success"){
        redirectToProject();
    }
    else{
        M.toast({html:response.message, classes:'rounded'});
    }
   
}


function updateProgressInfo(){
    if(!checklist_done){
        checklist_done = 0;
    }
    if(!checklist_item_count){
        checklist_item_count = 0;
    }
    $("#progress-info").html(checklist_done + " / " + checklist_item_count);
}

function deleteTask(){
    makeDeleteRequest("/project/"+ project_id + "/task/"+ task_id,backToProject);
}

function displayDueDate(xhttp){
    
    var response = JSON.parse(xhttp.responseText);
    if (response.message == "success"){
        $('#date_text').html(response.date);
        due_date = response.date;
        M.toast({html:"Due date updated successfully", classes:'rounded'});
    }
    else if(response.message == "errors"){
        var dateError = response.errors.message;
        var ele = document.getElementById("invalidDate");
        dateError.forEach(function(key,index){
            ele.innerHTML += "<p><strong>"+dateError[index]+"</strong></p>";
        });
        ele.style.display = "block";
    }
    else{
        M.toast({html:response.message, classes:'rounded'});
    }
}

function openDatePicker(){
    $('.datepicker').datepicker('open');
}

function validateDate(){

    var ele = document.getElementById("invalidDate");
    var date_value = $(".datepicker").val();
    var date = Date.parse(date_value);
   
    var today = new Date();
    var yesterday = today.setDate(today.getDate() - 1);
    
    if(date == "" || date == null ){
       
        ele.innerHTML = "<strong>Select a valid date</strong>";
        ele.style.display = "block";
        return false;
    }
    else if(date <= yesterday){
      
        ele.innerHTML = "<strong>Date range not valid</strong>";
        ele.style.display = "block";
        return false;
    }
    else{
        ele.style.display = "none";
        return true;
    }
}

function displayUpdatedDescription(xhttp){
   
    var response = JSON.parse(xhttp.responseText);
    if(response.message == "success"){
        description = xhttp.responseText.description;
        console.log(description);
        var quill;
        $('.ql-toolbar').remove();
        quill = new Quill('#quill-container', {
            modules: {
                "toolbar": false
            },
            theme: 'snow'
        });
        quill.enable(false);
        $('#description-button').html("mode_edit");
        M.toast({html: "Description updated", classes: 'rounded'});
    }
    else if(response.message == "errors"){
        var descError = response.errors.description;
        var ele = document.getElementById("invalidDescription");
        descError.forEach(function(key,index){
            ele.innerHTML += "<p><strong>"+descError[index]+"</strong></p>";
        });
        ele.style.display = "block";
    }
    else{
        M.toast({html: response.message, classes: 'rounded'});
    }
}

function validateDescription(){
    var ele = document.getElementById("invalidDescription");
    if(quill.getText() > 255 || quill.getText() == 0){
        ele.innerHTML = "<strong>Length of description should not be zero or more than 255</strong>";
        ele.style.display = "block";
        return false;
    }
    else if(quill.getLength() > 2048){
        ele.innerHTML = "<strong>There is too much formatting, reduce the formatting.</strong>";
        ele.style.display = "block";
        return false;
    }
    else{
        ele.style.display = "none";
        return true;
    }
}

function changeDescription(){
    var element = $('#description-button');
    if(element.html() == 'mode_edit'){
        element.html('save');
        quill = new Quill('#quill-container', {
            modules: {
                toolbar: [
                [{ header: [1, 2, 3, 4 ,false] }],
                ['bold', 'italic', 'underline','strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['clean']
                ]
            },
            theme: 'snow'  // or 'bubble'
            });
        quill.enable(true);
    }
    else if(element.html() == 'save'){
        var newDescription = JSON.stringify(quill.getContents());
        if(newDescription == JSON.stringify(description)){
           
            $('.ql-toolbar').remove();
            quill = new Quill('#quill-container', {
            modules: {
                "toolbar": false
            },
            theme: 'snow'  // or 'bubble'
            });
            //quill.setContents(JSON.parse(response.description));
            quill.enable(false);
            $('#description-button').html("mode_edit");
            return; 
        }

        var valid = validateDescription();
        if(valid == true){
            
            var message = JSON.stringify({"description": newDescription});
            console.log(message);
            
            makePatchRequest("/project/"+ project_id + "/task/" + task_id + "/description",message,displayUpdatedDescription);            
        }
    }
}

makeGetRequest("/project/"+ project_id + "/statuses",displayStatus);

makeGetRequest("/project/"+ project_id + "/task/" + task_id  +"/members",displayMemberIcons);

makeGetRequest("/project/" + project_id + "/task/" + task_id + "/checklist",displayChecklist);

makeGetRequest("/project/" + project_id + "/task/" + task_id + "/attachments",displayAttachments);

makeGetRequest("/project/" + project_id + "/task/" + task_id + "/comments",displayComments);

function openAttachmentModal(){
    if(attachment_count >= 10){
        M.toast({html:"No more than 10 attachments allowed", classes:'rounded'});
    }
    else{
        $('#addAttachmentModal').modal('open');
        $('#fileInput').val("");
        $('.file-path').val("");
    }
}


function validateItem(){
    
    var title = document.getElementById("newItem").value;
    var ele = document.getElementById("invalidNewItem");
    if(title.length > 30 || title.length < 3){
        ele.innerHTML = "<strong>The item name should not be more than 30 characters or less than 3 characters.</strong>";
        ele.style.display = "block";
    }
    else {
        ele.innerHTML = "";
        ele.style.display = "none";
        var checked = 0;
        if($("#newItemBox").prop("checked") == true){
            checked = 1;
        }
        var item_text_json = JSON.stringify({"message":title,"completed":checked});
        makePostRequest("/project/" + project_id + "/task/" + task_id + "/checklist",item_text_json,displayaddedItem);
        //document.getElementById("item_name").value = null;
    }
}

function displayChecklist(xhttp) {
    var listText = JSON.parse(xhttp.responseText);
    
    var newDiv = "";
    if(listText.length == 0){
        newDiv = "<div id='item-pretext'>Add items to your task</div>";
    }
    else{
    listText.forEach(function addToDiv(key,index){
        newDiv += "<div class='form-check'>";
        if(key.completed == "0"){
                newDiv += "<label for='checklist-"+key.id+"'><input type='checkbox' onclick='handleCheck(this,"+ key.id +")'  id='checklist-"+key.id+"' name='checklist-"+key.id+"'><span>";
            }
            else{
                newDiv += "<label for='checklist-"+key.id+"'><input type='checkbox' onclick='handleCheck(this,"+ key.id +")'  id='checklist-"+key.id+"' name='checklist-"+key.id+"' checked><span>";
            }
        newDiv += key.item; 
        newDiv += "</span></label>";
        //newDiv += "<i class='material-icons checklist-delete-icon right'>delete</i>";
        newDiv +="</div>";
    });
    }
    document.getElementById("checklist-items").innerHTML = newDiv;
    
}

function displayaddedItem(xhttp){
    
var response = JSON.parse(xhttp.responseText);

if(response.message == "success"){
if($('#item-pretext').length){
    $('#item-pretext').empty();
}

var itemText = response.checklist;
var ele = document.createElement("DIV");
ele.classList.add("form-check");
var label = document.createElement("LABEL");
setAtt(label,"for","checklist-"+itemText.id);
var inputEle = document.createElement("INPUT");
inputEle.classList.add("form-check-input");
setAtt(inputEle,"type","checkbox");
setAtt(inputEle,"id","checklist-"+itemText.id);
setAtt(inputEle,"name","checklist-"+itemText.id);
inputEle.onclick = function() {handleCheck(inputEle, itemText.id)};

label.appendChild(inputEle);

var spanEle = document.createElement("SPAN");
var textEle = document.createTextNode(itemText.item);

spanEle.appendChild(textEle);

label.appendChild(spanEle);
ele.appendChild(label);
document.getElementById("checklist-items").insertBefore(ele,document.getElementById("newItemDiv"));
$('#newItemBox').prop('checked',false);
$('#newItem').val("");
//$('#newItem').html(" ");
if($('#progressBarDiv').length){
    checklist_item_count++;
    if(itemText.completed == true){
        checklist_done++;
        inputEle.checked = true;
    }
    var progress = Math.ceil((checklist_done/checklist_item_count)*100);
    document.getElementById("progressBarDiv").style.width = progress+"%";
    
}
else{
    checklist_item_count++;
    if(itemText.completed == true){
        checklist_done++;
        inputEle.checked = true;
    }
    var text = "<div id='progress-info'>"+ checklist_done+ " / " + checklist_item_count+ "</div>";
    text += '<div class="progress" id="progressBar">';
    text+='<div id="progressBarDiv" class="determinate" role="progressbar">';
    text+='</div></div>';
    var progress_div  = $.parseHTML(text);
    $(progress_div).insertBefore("#checklist-items");
    
    var progress = Math.ceil((checklist_done/checklist_item_count)*100);
    document.getElementById("progressBarDiv").style.width = progress+"%";
}
M.toast({html:"Added new item to checklist",classes:'rounded'});
updateProgressInfo();
}
else if(response.message == "errors"){
var titleError = response.errors.message;
var ele = document.getElementById("invalidNewItem");
titleError.forEach(function(key,index){
   
    ele.innerHTML += "<p><strong>"+titleError[index]+"</strong></p>";
});
ele.style.display = "block";
}
else{
M.toast({html: response.message, classes: 'rounded'});
}
}

function addItem(){
if($('#newItemDiv').length){
validateItem();
}else{
var text = "<div class='form-check' id='newItemDiv'><label><input type='checkbox' id='newItemBox' >";
text+= "<span>";
text+= "<input type='text' name='newItem' id='newItem' placeholder='add a new Item' required>";
text+= "</span><i class='material-icons pointer' onclick='closeNewItem(event)'>close</i></label></div>";
text+= '<span class="invalid-feedback" id="invalidNewItem" role="alert"></span>';
var html = $.parseHTML(text);
$("#checklist-items").append(html);
$('#newItem').focus();
$('#newItem').keypress(function(event){

    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        validateItem();
    }

});
}

}

function closeNewItem(){
event.preventDefault();
if($('#newItem').length){
$('#newItemDiv').remove();
$('#invalidNewItem').remove();
}
}

function patchChecklist(xhttp){
    
    var response = JSON.parse(xhttp.responseText);
    var check;
    if(response.message == "success"){
    if(response.completed == 1){
        
        checklist_done++;
        check = true;
    }
    else if(response.completed == 0){
        checklist_done--;   
        check = false;
    }
    document.getElementById("checklist-" + response.checklist_id).checked = check;
    var progress = Math.ceil((checklist_done/checklist_item_count)*100);
    document.getElementById("progressBarDiv").style.width = progress+"%";
    M.toast({html:"Updated checklist",classes:'rounded'});
    updateProgressInfo();
    }
    else{
        M.toast({html:response.message,classes:'rounded'});
    }

}

function handleCheck(element,id){
    var checked = element.checked;
    var completed;
    if(checked === true){
        completed = "1";
    }
    else if(checked === false){
        completed = "0";
    }
    var data = JSON.stringify({"completed":completed});
    makePatchRequest("/project/" + project_id + "/task/" + task_id + "/checklist/"+ id, data, patchChecklist)
}

function differenceOf2Arrays (array1, array2) {
    const temp = [];
    array1 = array1.toString().split(',').map(Number);
    array2 = array2.toString().split(',').map(Number);
    
    for (var i in array1) {
    if(!array2.includes(array1[i])) temp.push(array1[i]);
    }
    for(i in array2) {
    if(!array1.includes(array2[i])) temp.push(array2[i]);
    }
   
   
    return temp.sort((a,b) => a-b);
}

function displayAssignMembers(xhttp){
    var response = JSON.parse(xhttp.responseText);
    if(response.message == "success"){
        $("#member-pattern").val("");
        $("#assignMemberTaskModal").modal('close');
        makeGetRequest("/project/"+ project_id + "/task/" + task_id  +"/members",displayMemberIcons);
        M.toast({html:"Updated members of this task",classes:'rounded'});
    }
    else{
        M.toast({html:response.message,classes:'rounded'});
    }
}

function assignMember(){
    var allParas = $("#members-list").find("input");
    var members = [];
    allParas.each(function(){
        if($(this).prop("checked") == true){
            members.push($(this).val());
        }
    });
   
    var difference = differenceOf2Arrays(members,memberList);
    if(difference.length == 0){
        $("#member-pattern").val("");
        $("#assignMemberTaskModal").modal('close');
      
    }
    else{
        var message = JSON.stringify({ids:members});
     
        makePostRequest("/project/" + project_id + "/task/" + task_id + "/members", message, displayAssignMembers);
    }
    
}

function searchMembers(){
    var pattern = $("#member-pattern").val().toUpperCase();
    var allParas = $("#members-list").find("p");
    var length = allParas.length;
    allParas.each(function(){
        var text = $(this).find('span').html().toUpperCase();
        if(text.indexOf(pattern) > -1){
            $(this).show();
        }
        else{
            $(this).hide();
        }
        
    });
}

function displayMemberIcons(xhttp){
    var membersText = JSON.parse(xhttp.responseText);
    var newDiv = "";
    var found = false;
    membersText.forEach(function (key,index){
        if(key.present == true){
            found = true;
            newDiv +="<img class='avatar-image circle' src='"+key.photo_location+"' title='"+key.name+ " ("+key.email+")'>";
        }
        
     });
     if(!found){
        $("#display-member-icons").empty();
    }
    else{
        if($("#member-avatars").length){
            document.getElementById("member-avatars").innerHTML = newDiv;
         }
         else{
            var memberDiv = "<h6>Members</h6>";
            memberDiv += "<div class='row'>";
            memberDiv += "<div id='member-avatars' class='s12 m12 l6 xl6'></div>";
            memberDiv += "</div>";

            $("#display-member-icons").append($.parseHTML(memberDiv));
            $("#member-avatars").append($.parseHTML(newDiv));
        }
    }
    displayMembers(xhttp);

}


function displayMembers(xhttp) {
    var membersText = JSON.parse(xhttp.responseText);
   
    var newDiv="";
    memberList = [];
    membersText.forEach(function (key,index){
        newDiv += "<p><label>";
      
        if(key.present == true){
                newDiv += "<input type='checkbox' checked='checked' value="+key.id+">";
                memberList.push(key.id);
        }
        else{
            newDiv += "<input type='checkbox' value="+key.id+">";
        }
        newDiv += "<span>"+key.name+"</span>";
        newDiv += "</label></p>";
     });
   
     document.getElementById("members-list").innerHTML = newDiv;
     
}

function displayNewStatus(xhttp){
    
    var response = JSON.parse(xhttp.responseText);
    if(response.message == "success"){
        M.toast({ html:"Status updated successfully",classes:'rounded' });
        status_id = response.id;
    }
    else if(response.message == "status-error"){
        M.toast({ html: response.errors,classes:'rounded' });
    }
    else{
        M.toast({ html:response.message,classes:'rounded' });
    }
}

function updateStatus(){
    var value = document.getElementById("move-status").value;
    var id_json = JSON.stringify({"status_id":value});
    makePatchRequest("/project/" + project_id + "/task/" + task_id + "/status",id_json,displayNewStatus);
}



function setAtt(element,att,val){
    var attribute = document.createAttribute(att);
    attribute.value = val;
    element.setAttributeNode(attribute); 
}

function displayAttachments(xhttp) {
    var attachmentText = JSON.parse(xhttp.responseText);
   
    var newDiv = "";
    if(attachmentText.length != 0){
    attachmentText.forEach(function(key,index){
        if(key.type == "image"){
            newDiv += "<div id='attachment-"+key.id+"' class='attachments-image' style='background-image:url("+JSON.stringify(key.location)+")' onmouseover='showOps(this)' onmouseout='hideOps(this)'>";
        }
        else{
            newDiv += "<div id='attachment-"+key.id+"' class='attachments-image' style='background-image:url(/media/task_attachments/default-document-icon.png)' onmouseover='showOps(this)' onmouseout='hideOps(this)'>"; //onmouseover='showNameAndOps()'
        }
        newDiv += "<div class='image-title truncate'>"+key.name+"</div>";
        newDiv += '<div class="image-ops"><i class="material-icons right none" onclick="deleteAttachment('+key.id+')">delete</i>';
        newDiv += '<a class="download-icon" href='+JSON.stringify(key.location)+' download><i class="material-icons right none">file_download</i></a></div>';
        newDiv += "</div>";
     });

    }
    else{
        newDiv += "<div id='attach-pretext'>Add attachments to your task</div>";
    }
     var newDivHtml = $.parseHTML(newDiv);
    $("#attachment-list").append(newDivHtml);
}

function displayComments(xhttp) {
    var listText = JSON.parse(xhttp.responseText);
    
    var newDiv = "";
    if(listText.length == 0){
        document.getElementById("comment-list").innerHTML = "<p id='comment-pre-text'>No comments yet. Say something</p>";
    }
    else{
        listText.forEach(function addToDiv(key,index){
         newDiv += "<div class='comment-div' id='comment-"+key.id+"'>";
         newDiv += "<div class='commenter-name'>";
         if(user_id == key.user_id){
            newDiv += "You";
         }
         else{
            newDiv += key.name;
         }
         newDiv += "</div>";
         newDiv += "<div class='commenter-message'>"+key.message+"</div>"

         var date = new Date(key.created_at);

         var hrs = (date.getHours()<10?'0':'') + date.getHours();
         var mins = (date.getMinutes()<10?'0':'') + date.getMinutes();
         var day = (date.getDate()<10?'0':'') + date.getDate();
         var month = date.getMonth()+1;
         var year = date.getFullYear();
         var formatted_date = hrs + ":" + mins + ", " + day + "-" + month + "-" + year;
         newDiv += "<div class='commenter-time'>"+formatted_date+"</div>"
         newDiv+= "</div>";
     });
     document.getElementById("comment-list").innerHTML = newDiv;
    }
    
}

function displayStatus(xhttp){
    var statusList = JSON.parse(xhttp.responseText);
    if(statusList.length == 1){

    }
    else{
            var newDiv = " <div class='input-field col s12'><select id='move-status' onchange='updateStatus()'>";
            statusList.forEach(function addToDiv(key,index){
            if( status_id == key.id ){
                newDiv += "<option value="+key.id+" selected='selected'>"+key.title+"</option>";
            }
            else{
                newDiv += "<option value="+key.id+">"+key.title+"</option>";
            }
        });
            newDiv +="</select><label>Update Status</label></div>";
            document.getElementById("status-form").innerHTML = newDiv;
    }
    $('select').formSelect();
}

function displayDate(xhttp){
    
    document.getElementById("date_text").innerHTML = xhttp.responseText.toString();
}

function showOps(ele) {
elements = ele.querySelectorAll('.none');
for (var i = 0; i < elements.length; i++) {
    elements[i].style.display="block";
    }      
}

function hideOps(ele){
    elements = document.getElementsByClassName("none");
    for (var i = 0; i < elements.length; i++) {
    elements[i].style.display="none";	
    }
}

function displayDeleteAttachment(xhttp){
    var response = JSON.parse(xhttp.responseText);
    if(response.message == "success"){
        $("#attachment-"+response.id).remove();
        attachment_count = attachment_count-1;
        console.log("count is"+ attachment_count);
        if(attachment_count == 0){
            var newDiv = "<div id='attach-pretext'>Add attachments to your task</div>";
            var newDivHtml = $.parseHTML(newDiv);
            $("#attachment-list").append(newDivHtml);
        }
        M.toast({html:"attachment deleted",classes:'rounded'});
    }
    else{
        M.toast({html:response.message,classes:'rounded'});
    }
}

function deleteAttachment(id){
    console.log("deleteCalled");
    makeDeleteRequest("/project/"+ project_id + "/task/"+ task_id+"/attachment/"+id,displayDeleteAttachment);
}

function updateAttachment(xhttp){
    console.log(xhttp.responseText);
    $('#invalidAttachment').empty();
    var response  = JSON.parse(xhttp.responseText);
    if(response.message == "success"){
        $('#attach-pretext').remove();
        $('#fileInput').val("");
        $('.file-path').val("");
        $('#addAttachmentModal').modal('close');
        var attachmentText = response.attachments;
        var newDiv = "";
        attachmentText.forEach(function(key,index){
            if(key.type == "image"){
                newDiv += "<div id='attachment-"+key.id+"' class='attachments-image' style='background-image:url("+JSON.stringify(key.location)+")' onmouseover='showOps(this)' onmouseout='hideOps(this)'>";
            }
            else{
                newDiv += "<div id='attachment-"+key.id+"' class='attachments-image' style='background-image:url(/media/task_attachments/default-document-icon.png)' onmouseover='showOps(this)' onmouseout='hideOps(this)'>"; //onmouseover='showNameAndOps()'
            }
            newDiv += "<span class='image-title truncate'>"+key.name+"</span>";
            newDiv += '<div class="image-ops"><i class="material-icons right none" onclick="deleteAttachment('+key.id+')">delete</i>';
            newDiv += '<a class="download-icon" href='+JSON.stringify(key.location)+' download><i class="material-icons right none">file_download</i></a></div>';
            //newDiv += "<div class='card-body' id='status-"+key.id+"'><p class='text-center addTask' data-toggle='modal' data-target='#newTaskForm'  onclick='sendID("+key.id+")'>+ Add task</p></div>";
            newDiv += "</div>";
        });
        var newDivHtml = $.parseHTML(newDiv);
        $("#attachment-list").append(newDivHtml);
        
        attachment_count = parseInt(attachment_count) + parseInt(response.attachments.length);
        M.toast({html:"Successfully added attachments",classes:'rounded'});
    }
    // else if(response.message == "errors"){
    //     var attachmentError = response.errors;
    //     var ele = document.getElementById("invalidAttachment");
    //     attachmentError.forEach(function(key,index){
    //         ele.innerHTML += "<p><strong>"+attachmentError[index]+"</strong></p>";
    //     });
    //     ele.style.display = "block";
    // }
    else if(response.message == "error"){
        var ele = document.getElementById("invalidAttachment");
        ele.innerHTML += "<p><strong>"+response.error+"</strong></p>";
        ele.style.display = "block";
    }
    else {
        M.toast({html: response.message, classes: 'rounded'});
    }
}

function displayNewComment(xhttp){
    var response = JSON.parse(xhttp.responseText);
    if(response.message == "success"){
        console.log(response.comment);
        var key = response.comment;
        var newDiv;
         newDiv = "<div class='comment-div' id='comment-"+key.id+"'>";
         newDiv += "<div class='commenter-name'>";
         newDiv += "You";
         newDiv += "</div>";
         newDiv += "<div class='commenter-message'>"+key.message+"</div>"
         var date = new Date(key.created_at);

         var offset = date.getTimezoneOffset()
         //var new_date = new Date()
         //new_date.setTime(date.getTime() - offset*60000);
         //console.log(new_date);
         var hrs = (date.getHours()<10?'0':'') + date.getHours();
         var mins = (date.getMinutes()<10?'0':'') + date.getMinutes();
         var day = (date.getDate()<10?'0':'') + date.getDate();
         var month = date.getMonth()+1;
         var year = date.getFullYear();
         var formatted_date = hrs + ":" + mins + ", " + day + "-" + month + "-" + year;
         newDiv += "<div class='commenter-time'>"+formatted_date+"</div>"
         newDiv+= "</div>";

         var commentDiv = $.parseHTML(newDiv);
         if($("#comment-pre-text").length){
            $("#comment-pre-text").remove();
         }
         $("#comment-list").prepend(commentDiv);
         $("#comment-list").scrollTop = $("#comment-list")[0].scrollHeight;
         $("#comment-message").val("");
         $("#comment-message").css('height','46px');
         M.toast({html:"Successfully added comment",classes:'rounded'});
    }
    else if(response.message == "errors"){
        var titleError = response.errors.comment;
        var ele = document.getElementById("invalidComment");
        titleError.forEach(function(key,index){
            ele.innerHTML += "<p><strong>"+titleError[index]+"</strong></p>";
        });
        ele.style.display = "block";
    }
    else{
        M.toast({html: response.message, classes: 'rounded'});
    }
    
}
    
function sendID(id){
    var inputTag = document.getElementById("input_status_id");
    //console.log(id);
    inputTag.value = id;
}

function validateImages(){
    var fileList = document.getElementById("fileInput").files;
    var filePath = document.getElementById("fileInput").value;
    var fileLength =fileList.length;
    var ele = document.getElementById("invalidAttachment");
    console.log("count is "+attachment_count);
    console.log(fileLength);
    if(filePath == ""){
        ele.innerHTML = "<strong>Please add/upload a attachment</strong>";
        ele.style.display = "block";
        return false;
    }
    else if((parseInt(fileLength)+parseInt(attachment_count)) > 10){
        ele.innerHTML = "<strong>No more than 10 files are allowed in 1 task.</strong>";
        ele.style.display = "block";
        return false;
    }
    else{
        var valid = true;
        Array.prototype.forEach.call(fileList, file => {
            var size = file.size;
            if(size >= 2097152){
                ele.innerHTML = "<strong>Each file size should not be more than 2MB</strong>";
                ele.style.display = "block";
                valid = false;
                }
            });
            if(valid == false){
                return false;
            }
            else{
                Array.prototype.forEach.call(fileList, file => {
                    var name = file.name;
                    var extension = name.substring(name.lastIndexOf('.') + 1).toLowerCase();
                    console.log(extension);
                    if(!(extension == "png" || extension == "jpg" || extension == "jpeg" || extension == "pdf" || extension == "rft" || extension == "doc" || extension == "docx" || extension == "txt")){
                        ele.innerHTML = "<strong>Please attach image, text or pdf only</strong>";
                        ele.style.display = "block";
                        valid = false;
                    }
                });
                if(valid == false){
                    return false;
                }
            }
    }
    ele.style.display = "none";
    return true;
}

function submit_files(){
    var fileList = document.getElementById("fileInput").files;
    var filePath = document.getElementById("fileInput").value;
    var fileLength =fileList.length;
    var formData = new FormData();
    //var valid = validateImages();
    var valid = true;
    console.log(valid);
    if(valid == true){
        Array.prototype.forEach.call(fileList, file => {
        formData.append("files[]",file);
        });
        makePostRequestForImages("/project/" + project_id + "/task/" + task_id + "/attachment",formData,updateAttachment);
    }
    
}

function stripHTML(dirtyString){
    var strippedText = $("<div/>").html(dirtyString).text();
    return strippedText;
}

function validateComment(){
    var dirtyString = document.getElementById("comment-message").value;
    var message = stripHTML(dirtyString);
    console.log(message.length + "length");
    var ele = document.getElementById("invalidComment");
    if(message.length == 0){
        ele.innerHTML = "<strong>The comment cannot be empty.</strong>";
        ele.style.display = "block";
    }
    else if(message.length >= 255){
        ele.innerHTML = "<strong>The comment should not be more than 255 letters.</strong>";
        ele.style.display = "block";
    }
    else {
        ele.innerHTML = "";
        ele.style.display = "none";

        var message_json = JSON.stringify({"comment":message});
        makePostRequest("/project/" + project_id + "/task/" +task_id  + "/comment",message_json,displayNewComment);
    }
}