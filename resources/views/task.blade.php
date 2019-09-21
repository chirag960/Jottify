@extends('layouts.app')

@section('style-link')
<link href="{{ asset('css/task.css') }}" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('full-content')
    <div class="container card-panel col s12 m10 offset-m2 l10 offset-l2 xl10 offset-xl2">
        <div class="col s12 m12 l8 xl8"> 
                <div>
                    <h3>{{ $task->title }}  <!-- Make this remanable --></h3>
                </div>
                <div>
                    <p>Members</p>
                    <div id="member-avatars">

                    </div>
                </div>
                <div class="s12 m6 l6 xl6">
                <a class="btn green datepicker"><i class="material-icons">date_range</i></a>
                @if (isset($task->due_date))
                    <span id="date_text">{{$task->due_date}}</span>
                @else
                    <span id="date_text">Add a Due Date</span>
                @endif
                <span class="invalid-feedback" id="invalidDate" role="alert"></span>
                </div>
            <div class="s12 m12 l12 xl12 description-container" id="description">
                    <h6>Description</h6>
                <input type="hidden" name="description">
                <div id="quill-container"></div>
                <span class="invalid-feedback" id="invalidDescription" role="alert"></span>
                <button type="button" class="btn light-blue" id="description-button" onclick="changeDescription()">Update Description</button>
            </div>
            <hr/>
            <div class="checklist-container" id="checklist">
                <h6>CheckList</h6>
                @if($task->checklist_item_count > 0)
                {{-- {{ dd($task->checklist_item_count)}} --}}
                <div class="progress s12 m6 l6 xl6" id="progressBar">
                    <div id="progressBarDiv" class="determinate" role="progressbar"
                    style="width: {{ ceil(($task->checklist_done/$task->checklist_item_count)*100).'%'}}" >
                    </div>
                </div>
                @endif
                <div id="checklist-items"></div>
                <button type="button" class="btn light-blue" onclick="addItem()">Add Items</button>
            </div>
            <hr/>
            <div class="attachments-header" id="attachments">
                <h6>Attachments</h6>
                <button type="button" class="btn light-blue" onclick="openAttachmentModal()">Add Attachment</button>
                <div id="attachment-list" class="row attachments"></div>
            </div>
            <hr/>

            <!--div class="right-side">
                <p>Move to another status</p>
                <form method="POST">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <select name="status" id="statusList">
                        </select>
                    </div>
                    <button type="button" class="btn light-blue" onclick="validateStatus();">Submit</button>
                </form>
            </div-->
        </div>
    <div class="col s12 m12 l4 xl4">
        @if($task->role == 1)
        <div id="task-buttons">
                <button class="btn light-blue" onclick="deleteTask()"><i class="material-icons">delete</i>Delete Task</button>
                <br/>
                <br/>
                <button class="btn light-blue modal-trigger" href="#assignMemberTaskModal"><i class="material-icons">people</i>assign members</button>
                <div id="status-form"></div>
                
        </div>
        @endif
        <div class="comments-header" id="comments">
            <h6>Comments</h6>
            <div id="comment-list"></div>
            <form method="POST">
                <textarea class="materialize-textarea" id="comment-message" placeholder="Add a comment" required></textarea>
                <span class="invalid-feedback" id="invalidComment" role="alert"></span>
                <button type="button" onclick='validateComment()' class="btn light-blue">Send</button>
            </form> 
        </div>
    </div>
</div>

@include('inviteMember')

@include('attachmentModal')

@endsection

