document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("searchBar").addEventListener("keyup", throttleSearchTask(showTasks, 500));
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems,{});
    $('.modal').modal();
    $('#addInviteProjectModal').modal({'onCloseStart':removeInputValues});
    $('#taskMemberModal').modal({'onCloseStart':removeMembersValues});
    $('.tooltipped').tooltip({'outDuration':0});

    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems, {edge: 'right',draggable: true,});

    $('.dropdown-trigger').dropdown();
    var elems = document.querySelectorAll('.chips-placeholder');
    var instances = M.Chips.init(elems,{});

    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems, {});

});

$(document).on('keypress', 'input,select', function (e) {
    if (e.which == 13) {
        e.preventDefault();
    }
});

jQuery(document).bind("keydown", function(e){
    if(e.ctrlKey && e.keyCode == 70){
        e.preventDefault();
        $('#searchBar').focus();
    }
    if(e.ctrlKey && e.keyCode == 65){
        e.preventDefault();
        var opened = $('#addTaskModal').hasClass('open');
        if(opened == true){
            $('#addTaskModal').modal('close');
        }
        else{
            $('.modal').modal('close');
            openTaskModal();
        }
    }
    if(e.ctrlKey && e.keyCode == 83){
        e.preventDefault();

        if($('#addStatusModal').hasClass('open')){
            $('#addStatusModal').modal('close');
        }
        else{
            $('.modal').modal('close');
            $('#addStatusModal').modal('open');
            $('#status-title').focus();
        }
    }
    if(e.ctrlKey && e.keyCode == 73){
        e.preventDefault();

        if($('#addInviteProjectModal').hasClass('open')){
            $('#addInviteProjectModal').modal('close');
        }
        else{
            $('.modal').modal('close');
            $('#addInviteProjectModal').modal('open');
            $('#invite-members').focus();
        }
    }
    if(e.ctrlKey && e.keyCode == 80){
        e.preventDefault();

        if($('.sidenav').isOpen){
            $('.sidenav').sidenav('close');
        }
        else{
            $('.modal').modal('close');
            $('.sidenav').sidenav('open');
        }
    }

    
});

    var statusList = new Array();
    
    //var project_id = "{!! $project['id'] !!}"; 
    var projectsText;
    var membersText;
    var tasksText;
    var hiddenTasks = [];
    var checkedMembers = [];
    makeGetRequest("/project/"+ project_id +"/statuses",displayStatus);

    function removeInputValues(){
        console.log($("#users-list").find("input"));
        checkedMembers = [];
        $("#users-list").empty();
        $("#invite-members").html("");
        $("#invite-members").val("");
        $("#membersView").empty();

    }

    function removeMembersValues(){
        $("#taskMembersView").empty();
        $("#taskTitleInModal").empty();
        $("#taskMembersCount").empty();
    }

    function openSideNav(){
        $('.sidenav').sidenav('open');
    }

    function displayInviteMembers(xhttp){
        var response = JSON.parse(xhttp.responseText);
        if(response.message == "success"){
            $('#addInviteProjectModal').modal('close');
            var newMemberText = response.members;
            console.log(newMemberText.length + "this is the length");
            var newDiv = "";
            newMemberText.forEach(function (key,index){
                newDiv +="<li id='member-"+key.id+"' class='collection-item avatar min-height-auto' onmouseover='showOps(this)' onmouseout='hideOps(this)'>";
                newDiv +="<img class='circle' src='"+key.photo_location+"'>";
                newDiv +="<span class='title'>"+key.name+"<span class='role'></span></span>";
                newDiv +="<p>"+key.email+"</p>";
                newDiv +='<div class="overlay-options none">';
                newDiv +='<i title="make admin" class="material-icons member-options" id="admin-'+key.id+'" onclick="addAdmin('+key.id+',this)">group_add</i>';
                newDiv +='<i title="remove from project" class="material-icons member-options" id="delete-'+key.id+'" onclick="deleteMember('+key.id+',this)">delete</i>';
                newDiv +='</div>';
         });
            var memberDiv = $.parseHTML(newDiv);
            $("#members-list").append(memberDiv);
            if($('#project-admin-avatars img').length){
                var left = $('#project-admin-avatars img').length;
            }
            
            console.log(memberCount + "this is the count");
            if(memberCount > 10){
                console.log("memberCount is more than 10, appending in the span");
                var leftOut = memberCount - 10 + newMemberText.length;
                $("#left-member-count").html(leftOut);
            }
            else if(memberCount == 10){
                console.log("memberCount is equal to 9,creating the span");
                var newDiv = "<span id='admins-icons' class='avatar-image circle all-member-icon' onclick='openSideNav()'>+"
                newDiv += "<span id='left-member-count'>"+ newMemberText.length +"</span></span>";
                var memberIcons = $.parseHTML(newDiv);
                $('#project-admin-avatars').append(memberIcons);
            }
            else{
                console.log("memberCount is less than 10, appending the icons");
                var maxLoop = 9 - memberCount;
                var loopCount = (newMemberText.length < maxLoop)?newMemberText.length:maxLoop;
                var newDiv = "";
                console.log("looping for" + loopCount);
                for(var i=0; i<loopCount; i++){
                    
                    newDiv +="<img id='icon-"+newMemberText[i].id+"' class='avatar-image circle' src='"+newMemberText[i].photo_location+"' title='"+newMemberText[i].name+" ("+newMemberText[i].email+")'>";           
                }
                

                var memberIcons = $.parseHTML(newDiv);
                if(newMemberText.length > maxLoop){
                    var left_members = newMemberText.length - maxLoop;
                    console.log("left out members are"+left_members);
                    newDiv += "<span id='admins-icons' class='avatar-image circle all-member-icon' onclick='openSideNav()'>+"
                    newDiv += "<span id='left-member-count'>"+left_members+"</span></span>";
                }
                var memberIcons = $.parseHTML(newDiv);
                console.log(memberIcons);
                $('#project-admin-avatars').append(memberIcons);                
            }
            memberCount = parseInt(memberCount) + parseInt(newMemberText.length);
            M.toast({html: "Added members to this project. A invitation mail has been sent", classes: 'rounded'});
        }
        else if(response.message == "errors"){
            var membersError = response.errors.members;
            var messageError = response.errors.message;
            if(membersError){
                var ele = document.getElementById("invalidInviteMembers");
                titleError.forEach(function(key,index){
                    ele.innerHTML += "<p><strong>"+titleError[index]+"</strong></p>";
                });
                ele.style.display = "block";
            }
            if(descError){
                var ele = document.getElementById("invalidTaskDesc");
                descError.forEach(function(key,index){
                  
                    ele.innerHTML += "<p><strong>"+descError[index]+"</strong></p>";
                });
                ele.style.display = "block";
            }
        }
        else{
            M.toast({html: response.message, classes: 'rounded'});
        }
    }

    function displayNewStatus(xhttp){

        var response = JSON.parse(xhttp.responseText);
        if(response.message == "success"){
            if($("#status-text").length){
                $("#status-text").remove();
            }
            document.getElementById("status-title").value = null;
            $('#addStatusModal').modal('close');

            var newDiv = "";
            newDiv += "<div class='status-panel' id='status-panel-"+response.id+"'><div class='card-panel status-name grey lighten-4'><div class='card-header' onmouseover='showOps(this)' onmouseout='hideOps(this)'>";
            newDiv += "<span>"+response.title+"</span>";
            newDiv += '<span class="status-ops"><i class="material-icons right none" onclick="deleteStatus('+response.id+')">delete</i></span>';
            newDiv+= "</div>";
            newDiv += "<div class='card-content status-body' id='status-"+response.id+"'><p class='text-center addTask' onclick='openTaskModal("+response.id+")'>+ Add task</p><div class='task-list' id='status-task-"+response.id+"'></div></div>";
            newDiv += "</div></div>";
            var newStatus = $.parseHTML(newDiv);
            if(response.beforeStatusId == -1){
                $("#status-list").prepend(newStatus);
            }
            else{
                $(newStatus).insertAfter($("#status-panel-"+response.beforeStatusId));
            }
            
            statusList.splice(response.order,0,response.id+"-"+response.title);
            console.log(statusList);
            M.toast({html: "New status added successfully", classes: 'rounded'});
            addStatusToSelectMenu()
            addStatusToTaskMenu();

        }
        else if(response.message == "errors"){
            var titleError = response.errors.title;
            var ele = document.getElementById("invalidStatusTitle");
            titleError.forEach(function(key,index){
               
                ele.innerHTML += "<p><strong>"+titleError[index]+"</strong></p>";
            });
            ele.style.display = "block";
        }
        else{
            M.toast({html: response.message, classes: 'rounded'});
        }
    }

    function displayNewTask(xhttp){
       
        var response = JSON.parse(xhttp.responseText);
        if(response.message == "success"){
            document.getElementById("task-title").value = null;
            //document.getElementById("task-desc").value = null;
            $('#addTaskModal').modal('close');
            var id = "status-task-"+response.status_id;
            var newDiv = "";
            newDiv += "<div id='task-"+response.id+"' onclick='goToTask("+response.id+")' class='card task-card card-body' onmouseover='showOps(this)' onmouseout='hideOps(this)'>";
            newDiv += "<div><p>"+response.title+"</p></div>";
            newDiv += "<span class='task-ops'><i id='deleteTask-"+response.id+"' class='material-icons right none' onclick='deleteTask(event)'>delete</i></span>";
            newDiv +="</div>";
            var taskDiv = $.parseHTML(newDiv);
            $("#"+id).append(taskDiv);
            M.toast({html: "New task added successfully", classes: 'rounded'});
        }
        else if(response.message == "errors"){
            var titleError = response.errors.title;
            var descError = response.errors.description;
            if(titleError){
                var ele = document.getElementById("invalidTaskTitle");
                titleError.forEach(function(key,index){
                   
                    ele.innerHTML += "<p><strong>"+titleError[index]+"</strong></p>";
                });
                ele.style.display = "block";
            }
        }
        else{
            M.toast({html: response.message, classes: 'rounded'});
        }
    }

    function displayDeleteStatus(xhttp){
    var response = JSON.parse(xhttp.responseText);
    if(response.message == "success"){
        $("#status-panel-"+response.id).remove();
        //Move all status forward
        //recalculate the select form of status and task modal.
        M.toast({html:"Status deleted",classes:'rounded'});
        console.log($("#status-list").length);
        statusList.splice( $.inArray(response.id+"-"+response.title,statusList) ,1 );
        if(statusList.length == 0){
            $("#status-list").html("<h6 class='grey-text' id='status-text'>No status found. Create a status (Ctrl + s). </h6>");
        }
        
        console.log(statusList);
        addStatusToSelectMenu()
        addStatusToTaskMenu();
    }
    else{
        M.toast({html:response.message,classes:'rounded'});
    }

}

