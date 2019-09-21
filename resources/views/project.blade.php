@extends('layouts.app')

@section('style-link')
<link href="{{ asset('css/project.css') }}" rel="stylesheet">

@endsection

@section('full-content')

<div class="fixed-action-btn">
    <a class="btn-floating btn-large red">
        <i class="large material-icons">mode_edit</i>
    </a>
    <ul>
        <li><a class="btn-floating red tooltipped" data-position="left" data-tooltip="project settings"><i href="#" data-target="slide-out" class="material-icons sidenav-trigger show-on-large right">settings</i></a></li>
        @if($project['role']==1)
        <li><a class="btn-floating yellow darken-1 tooltipped" data-position="left" data-tooltip="invite members"><i class="material-icons modal-trigger" href="#addInviteProjectModal" >person_add</i></a></li>
        @endif
        <li><a class="btn-floating green tooltipped" data-position="left" data-tooltip="add task" ><i class="material-icons" onclick="openTaskModal()">playlist_add</i></a></li>
        <li><a class="btn-floating blue tooltipped" data-position="left" data-tooltip="add status"><i class="material-icons modal-trigger" href="#addStatusModal">label_outline</i></a></li>
    </ul>
</div>
<div class="project-bar row">
    <!-- Left Side Of Navbar -->
    <div class="col s12 m6 l6 xl6">
            <span class="project-title">{{ $project['title'] }}</span>  <!-- Make this remanable -->
            <!--
            @if ($project['star'] == 1)
            <i class="material-icons yellow-text" onclick="toggleStar(this)">star</i>
            @else
            <i class="material-icons" onclick="toggleStar(this)">star_border</i>
            @endif
            -->
    </div>
    <!-- Right Side Of Navbar -->
    <div class="col s12 m6 l6 xl6">
        <!--i class="material-icons right">file_download</i-->
        <!--a class="waves-effect waves-light btn right" onclick="filterTask()">Filter</a-->
        <!--button type="buttton" class="btn btn-primary" onclick="resetTask()">Reset Tasks</button-->  
    </div>
</div>
<div class="status-card-container" id="status-list">

</div>


<ul id="slide-out" class="sidenav">
    <li>
        <div class="user-view">
            <div class="background">
                <img src="{{$project['background']}}">
            </div>
            <a class="center-align"><span class="white-text project-title">{{$project['title']}}</span></a>
        </div>
                <!--input value="{{$project['title']}}" type="Text" class="form-control" name="title" onblur="changeTitle(this)" placeholder="Title" required-->
    </li>
    <li><a><i class="material-icons">access_time</i>Created on {{ $project['created_at'] }}</a></li>
    @isset($project['description'])
    <li><a>{{$project['description']}}</a></li>
    @endisset
    <li><div class="divider"></div></li>
    <li id="members-heading">All Members</li>
    
        @if(count($project['members']) != 0)
            <ul class="collection" id="members-list">
            @foreach ($project['members'] as $member)
            <li class="collection-item avatar min-height-auto" id="member-{{$member->id}}" onmouseover="showOps(this)" onmouseout="hideOps(this)">
                    @if($member->id == auth()->user()->id)
        
                            <img src="{{$member->photo_location}}" alt="" class="circle">
                        
                        <span class="title">You</span>
                        <p>{{$member->email}}</p>
                    @else
                        
                        <img src="{{$member->photo_location}}" alt="" class="circle">
                        
                        <span class="title">{{$member->name}}</span>
                        <p>{{$member->email}}</p>
                        @if($project['role']==1)
                            <div class="overlay-options">
                        @if($member->role == 0)
                            <i title="make admin" class="material-icons member-options" id="admin-{{$member->id}}" onclick="addAdmin({{ $member->id}},this)">group_add</i>
                        @else
                            <i title="remove as admin" class="material-icons member-options" id="admin-{{$member->id}}" onclick="removeAdmin({{ $member->id}},this)">remove_circle</i>
                        @endif
                        <i title="remove from project" class="material-icons member-options" id="delete-{{$member->id}}" onclick="deleteMember({{ $member->id}},this)">delete</i>
                            </div>
                        @endif
                    @endif
                </li>    
            @endforeach
            </ul>
        @else 
            <li><a>Invite members to the project</a></li>
        @endif
  </ul>

@include('inviteProjectModal')