@section('links')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>

        console.log( {!! $task !!});
        function callFunction(){
            var date = $('.datepicker').val();
            var valid = validateDate();
            $('.datepicker').datepicker('close');
            if(valid == true){
                console.log("sending the request");
                var message = JSON.stringify({"date":date});
                makePostRequest("/project/" + project_id + "/task/" +task_id+ "/duedate",message,displayDueDate);
            }
        }

        var task_id = "{!! $task->id !!}";
        @if(isset($task->description))
        var description = {!! $task->description !!};
        @else
        var description = "";
        @endif

        var project_id = "{!! $task->project_id !!}";
        var status_id = "{!! $task->status_id !!}";
        var checklist_item_count = {!! ($task->checklist_item_count==null)?0:$task->checklist_item_count !!} ;
        var checklist_done = {!! ($task->checklist_done==null)?0:$task->checklist_done !!} ;
        var attachment_count = "{!! $task->attachment_count !!}";
        var role = "{!! $task->role !!}";
        
        $(document).ready(function(){
            $('.dropdown-trigger').dropdown();
            $('#addAttachmentModal').modal();
            $('#assignMemberTaskModal').modal();
            document.getElementById("searchBar").addEventListener("keyup", throttleSearchTask(showTasks, 500));

            var d = new Date;
            var month = d.getMonth();
            var day = d.getDate();
            var year = d.getFullYear();
            var c = new Date(year + 2, month, day)
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
                $('.datepicker').datepicker({
                    defaultDate : new Date(),
                    minDate: new Date(),
                    maxDate: c,
                    onClose:callFunction
                });

            
        });

        function backToProject(xhttp){
            var response = JSON.parse(xhttp.response);
            if(response.message == "success"){
                window.location.href = "/project/" + project_id ;     
            }
            else{
                M.toast({html:response.message, classes:'rounded'});
            }
           
        }

        function deleteTask(){
            makeDeleteRequest("/project/"+ project_id + "/task/"+ task_id,backToProject);
        }

        function displayDueDate(xhttp){
            console.log(xhttp.responseText);
            var response = JSON.parse(xhttp.responseText);
            if (response.message == "success"){
                $('#date_text').html(response.date);
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
            else if(response.message == "date-error"){
                var ele = document.getElementById("invalidDate");
                ele.innerHTML += "<p><strong>"+response.errors+"</strong></p>";
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
            console.log("inside valid");
            //console.log(date > new Date());
            var ele = document.getElementById("invalidDate");
            var date_value = $(".datepicker").val();
            var date = Date.parse(date_value);
            console.log(date_value);
            var d = new Date;
            console.log(d);
            var month = d.getMonth();
            var day = d.getDate();
            var year = d.getFullYear();
            var c = new Date(year + 1, month, day)
            if(date == "" || date == null ){
                console.log("inside null");
                ele.innerHTML = "<strong>Select a valid date</strong>";
                ele.style.display = "block";
                return false;
            }
            else if(date < new Date() || date > c){
                console.log("inside range");
                ele.innerHTML = "<strong>date range not valid</strong>";
                ele.style.display = "block";
                return false;
            }
            else{
                ele.style.display = "none";
                return true;
            }
        }

        function displayUpdatedDescription(xhttp){
            //console.log(xhttp.responseText);
            var response = JSON.parse(xhttp.responseText);
            if(response.message == "success"){
                var description = xhttp.responseText.description;
                console.log(response);
                var quill;
                $('.ql-toolbar').remove();
                quill = new Quill('#quill-container', {
                modules: {
                    "toolbar": false
                },
                theme: 'snow'  // or 'bubble'
                });
                //quill.setContents(JSON.parse(response.description));
                quill.enable(false);
                $('#description-button').html("Update Description");
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
            var description = JSON.stringify(quill.getContents());
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
            if(element.html() == 'Update Description'){
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
                var valid = validateDescription();
                if(valid == true){
                    var description = JSON.stringify(quill.getContents());
                    //console.log(description);
                    var message = JSON.stringify({"description": description});
                    console.log(message);
                    //message = 
                    makePatchRequest("/project/"+ project_id + "/task/" + task_id + "/description",message,displayUpdatedDescription);            
                }
            }
        }

        makeGetRequest("/project/"+ project_id + "/statuses",displayStatus);

        makeGetRequest("/project/"+ project_id + "/task/" + task_id  +"/members",displayMembers);

        makeGetRequest("/project/" + project_id + "/task/" + task_id + "/checklist",displayChecklist);

        makeGetRequest("/project/" + project_id + "/task/" + task_id + "/attachments",displayAttachments);

        makeGetRequest("/project/" + project_id + "/task/" + task_id + "/comments",displayComments);

        function assignMembers(){
            
        }


        function openAttachmentModal(){
            if(attachment_count == 10){
                M.toast({html:"No more than 10 attachments allowed", classes:'rounded'});
            }
            else{
                $('#addAttachmentModal').modal('open');
            }
        }


        function validateItem(){
            
            var title = document.getElementById("newItem").value;
            var ele = document.getElementById("invalidNewItem");
            if(title.length >= 30 || title.length < 3){
                ele.innerHTML = "<strong>The item should not be more than 30 letters or less than 3.</strong>";
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
                newDiv += "</span></label></div>";
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
        $('#newItem').val(" ");
        $('#newItem').html(" ");
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
            var text = '<div class="progress" id="progressBar">';
            text+='<div id="progressBarDiv" class="determinate" role="progressbar">';
            //style="width:  Math.ceil((key.checklist_done/key.checklist_item_count)*100);
            text+='</div></div>';
            var progress_div  = $.parseHTML(text);
            $(progress_div).insertBefore("#checklist-items");
            checklist_item_count++;
            if(itemText.completed == true){
                checklist_done++;
                inputEle.checked = true;
            }
            var progress = Math.ceil((checklist_done/checklist_item_count)*100);
            document.getElementById("progressBarDiv").style.width = progress+"%";
        }
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
    
    var checklist_response = JSON.parse(xhttp.responseText);
    checklist_response.forEach(function addToDiv(key,index){
        var check;
        if(key.completed == 1){
            console.log("it is true");
            check = true;
        }
        else if(key.completed == 0){
            
            check = false;
        }
        document.getElementById("checklist-" + key.id).checked = check;
        var progress = Math.ceil((key.checklist_done/key.checklist_item_count)*100);
        document.getElementById("progressBarDiv").style.width = progress+"%";
     });

}

function handleCheck(element,id){
    var checked = element.checked;
    if(checked === true){
        var completed = "1";
        checklist_done++;
        var data = JSON.stringify({"completed":completed,"checklist_done":checklist_done,"id":task_id ,"project_id":project_id});
        makePatchRequest("/project/" + project_id + "/task/" + task_id + "/checklist/"+ id, data, patchChecklist)
    }
    else if(checked === false){
        var completed = "0";
        checklist_done--;
        var checklist_progress = checklist_progress - Math.ceil(100/ checklist_item_count);
        var data = JSON.stringify({"completed":completed,"checklist_done":checklist_done,"id":task_id ,"project_id":project_id});
        makePatchRequest("/project/" + project_id + "/task/" + task_id + "/checklist/"+ id, data, patchChecklist)
    }
    
}
        function displayAssignMembers(xhttp){
            var response = JSON.parse(xhttp.responseText);
            if(response.message == "success"){
                $("#member-pattern").val("");
                $("#assignMemberTaskModal").modal('close');
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
            console.log(members);
            var message = JSON.stringify({ids:members});
            console.log(task_id);
            makePostRequest("/project/" + project_id + "/task/" + task_id + "/members", message, displayAssignMembers);
        }

        function searchMembers(){
            var pattern = $("#member_pattern").val().toUpperCase();
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
            membersText.forEach(function (key,index){
                if(key.present == true){
                    newDiv +="<img class='avatar-image circle' src='"+key.photo_location+"' title='"+key.name+ " ("+key.email+")'>";
                }
                
             });
             document.getElementById("member-avatars").innerHTML = newDiv;

        }


        function displayMembers(xhttp) {
            var membersText = JSON.parse(xhttp.responseText);
            console.log(xhttp.responseText);
            var newDiv="";
            membersText.forEach(function (key,index){
                newDiv += "<p><label>";
                console.log(key.present);
                if(key.present == true){
                    if(key.role == 1){
                        newDiv += "<input type='checkbox' checked='checked' disabled='disabled' value="+key.id+">";    
                    }
                    else{
                        newDiv += "<input type='checkbox' checked='checked' value="+key.id+">";
                    }
                }
                else{
                    newDiv += "<input type='checkbox' value="+key.id+">";
                }
                newDiv += "<span>"+key.name+"</span>";
                newDiv += "</label></p>";
                
             });
             
             document.getElementById("members-list").innerHTML = newDiv;
             displayMemberIcons(xhttp);
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
            console.log(xhttp.responseText);
            var newDiv = "";
            attachmentText.forEach(function addToDiv(key,index){
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
                 newDiv += "<div class='comment-div'>";
                 newDiv += "<p class='commenter-name' id='comment-"+key.id+"'>";
                 var id = {!! Auth::user()->id !!} ;
                 if(id == key.user_id){
                    newDiv += "You"+" : " + key.message;
                 }
                 else{
                    newDiv += key.name+" : " + key.message;
                 }
                 newDiv += "</p>";
                 //newDiv += "<span class='comment-time'>"+  +"</span>";
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
                    newDiv +="</select><label>Select Status</label></div>";
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
            var response  = JSON.parse(xhttp.responseText);
            if(response.message == "success"){
                $('#fileInput').value = null;
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
                console.log("length = " + response.attachments.length);
                attachment_count = parseInt(attachment_count) + parseInt(response.attachments.length);
                console.log(attachment_count);
            }
            else if(response.message == "errors"){
                var attachmentError = response.errors.files;
                var ele = document.getElementById("invalidAttachment");
                attachmentError.forEach(function(key,index){
                    ele.innerHTML += "<p><strong>"+attachmentError[index]+"</strong></p>";
                });
                ele.style.display = "block";
            }
            else if(response.message == "count-error"){
                var ele = document.getElementById("invalidAttachment");
                ele.innerHTML += "<p><strong>"+attachmentError[index]+"</strong></p>";
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
                 newDiv = "<div class='comment-div'>";
                 newDiv += "<p class='commenter-name' id='comment-"+key.id+"'>";
                 newDiv += "You"+" : " + key.message;
                 newDiv += "</p>";
                 //newDiv += "<span class='comment-time'>"+  +"</span>";
                 newDiv+= "</div>";
                 var commentDiv = $.parseHTML(newDiv);
                 if($("#comment-pre-text").length){
                    $("#comment-pre-text").remove();
                 }
                 $("#comment-list").prepend(commentDiv);
                 $("#comment-list").scrollTop = $("#comment-list")[0].scrollHeight;
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
            console.log(attachment_count);
            console.log(fileLength);
            if(filePath == ""){
                ele.innerHTML = "<strong>Please enter a attachment</strong>";
                ele.style.display = "block";
                return false;
            }
            else if(parseInt(fileLength)+parseInt(attachment_count) > 10){
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
            var valid = validateImages();
            console.log(valid);
            if(valid == true){
                Array.prototype.forEach.call(fileList, file => {
                formData.append("files[]",file);
                });
                makePostRequestForImages("/project/" + project_id + "/task/" + task_id + "/attachment",formData,updateAttachment);
            }
            
        }
  
    </script>

<script type="text/javascript" src="{{ asset('js/task/checklist.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/task/validation.js') }}"></script>

@endsection