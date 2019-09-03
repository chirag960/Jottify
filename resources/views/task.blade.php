@extends('layouts.app')

@section('styles')
.task-bar{
    height:50px;
    width:100%;
    background-color: rgba(255,255,255,0.5);
    position:fixed;
    top:60px;
}
.backLink:hover{
    cursor:pointer;
    text-decoration: underline;
}

.go-inline{
    float:left;
}

.status-div{
    width:400px;
    float:left;
    margin:10px;
}
.task-detail-container{
    margin-top:70px;
    padding-left:2%;
    padding-right:2%;
}
.btn-primary-outline{
    float:right;
}
.btn-primary-outline:hover{
    background:#afafaf;
}
#members-list{
    position:relative;
    z-index:2;
}
.task-card:hover{
    background:#afafaf;
    cursor:pointer;
}
.pointer{
    cursor:pointer;
}
.addTask:hover{
    cursor:pointer;
    text-decoration: underline;
}
.left-side{
    width:70%;
    float:left;
}
.right-side{
    width:30%;
    float:left;
}
.striked{
    text-decoration: line-through;
}
.error{
    color:red;
}
.comment-option{
    float:right;
    margin-left:10px;
    margin-right:10px;
    cursor:pointer;
}
.comment-option:hover{
    text-decoration: underline;
}
@endsection

@section('full-content')
<div class="full-project-view">
    <nav class="task-bar navbar-expand-md shadow-sm">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto text-white nav-menu">
                <li class="nav-item go-inline text-body">
                    <div class="backLink" onclick="backToProject();"><h6><!-- Add back icon -->< Go back to Project</h3></div>
                    <div><h3>{{ $task->title }}  <!-- Make this remanable --></h3></div>
                </li>

                <li class="nav-item go-inline">
                    <div class="dropdown">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                            Members
                        </button>
                        <div class="dropdown-menu" id="members-list">
                        <!-- Ajax data coming here -->
                        </div>
                    </div> 
                    <span id="#member-names go-inline"></span>
                </li>

                <li class="nav-item">
                    <button type="button" class="btn btn-primary">Invite</button>
                </li>

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto text-white">
                <li class="nav-item">
                @if (isset($task->due_date))
                    <span class="text-body">Due Date : <span id="date_text">{{$task->due_date}}</span></span>
                    <span><button type="button" class="btn btn-primary" data-toggle='modal' data-target='#dueDateModal'>Change Due Date</button></span>
                @else
                    <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#dueDateModal'>Add Due Date</button>
                @endif
                </li>
            </ul>
        </div>
    </nav>
    <div class="task-detail-container">
        <div class="left-side">
        <div class="description-container" id="description">
            <h3>Description</h3>
            @if (isset($task->description))
                <p id="taskDescription">{{$task->description}}</p>
                <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#addDescriptionModal'>Change Description</button>
            @else
                <p>Add a Description to make help understand the task better.</p>
                <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#addDescriptionModal'>Add Description</button>
            @endif
        </div>
        <hr/>
        <div class="checklist-container" id="checklist">
            <h3>CheckList</h3>
            @if($task->checklist_item_count != null)
            <div class="progress">
                <div id="progressBarDiv" class="progress-bar bg-success progress-bar-striped" role="progressbar"
                style="width: {{ ceil(($task->checklist_done/$task->checklist_item_count)*100).'%'}}" >
                </div>
            </div>
            @endif
            <div id="checklist-items"></div>
            <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#addItemModal'>Add Items</button>
        </div>
        <hr/>
        <div class="attachments-header" id="attachments">
            <h3 data-toggle="collapse" data-target="#attachment-list" class="pointer">Attachments</h3>
            
            <button id="attachment-dropdown" type="button" class="btn btn-primary" data-toggle='modal' data-target='#addAttachmentModal'>Add Attachment</button>
            <div id="attachment-list" class="collapse">
                adgfhsfggdafgshadgadfhdgadfhasdhsf
            </div>
        </div>
        <hr/>
        <div class="comments-header" id="comments">
            <h3 data-toggle="collapse" data-target="#comment-list" class="pointer">Comments</h3>
            
            <form method="POST">
                <textarea class="form-control" id="comment-message" placeholder="Add a comment"></textarea>
                <button type="button" onclick='sendComment()' class="btn btn-primary">Send</button>
            </form>
            <div id="comment-list" class="collapse">
            </div>
        </div>
        <hr/>
    </div>
    <div class="right-side">
        <p>Move to another status</p>
        <form method="POST">
            @csrf
            @method('patch')
            <div class="form-group">
                <select name="status" id="statusList">
                </select>
            </div>
            <button type="button" class="btn btn-primary" onclick="validateStatus();">Submit</button>
        </form>
    </div>
    </div>