@include('statusModal')

@include('taskModal')

@endsection

@section('links')
<script type="text/javascript" src="{{ asset('js/project/sidenav.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/project/validation.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/project/inviteProject.js') }}"></script>

<script>

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById("searchBar").addEventListener("keyup", throttleSearchTask(showTasks, 500));
            var elems = document.querySelectorAll('.fixed-action-btn');
            var instances = M.FloatingActionButton.init(elems,{});
            $('.modal').modal();
            if($('#addInviteProjectModal').length){
                $('#addInviteProjectModal').modal();
            }
            $('.tooltipped').tooltip({'outDuration':0});
            var elems = document.querySelectorAll('.sidenav');
            var instances = M.Sidenav.init(elems, {edge: 'right',draggable: true,});
            $('.dropdown-trigger').dropdown();
            var elems = document.querySelectorAll('.chips-placeholder');
            var instances = M.Chips.init(elems,{});
            var elems = document.querySelectorAll('select');
            var instances = M.FormSelect.init(elems, {});
            // $('.chips-placeholder').chips({
            //     placeholder: 'Enter a tag',
            //     secondaryPlaceholder: '+Tag',
            // });
    
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
                    openTaskModal();
                }
            }
            if(e.ctrlKey && e.keyCode == 83){
                e.preventDefault();

                if($('#addStatusModal').hasClass('open')){
                    $('#addStatusModal').modal('close');
                }
                else{
                    $('#addStatusModal').modal('open');
                }
            }

            
        });

            var statusList = new Array();
            var project_id = "{!! $project['id'] !!}";
            var projectsText;
            var membersText;
            var tasksText;
            var hiddenTasks = [];
    
            makeGetRequest("/project/{!! $project['id'] !!}/statuses",displayStatus);
            
            /*
            function checkStar(xhttp){
                console.log(xhttp.responseText);
            }
    
            function toggleStar(element){
                if(element.innerHTML == "star_border"){
                    element.innerHTML = "star";
                    element.classList.add("yellow-text");
                    var message = JSON.stringify({"star":"1"});
                    loadDoc("PATCH","/project/{!!$project['id']!!}/star",message,checkStar);
                }
                else{
                    element.innerHTML = "star_border";
                    element.classList.remove("yellow-text");
                    var message = JSON.stringify({"star":"0"});
                    loadDoc("PATCH","/project/{{ $project['id']}}/star",message,checkStar);
                }
                console.log(message);
            }
            */
    
            function displayInviteMembers(xhttp){
                var response = JSON.parse(xhttp.responseText);
                if(response.message == "success"){
                    $('#addInviteProjectModal').modal('close');
                    M.toast({html: "Invitation mail sent to all members", classes: 'rounded'});
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
                    document.getElementById("status-title").value = null;
                    $('#addStatusModal').modal('close');
    
                    var newDiv = "";
                    newDiv += "<div class='card-panel status-name grey lighten-4' id='status-panel-"+response.id+"'><div class='card-header'>";
                    newDiv += response.title;
                    // + "<i class='material-icons dropdownTrigger right' data-toggle='dropdown-status-"+response.id+"'>arrow_drop_down</i><ul id='dropdown-status-"+response.id+"' class='dropdown-content'><li><p href='#'>Add another status right to it</p></li><li><p href='#'>Delete status</p></li><li><p href='#'>Add task</p></li></ul>
                    newDiv+= "</div>";
                    newDiv += "<div class='card-content' id='status-"+response.id+"'><p class='text-center addTask' onclick='openTaskModal("+response.id+")'>+ Add task</p></div>";
                    newDiv += "</div>";
                    var newStatus = $.parseHTML(newDiv);
                    if(response.beforeStatusId == -1){
                        $("#status-list").prepend(newStatus);
                    }
                    else{
                        $(newStatus).insertAfter($("#status-panel-"+response.beforeStatusId));
                    }
                    
                    statusList.splice(response.order,0,response.id+"-"+response.title);
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
                    var taskDiv = "";
                    var id = "status-"+response.status_id;
                    
                    var status_div = document.getElementById(id);
                    var len = status.length;
                    var taskDiv = document.createElement("DIV");
                    taskDiv.id = "task-"+response.id;
                    taskDiv.classList.add("card","task-card");
                    var card_body = document.createElement("DIV");
                    taskDiv.classList.add("card-body");
                    var title_para = document.createElement("P");
                    var textnode = document.createTextNode(response.title);
                    title_para.appendChild(textnode);
                    card_body.appendChild(title_para);
                    taskDiv.appendChild(card_body);
                    taskDiv.addEventListener("click", function(){
                        window.location.href = "/project/"+project_id+"/task/"+response.id;
                    }); 
                    status_div.append(taskDiv);
                    //hiddenTasks.push(taskDiv);
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
                    // if(descError){
                    //     var ele = document.getElementById("invalidTaskDesc");
                    //     descError.forEach(function(key,index){
                         
                    //         ele.innerHTML += "<p><strong>"+descError[index]+"</strong></p>";
                    //     });
                    //     ele.style.display = "block";
                    // }
                }
                else{
                    M.toast({html: response.message, classes: 'rounded'});
                }
            }
    
            function displayStatus(xhttp) {
                var statusText = JSON.parse(xhttp.responseText);
               
                var newDiv = "";
                statusText.forEach(function addToDiv(key,index){
                    newDiv += "<div class='card-panel status-name grey lighten-4' id='status-panel-"+key.id+"'><div class='card-header'>";
                    newDiv += key.title
                    //newDiv += + "<i class='material-icons dropdownTrigger right' data-toggle='dropdown-status-"+key.id+"'>arrow_drop_down</i><ul id='dropdown-status-"+key.id+"' class='dropdown-content'><li><p href='#'>Add another status right to it</p></li><li><p href='#'>Delete status</p></li><li><p href='#'>Add task</p></li></ul>
                    newDiv += "</div>";
                    newDiv += "<div class='card-content' id='status-"+key.id+"'><p class='text-center addTask' onclick='openTaskModal("+key.id+")'>+ Add task</p></div>";
                    newDiv += "</div>";
                    statusList.push(key.id+"-"+key.title);
                 });
                 document.getElementById("status-list").innerHTML = newDiv;
                 addStatusToSelectMenu();
                 addStatusToTaskMenu();
                 makeGetRequest("/project/{{ $project['id'] }}/tasks",displayTasks);
            }
    
            function addStatusToSelectMenu(){
                var ele = document.getElementById("status_order");
                if(statusList.length > 0){
                    var text;
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
                    var text = '<input type="hidden" name="select-options" id="select-options" value="0">';
                    ele.innerHTML = text;
                }
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
    
            function openTaskModal(status_id = -1){
                if(statusList.length == 0){
                    M.toast({html: "First add a new status",classes: 'rounded'});
                }
                else{
                    $('#addTaskModal').modal();
                    $('#addTaskModal').modal('open');
                    addStatusToTaskMenu(status_id);
                }
            }
    
            function displayTasks(xhttp) {
                //$('.dropdownTrigger').dropdown();
                tasksText = JSON.parse(xhttp.responseText);
               
                var taskDiv = "";
                tasksText.forEach(function addToDiv(key,index){
                    var id = "status-"+key.status_id;
                 
                    var status_div = document.getElementById(id);
                    var len = status.length;
                    var taskDiv = document.createElement("DIV");
                    taskDiv.id = "task-"+key.id;
                    taskDiv.classList.add("card","task-card");
                    var card_body = document.createElement("DIV");
                    taskDiv.classList.add("card-body");
                    var title_para = document.createElement("P");
                    var textnode = document.createTextNode(key.title);
                    title_para.appendChild(textnode);
                    card_body.appendChild(title_para);
                    if(key.checklist_item_count != null){
                        var progress = document.createElement("DIV");
                        progress.classList.add("progress");
                        var progressbar = document.createElement("DIV");
                        progressbar.classList.add("determinate");
                        progressbar.style.width = Math.ceil((key.checklist_done/key.checklist_item_count)*100) + "%";
                      
                        progress.appendChild(progressbar);
                        card_body.appendChild(progress);
                    }
                    taskDiv.appendChild(card_body);
                    taskDiv.addEventListener("click", function(){
                        window.location.href = "/project/"+key.project_id+"/task/"+key.id;
                    }); 
                    status_div.insertBefore(taskDiv,status_div[0]);
                    //hiddenTasks.push(taskDiv);
                   
                 });
            }                
        </script>
        
@endsection