function deleteStatus(id){
    console.log("deleteCalled");
    makeDeleteRequest("/project/"+ project_id + "/status/"+ id,displayDeleteStatus);
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

    function displayStatus(xhttp) {
        var statusText = JSON.parse(xhttp.responseText);
       
        var newDiv = "";
        if(statusText.length == 0){
            $("#status-list").html("<h6 class='grey-text' id='status-text'>No status found. Create a status (Ctrl + s). </h6>");
        }
        else{
            statusText.forEach(function addToDiv(key,index){
            newDiv += "<div class='status-panel' id='status-panel-"+key.id+"'><div class='card-panel status-name grey lighten-4'><div class='card-header' onmouseover='showOps(this)' onmouseout='hideOps(this)'>";
            newDiv += "<span>" + key.title + "</span>";
            newDiv += '<span class="status-ops"><i class="material-icons right none" onclick="deleteStatus('+key.id+')">delete</i></span>';
            newDiv += "</div>";
            newDiv += "<div class='card-content status-body' id='status-"+key.id+"'><p class='text-center addTask' onclick='openTaskModal("+key.id+")'>+ Add task</p><div class='task-list' id='status-task-"+key.id+"'></div></div>";
            newDiv += "</div></div>";
            statusList.push(key.id+"-"+key.title);
         });
         console.log(statusList);
         document.getElementById("status-list").innerHTML = newDiv;
         makeGetRequest("/project/"+project_id+"/tasks",displayTasks);
        }
         addStatusToSelectMenu();
         addStatusToTaskMenu();
        
    }

    function addStatusToSelectMenu(){
        var ele = document.getElementById("status_order");
        var text;
        if(statusList.length > 0){
            text = '<select name="order" id="select-options">';
            text+='<option value="new" class="blue-text">First</option>'
                for(var i=0;i<statusList.length;i++){
                    order = statusList[i].slice(0, statusList[i].indexOf('-'))    
                    name = statusList[i].replace(order+'-',"");
                    if(i == statusList.length-1){
                        text+='<option value="'+order+'" class="blue-text" selected="selected">after '+name+'</option>';
                    }
                    else{
                        text+='<option value="'+order+'">after '+name+'</option>';
                    }
                }
            text+='</select>';
            text+='<label>Select order</label>';
            ele.innerHTML = text;
            $('select').formSelect();
        }
        else{
            text = '<input type="hidden" name="select-options" id="select-options" value="new">';
            ele.innerHTML = text;
        }
        console.log("this is text"+text);
    }

    function addStatusToTaskMenu(status_id = -1){
        var ele = document.getElementById("status_order_in_task");
        if(statusList.length > 0){
            var text = '<select name="order" id="select-task-options">';
                for(var i=0;i<statusList.length;i++){
                    order = statusList[i].slice(0, statusList[i].indexOf('-'));
                    if(status_id == order){
                        name = statusList[i].replace(order+'-',"");
                        text+='<option value="'+order+'" selected="selected">in '+name+'</option>';
                    }
                    else{
                        name = statusList[i].replace(order+'-',"");
                        text+='<option value="'+order+'">in '+name+'</option>';
                    }
                }
            text+='</select>';
            text+='<label>Select Status</label>';
            ele.innerHTML = text;
            $('select').formSelect();
        }
        else{
            var text = '<input type="hidden" name="select-options" id="select-options" value="0">';
            ele.innerHTML = text;
        }
    }

    function openStatusModal(){
        $('#addStatusModal').modal('open');
        $('#status-title').focus();
    }

    function openTaskModal(status_id = -1){
        
        if(statusList.length == 0){
            M.toast({html: "First add a new status (Ctrl + s)",classes: 'rounded'});
        }
        else{
            $('#addTaskModal').modal();
            $('#addTaskModal').modal('open');
            $('#task-title').focus();
            addStatusToTaskMenu(status_id);
        }
    }

    function goToTask(id){
        window.location.href = "/project/"+project_id+"/task/"+id;
    }

    function displayDeleteTask(xhttp){
        var response = JSON.parse(xhttp.responseText);
        if(response.message == "success"){
            $("#task-"+response.id).remove();
            M.toast({html:"Task successfully deleted", classes:'rounded'});
        }
        else{
            M.toast({html:response.message,classes:'rounded'});
        }
    }

    function deleteTask(event){
        event.stopPropagation();
        console.log("deleteCalled");
        console.log(event);
        var deleteEle = event.target;
        var did = deleteEle.id.split("-");
        var id = did[1];
        makeDeleteRequest("/project/"+ project_id + "/task/"+ id,displayDeleteTask);
    }

    function displayTasks(xhttp) {
       
        tasksText = JSON.parse(xhttp.responseText);
       
        tasksText.forEach(function addToDiv(key,index){
            var newDiv = "";
            var id = "status-task-"+key.status_id;
            var status_div = document.getElementById(id);
            newDiv += "<div id='task-"+key.id+"' onclick='goToTask("+key.id+")' class='card task-card card-body' onmouseover='showOps(this)' onmouseout='hideOps(this)'>";
            newDiv += "<div><p>"+key.title+"</p></div>";
            newDiv += "<span class='task-ops'><i id='deleteTask-"+key.id+"' class='material-icons right none' onclick='deleteTask(event)'>delete</i></span>";
            if(key.checklist_item_count != null){
                var width = Math.ceil((key.checklist_done/key.checklist_item_count)*100) + "%";
                newDiv+="<div><span class='progress-info'>"+key.checklist_done + " / " +key.checklist_item_count+"</span>";
                newDiv+="<div class='progress'><div class='determinate' style='width:"+width+"'></div></div></div>";
            }
            newDiv +="<div style='margin-bottom:2%;'>";
            if(key.due_date){
                var date = new Date(key.due_date);
                var eng_date;
                if(date.getFullYear() == new Date().getFullYear()){
                    eng_date = date.toLocaleDateString(undefined,{
                                day : 'numeric',
                                month : 'short',
                            });
                }
                else{
                    eng_date = date.toLocaleDateString(undefined,{
                                day : 'numeric',
                                month : 'short',
                                year : 'numeric',
                            });
                }
                if(Date.parse(date) < Date.parse(new Date())){
                    newDiv +="<div class='task-icons-info date-div' style='background-color:#eb5a46'><i class='material-icons task-icons text-white'>access_time</i><span class='text-white'>"+eng_date+"</span></div>";    
                }
                else{
                    newDiv +="<div class='task-icons-info date-div'><i class='material-icons task-icons'>access_time</i><span>"+eng_date+"</span></div>";    
                }
            }
            if(key.description){
                newDiv +="<div class='task-icons-info' title='this task has a description'><i class='material-icons task-icons desc-icons'>description</i></div>";
            }
            if(key.attachment_count != 0){
                newDiv +="<div class='task-icons-info'><span><i class='material-icons task-icons pin-icon'>attachment</i></span><span>"+key.attachment_count+"</span></div>";
            }
            if(key.comment_count != 0){
                newDiv +="<div class='task-icons-info'><i class='material-icons task-icons'>mode_comment</i><span>"+key.comment_count+"</span></div>";
            }
            newDiv+="</div>";
            newDiv+="<div class='task-members'>";
                if(key.members){
                    var length = key.members.length;
                    var maxLoop = (length < 5)?length:5;
                    
                    for(var i = 0;i < maxLoop; i++){
                       
                        newDiv +="<img class='member-"+key.members[i].id+" avatar-image circle' src='"+key.members[i].photo_location+"' title='"+key.members[i].name+" ("+key.members[i].email+")'>";           
                    }
                    if((length - maxLoop) != 0){
                        newDiv += "<span class='avatar-image circle all-member-icon' onclick='openTaskMemberModal(event,"+key.id+","+JSON.stringify(key.title)+")'><span id='left-task-count'>All</span></span>";
                    }
                }
            newDiv+="</div>";
            newDiv+="</div>";
            
            var taskDiv = $.parseHTML(newDiv);
            $("#"+id).append(taskDiv);
         });

         
    }

    function displayTaskMembersModal(xhttp){
        
        var response = JSON.parse(xhttp.responseText);
        if(response.message == "success"){
            var membersText = response.members;
            var newDiv = "";
            membersText.forEach(function(key,index){
                newDiv +="<img class='avatar-image circle' src='"+key.photo_location+"' title='"+key.name+" ("+key.email+")'>";           
            });
            var membersIcons = $.parseHTML(newDiv);
            $("#taskMembersView").append(membersIcons);
            $("#taskMembersCount").html("Total Members: "+membersText.length);
            $("#taskMemberModal").modal('open');
        }
        else if(response.message == "error"){
            $("#taskTitleInModal").empty();
            M.toast({html: response.error, classes: 'rounded'});
        }
    }

    function openTaskMemberModal(event,id,title){
        event.stopPropagation();
        $("#taskTitleInModal").html('"'+title+'"');
        makeGetRequest("/project/"+ project_id + "/task/" + id  +"/onlyMembers",displayTaskMembersModal);
        
    }

    function showAdminAdd(xhttp){
        var response = JSON.parse(xhttp.responseText);
        if(response.message == 'success'){
            var admin_icon = document.getElementById("admin-"+response.id);
            var name = $("#member-"+response.id).find(".title")[0].firstChild.textContent;
            $("#member-"+response.id).find(".role").html('(admin)');
            admin_icon.innerHTML = "remove_circle";
            admin_icon.title = "remove as admin";
            admin_icon.onclick = function () {
                                     removeAdmin(response.id,admin_icon);
                                };
            M.toast({html: "Successfully added as admin", classes: 'rounded'});
        }
        else{
            M.toast({html: response.message, classes: 'rounded'});
        }
        

    }

    function addAdmin(id,element){
        var message = JSON.stringify({"admin":"1"});
        makePatchRequest("/project/"+project_id+"/member/"+id,message,showAdminAdd);
    }

    function showAdminRemove(xhttp){
        console.log(xhttp.responseText);
        var response = JSON.parse(xhttp.responseText);
        if(response.message == 'success'){
            $("#member-"+response.id).find(".role").html('');
            var admin_icon = document.getElementById("admin-"+response.id);
            admin_icon.innerHTML = "group_add";
            admin_icon.title = "make admin";
            admin_icon.onclick = function () { 
                                    addAdmin(response.id,admin_icon);
                                };
            M.toast({html: "Successfully removed as admin", classes: 'rounded'});
            $("#icon-"+response.id).remove();
        }
        else{
            M.toast({html: response.message, classes: 'rounded'});
        }
        
    }

    function removeAdmin(id,element){
        var message = JSON.stringify({"admin":"0"});
        makePatchRequest("/project/"+project_id+"/member/"+id,message,showAdminRemove);
    }

    function showDeleteMember(xhttp){
        console.log("deleted");
        var response = JSON.parse(xhttp.responseText);
        if(response.message == 'success'){
            var member_ele = document.getElementById("member-"+response.id);
            member_ele.parentNode.removeChild(member_ele);
            
            if($("#icon-"+response.id).length){
                $("#icon-"+response.id).remove();
            }
            else{
                var count = parseInt($("#left-member-count").html()) - 1;
                console.log(count);
                if(count != 0){
                    console.log("inside not zero");
                    $("#left-member-count").html(count);
                }
                else{
                    console.log("inside is zero");
                    $("#admins-icons").remove();
                }
                
            }
            $(".member-"+response.id).remove();
            memberCount--;
            M.toast({html: "Successfully deleted member", classes: 'rounded'});
        }
        else{
            M.toast({html: response.message, classes: 'rounded'});
        }
    }

    function deleteMember(id,element){
        element.parentNode.removeChild(element);
        makeDeleteRequest("/project/"+project_id+"/member/"+id,showDeleteMember);
    }

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
        var ele = document.getElementById("invalidInviteMembers");
        if(checkedMembers.length == 0){
            ele.innerHTML = "<strong>Select members to invite to the project</strong>";
            ele.style.display = "block";
            return false;
        }
        else{
            return true;
        }
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
        var checkMembers = validateMembers();
        var checkMessage = validateInviteMessage();
        if(checkMembers && checkMessage){
            var inviteMessage = document.getElementById("invite-message").value;
            var message = JSON.stringify({"membersList":checkedMembers,"inviteMessage":inviteMessage});
            makePostRequest("/project/"+project_id+"/members",message,displayInviteMembers);
        }
    }

    $('.chips').chips();
        
    if(document.getElementById("invite-members")){
        console.log("inside if");
        document.getElementById("invite-members").addEventListener("keyup", throttleInviteMember(showUsers, 500));
    }

    function throttleInviteMember(fn, threshhold) {
        console.log("hey");
        var last,deferTimer;
        return function () {
            var now = +new Date,
                args = arguments;
            if (last && now < last + threshhold) {
                // hold on to it
                clearTimeout(deferTimer);
                deferTimer = setTimeout(function () {
                last = now;
                fn(document.getElementById("invite-members").value);
                }, threshhold);
            } else {
                console.log("hey");
                last = now;
                fn(document.getElementById("invite-members").value);
            }
        };
    }

    var membersList;

    function showUsers(pattern){
        if(pattern.length > 2){
            makeGetRequest("/project/"+project_id+"/allMembers?pattern="+pattern,showMembersDropDown,false);
        }
        else{
            if($('#users-list')){
                $('#users-list').empty();
            }
        }
    }

    function showMembersDropDown(xhttp){
        console.log(JSON.parse(xhttp.responseText));
        var response =  JSON.parse(xhttp.responseText);
        document.getElementById("results").innerHTML = "";
        if(response.message == "errors"){

        }
        else if(response.message == "success"){
            membersList = response.members;
            var uList = $("#users-list");
            uList.empty();
            if(membersList.length == 0){
                //ulist.addClass('dropdown-content')
                text = "<li class='black-text white'><a>No users found</a></li>";
                var lList = $(text);
                uList.append(lList);
            }
            else{
                //ulist.addClass('dropdown-content')
                membersList.forEach(function(key,index){
                    var name = key.name;
                    console.log(key);

                    text = "<li class='black-text title-list white'><label>";
                    if($.inArray(key.id,checkedMembers) == -1){
                        text+="<input type='checkbox' id='checkbox-"+key.id+"' onclick='checkedMember("+key.id+","+JSON.stringify(key.name)+")'><span class='black-text'>"+key.name+"("+key.email+")</span></label></li>";
                    }
                    else{
                        text+="<input type='checkbox' id='checkbox-"+key.id+"' checked='checked' onclick='removeCheck("+key.id+","+JSON.stringify(key.name)+")'><span class='black-text'>"+key.name+"("+key.email+")</span></label></li>";
                    }
                    //text+=;
                    var lList = $(text);
                    //heading.attr('href','/project/'+key.id);
                    uList.append(lList);
                });

                //document.getElementById("results").style.display = "block";
            }
        }  
    }

    function removeCheck(id,name){
        $("#chip-"+id).remove();
        checkedMembers.splice( $.inArray(id,checkedMembers) ,1 );
        if($("#checkbox-"+id).length){
            $("#checkbox-"+id).prop('checked', false);
            $("#checkbox-"+id).attr("onclick","checkedMember("+id+","+JSON.stringify(name)+")");
        }
        console.log("unchecking "+checkedMembers);
    }

    function checkedMember(id,name){
        $("#checkbox-"+id).prop('checked', true);
        var text = "<div class='chip' id='chip-"+id+"'>"+name+"<i class='close material-icons' onclick='removeCheck("+id+","+JSON.stringify(name)+")'>close</i></div>";
        $('#membersView').append(text);
        checkedMembers.push(id);
        $("#checkbox-"+id).attr("onclick","removeCheck("+id+","+JSON.stringify(name)+")");
        console.log(id + "is clicked");
        console.log("checking "+checkedMembers);
    }