</div>

@include('dueDateModal')

@include('addDescription')

@include('addAttachment')

@include('addItem')

<script>

        console.log( {!! $task !!});
    
        $(document).ready(function(){
            $('.datepicker').datepicker();
        });

        var checklist_item_count = {!! ($task->checklist_item_count==null)?"null":$task->checklist_item_count !!} ;
        var checklist_done = {!! ($task->checklist_done==null)?"null":$task->checklist_done !!} ;

        function displayMembers(xhttp) {
            var membersText = JSON.parse(xhttp.responseText);
            console.log(xhttp.responseText);
            var newDiv="";
            membersText.forEach(function addToDiv(key,index){
                newDiv += "<div class='dropdown-item' >";
                newDiv += key.name;
                newDiv += "</div>";
                console.log("Done adding members");
             });
             
             document.getElementById("members-list").innerHTML = newDiv;
        }

        function updateStatus(xhttp){
            console.log(JSON.parse(xhttp.responseText));
            // {{ $task->status_id }} = JSON.parse(xhttp.responseText);
        }
        
        function validateStatus(){
            var value = document.getElementById("statusList").value;
            var id_json = JSON.stringify({"id":value});
            loadDoc("PATCH","/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/status",id_json,updateStatus);
        }

        function displayChecklist(xhttp) {
            var listText = JSON.parse(xhttp.responseText);
            console.log(xhttp.responseText);
            var newDiv = "";
            listText.forEach(function addToDiv(key,index){
                 newDiv += "<div class='form-check'>";
                 if(key.completed == "0"){
                        newDiv += "<input type='checkbox' onclick='handleCheck(this,"+ key.id +")' class='form-check-input' id='checklist-"+key.id+"' name='checklist-"+key.id+"'><span>";
                    }
                    else{
                        newDiv += "<input type='checkbox' onclick='handleCheck(this,"+ key.id +")' class='form-check-input' id='checklist-"+key.id+"' name='checklist-"+key.id+"' checked><span>";
                    }
                 newDiv += key.item; 
                 newDiv += "</span></div>";
             });
             document.getElementById("checklist-items").innerHTML = newDiv;
             
        }

        function setAtt(element,att,val){
            var attribute = document.createAttribute(att);
            attribute.value = val;
            element.setAttributeNode(attribute); 
        }

        function displayaddedItem(xhttp){
            
            var itemText = JSON.parse(xhttp.responseText);
            console.log(itemText);

            var ele = document.createElement("DIV");
            ele.classList.add("form-check");
            
            var inputEle = document.createElement("INPUT");
            inputEle.classList.add("form-check-input");
            setAtt(inputEle,"type","checkbox");
            setAtt(inputEle,"id","checklist-"+itemText.id);
            setAtt(inputEle,"name","checklist-"+itemText.id);
            inputEle.onclick = function() {handleCheck(inputEle, itemText.id)};
            
            ele.appendChild(inputEle);
            
            var spanEle = document.createElement("SPAN");
            var textEle = document.createTextNode(itemText.item);
            
            spanEle.appendChild(textEle);
            
            ele.appendChild(spanEle);

            document.getElementById("checklist-items").appendChild(ele);

            checklist_item_count++;

            var progress = Math.ceil((checklist_done/checklist_item_count)*100);
            document.getElementById("progressBarDiv").style.width = progress+"%";
        }

        function displayAttachments(xhttp) {
            var statusText = JSON.parse(xhttp.responseText);
            console.log(xhttp.responseText);
            var newDiv = "";
            statusText.forEach(function addToDiv(key,index){
                newDiv += "<div class='card status-div'><div class='card-header text-center'>";
                newDiv += key.title + "<button type='button' class='btn btn-primary-outline dropdown-toggle' data-toggle='dropdown'></button><ul class='dropdown-menu'><li><p href='#'>Add another status right to it</p></li><li><p href='#'>Delete status</p></li><li><p href='#'>Add task</p></li></ul></div>";
                newDiv += "<div class='card-body' id='status-"+key.id+"'><p class='text-center addTask' data-toggle='modal' data-target='#newTaskForm'  onclick='sendID("+key.id+")'>+ Add task</p></div>";
                newDiv += "</div></div>";
             });
             document.getElementById("status-list").innerHTML = newDiv;
        }

        function displayComments(xhttp) {
            var listText = JSON.parse(xhttp.responseText);
            console.log(xhttp.responseText);
            var newDiv = "";
            listText.forEach(function addToDiv(key,index){
                 newDiv += "<div class='comment-div'>";
                 newDiv += "<span class='commenter-name' id='comment-"+key.id+"'>";
                 var id = {!! Auth::user()->id !!} ;
                 if(id == key.user_id){
                    newDiv += "You"+" : " + key.message + key.timestamp;
                 }
                 else{
                    newDiv += key.name+" : " + key.message + key.timestamp;
                 }
                // if(key.edited == "1"){
                //     newDiv += "<i>(edited)</i>"
                //  }
                 
                //  if(id == key.user_id){
                //  newDiv += "<a class='comment-option'>delete</a><a class='comment-option'>edit</a>";
                // }

                 newDiv += "</span></div>";
             });
             document.getElementById("comment-list").innerHTML = newDiv;
        }

        
        function updateDescription(xhttp){
            console.log("This is request object " + xhttp.responseText);
            document.getElementById("taskDescription").innerHTML = xhttp.responseText;
        }

        function sendComment(){
            var message = document.getElementById("comment-message").value;
            var message_json = JSON.stringify({"message":message});
            loadDoc("POST","/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/comment",message_json,updateComment);
            document.getElementById("comment-message").value = null;
        }

        function displayStatus(xhttp){
            var statusList = JSON.parse(xhttp.responseText);
            statusList.forEach(function addToDiv(key,index){
                var optionEle = document.createElement("OPTION");
                optionEle.value = key.id;
                var text = document.createTextNode(key.title);
                optionEle.appendChild(text);
                if( {!! $task->status_id !!} == key.id ){
                    optionEle.selected = "selected";
                }
                document.getElementById("statusList").appendChild(optionEle);
            });
        }

        function displayDate(xhttp){
            console.log(xhttp.responseText.toString());
            document.getElementById("date_text").innerHTML = xhttp.responseText.toString();
        }

        function updateAttachment(xhttp){
            console.log(JSON.parse(xhttp.responseText));
        }

        function updateComment(xhttp){
            //console.log(JSON.parse(xhttp.responseText));
            var response = JSON.parse(xhttp.responseText);
            console.log(response['message']);
        }

        loadDoc("GET","/project/"+ {!! $task->project_id !!} + "/statuses",null,displayStatus);

        loadDoc("GET","/project/"+ {!! $task->project_id !!} + "/task/" + {!! $task->id !!} +"/members",null,displayMembers);

        loadDoc("GET","/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/checklist",null,displayChecklist);

        //loadDoc("GET","/project/" + {!! $task->project_id !!} + "/task/" + {!! $task->id !!} + "/attachments",displayAttachments);

        loadDoc("GET","/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/comments",null,displayComments);
            
        function sendID(id){
            var inputTag = document.getElementById("input_status_id");
            //console.log(id);
            inputTag.value = id;
        }

        function backToProject(){
           window.location.href = "/project/" + {!! $task->project_id !!} ;
        }

        function patchChecklist(xhttp){
            console.log(JSON.parse(xhttp.responseText));
            var checklist_response = JSON.parse(xhttp.responseText);
            checklist_response.forEach(function addToDiv(key,index){
                var check;
                if(key.completed == 1){
                    console.log("it is true");
                    check = true;
                }
                else if(key.completed == 0){
                    console.log("it is false");
                    check = false;
                }
                document.getElementById("checklist-" + key.id).checked = check;
                var progress = Math.ceil((key.checklist_done/key.checklist_item_count)*100);
                document.getElementById("progressBarDiv").style.width = progress+"%";
             });
             //document.getElementById("status-list").innerHTML = newDiv;

        }

        function handleCheck(element,id){
            var checked = element.checked;
            if(checked === true){
                var completed = "1";
                checklist_done++;
                var data = JSON.stringify({"completed":completed,"checklist_done":checklist_done,"id":{{ $task->id }},"project_id":{{ $task->project_id}}});
                //console.log("this is json : " + data);
                loadDoc("PATCH","/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/checklist/"+ id, data, patchChecklist)
            }
            else if(checked === false){
                var completed = "0";
                checklist_done--;
                //var checklist_progress = checklist_progress - Math.ceil(100/ {!! $task->checklist_item_count !!});
                var data = JSON.stringify({"completed":completed,"checklist_done":checklist_done,"id":{{ $task->id }},"project_id":{{ $task->project_id}}});
                loadDoc("PATCH","/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/checklist/"+ id, data, patchChecklist)
            }
            console.log(checked);

        }

        
    </script>

@endsection