@extends('layouts.app')

@section('styles')

body{
    min-height:auto;
}

.project-bar{
    height:50px;
    width:100%;
    position:fixed;
    margin-top:20px;
}

.go-inline{
    float:left;
}
.status-card-container{
    margin-top:60px;
    display:flex;
  	flex-direction: row;
  	flex-wrap: nowrap;
  	white-space:nowrap;
}
.status-div{
    flex: 0 0 272px;
    min-height:30px;
    height:auto !important;
    margin:10px;
}
.status-name{
    width:272px;
    padding:0;
    margin-left:10px;
    margin-right:10px;
}
.card-header{
    text-overflow:ellipses;
}
.secondary-content{
    right:0px !important;
}
.admin-icons{
    margin-right:0px;
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

.addTask:hover{
    cursor:pointer;
    text-decoration: underline;
}
@endsection

@section('full-content')
<div class="project-bar row white-text">
    <!-- Left Side Of Navbar -->
    <div class="col s12 m6 l6 xl6">
            <span id="nav-title">{{ $project['title'] }}</span>  <!-- Make this remanable -->
            <!--
            @if ($project['star'] == 1)
            <i class="material-icons yellow-text" onclick="toggleStar(this)">star</i>
            @else
            <i class="material-icons" onclick="toggleStar(this)">star_border</i>
            @endif
            -->
            <a class="waves-effect waves-light btn dropdown-trigger" data-target='inviteDrop'>Invite</a>
    </div>
    <!-- Right Side Of Navbar -->
    <div class="col s12 m6 l6 xl6">
        <a href="#" data-target="slide-out" class="sidenav-trigger show-on-large right" ><i class="material-icons text-white">menu</i></a>
        <!--i class="material-icons right">file_download</i-->
        <a class="waves-effect waves-light btn right" onclick="filterTask()">Filter</a>
        <!--button type="buttton" class="btn btn-primary" onclick="resetTask()">Reset Tasks</button-->  
    </div>
</div>
<div class="status-card-container" id="status-list">

</div>


<ul id="slide-out" class="sidenav">
    <li>
        <input value="{{$project['title']}}" type="Text" class="form-control" name="title" onblur="changeTitle(this)" placeholder="Title" required>
    </li>
    <li><textarea id="description" class="materialize-textarea" name="description" onblur="changeDescription(this)">{{$project['description']}}</textarea></li>
    <li><a href="#">Created at {{ $project['timestamp']}}</a></li>
    <li><div class="divider"></div></li>
    <li><a class="subheader">Members</a></li>
    <ul class="collection">
        @foreach ($project['members'] as $member)
            <li class="collection-item avatar col s12">
                <img src="{{$member->photo_location}}" alt="" class="circle">
                <span class="title">{{$member->name}}</span>
                <!--p>{{$member->email}}</p>
                @if($member->role == 0)
                <a href="#!" class="secondary-content"><i class="material-icons admin-icons" onclick="addAdmin({{ $member->id}},this)">group_add</i></a>
                @else
                <a href="#!" class="secondary-content"><i class="material-icons admin-icons" onclick="removeAdmin({{ $member->id}},this)">remove_circle</i></a>
                @endif
                <a href="#!" class=""><i class="material-icons" onclick="deleteMember({{ $member->id}},this)">delete</i></a-->
            </li>    
        @endforeach   
    </ul>
  </ul>

<div class="modal fade" id="newTaskForm" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
          <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <p class="text-center">Create new Task</p>
          <form method="POST" action="/project/{{$project['id']}}/task">
                @csrf
                    <input type="hidden" name="status_id" id="input_status_id">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input id="sideTitle" type="Text" class="form-control" name="title" placeholder="Title" required >
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input id="sideDesc" type="textarea" class="form-control" name="description" placeholder="Description (optional)">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
          </form>
          </div>
        </div>
      </div>
</div>

<div class="modal fade" id="addStatusModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <p class="text-center">Create new Status</p>
      <form method="POST" action="/project/{{$project['id']}}/status">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="Text" class="form-control" name="title" placeholder="Title" required >
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="textarea" class="form-control" name="description" placeholder="Description (optional)">
            </div>
            <button type="button" class="btn btn-primary" onclick="createStatus()">Submit</button>
      </div>
      
    </div>
  </div>


</div>

@include('inviteMember')

@include('inviteDrop')

<script>
    document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems, {edge: 'right',draggable: true,});
    $('.dropdown-trigger').dropdown();
    $('.chips-placeholder').chips({
    placeholder: 'Enter a tag',
    secondaryPlaceholder: '+Tag',
  });
  });
        var project_id = "{!! $project['id'] !!}";
        console.log(project_id);
        var projectsText;
        var membersText;
        var tasksText;
        var hiddenTasks = [];

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

        function showTitleChange(xhttp){
            document.getElementById("navTitle").innerHTML = xhttp.response;
            document.getElementById("sideTitle").innerHTML = xhttp.response;
        }

        function changeTitle(element){
            if(element.value == "{{ $project['title'] }}"){

            }
            else{
                var message = JSON.stringify({"_token":document.getElementsByTagName("META")[2].content, "title":element.value});
                makePatchRequest("/project/{!!$project['id']!!}/title",message,showTitleChange);
            }
        }

        function showDescChange(xhttp){
            document.getElementById("sideDesc").innerHTML = xhttp.response;
        }

        function changeDescription(element){
            if(element.value == "{{ $project['description'] }}"){
            }
            else{
                var message = JSON.stringify({"title":element.value});
                makePatchRequest("/project/{{ $project['id']}}/description",message,showDescChange);
            }
        }

        function showAdminAdd(xhttp){
            console.log(xhttp.responseText);
        }

        function addAdmin(id,element){
            var message = JSON.stringify({"admin":"1"});
            element.innerHTML = "remove_circle";
            element.onclick = function () { removeAdmin(id,element);};
            makePatchRequest("/project/{{ $project['id']}}/member/"+id,message,showAdminAdd);
        }

        function showAdminRemove(xhttp){
            console.log(xhttp.responseText);
        }

        function removeAdmin(id,element){
            var message = JSON.stringify({"admin":"0"});
            element.innerHTML = "group_add";
            element.onclick = function () { addAdmin(id,element);};
            makePatchRequest("/project/{{ $project['id']}}/member/"+id,message,showAdminRemove);
        }

        function showAdminRemove(xhttp){
            console.log(xhttp.responseText);
        }

        function showDeleteMember(xhttp){
            console.log("deleted");
        }

        function deleteMember(id,element){
            element.parentNode.removeChild(element);
            //loadDoc("DELETE","/project/{{ $project['id']}}/member/"+id,null,showDeleteMember);
        }

        function displayStatus(xhttp) {
            var statusText = JSON.parse(xhttp.responseText);
            console.log(xhttp.responseText);
            var newDiv = "";
            statusText.forEach(function addToDiv(key,index){
                newDiv += "<div class='card-panel status-name grey lighten-4'><div class='card-header'>";
                newDiv += key.title + "<i class='material-icons dropdown-toggle right' data-toggle='dropdown'></i><ul class='dropdown-menu'><li><p href='#'>Add another status right to it</p></li><li><p href='#'>Delete status</p></li><li><p href='#'>Add task</p></li></ul></div>";
                newDiv += "<div class='card-body' id='status-"+key.id+"'><p class='text-center addTask' data-toggle='modal' data-target='#newTaskForm'  onclick='sendID("+key.id+")'>+ Add task</p></div>";
                newDiv += "</div>";
             });
             newDiv += "<div class='card status-div text-center task-card' data-toggle='modal' data-target='#addStatusModal'>+</div>";
             document.getElementById("status-list").innerHTML = newDiv;
             makeGetRequest("/project/{{ $project['id'] }}/tasks",displayTasks);
        }

        function displayTasks(xhttp) {
            tasksText = JSON.parse(xhttp.responseText);
            console.log(xhttp.responseText);
            var taskDiv = "";
            tasksText.forEach(function addToDiv(key,index){
                var id = "status-"+key.status_id;
                console.log(id);
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
                    console.log(progressbar.style.width);
                    progress.appendChild(progressbar);
                    card_body.appendChild(progress);
                }
                taskDiv.appendChild(card_body);
                taskDiv.addEventListener("click", function(){
                    window.location.href = "/project/"+key.project_id+"/task/"+key.id;
                }); 
                status_div.insertBefore(taskDiv,status_div[0]);
                //hiddenTasks.push(taskDiv);
                console.log("Phew!! Done.")
             });
        }


        makeGetRequest("/project/{!! $project['id'] !!}/statuses",displayStatus);
            
        function filterTask(){
            var startDate = new Date(2019,07,15,00,00,00);
            var endDate = new Date(2019,07,27,00,00,00);

            var members = ["1"];

            var startProgress = 50;
            var endProgress = 80;

            
            var check1=true;
            var check2=true;
            var check3=true;
            tasksText.forEach(function (key,index){
                if(key.checklist_item_count != null){
                    var progress = Math.ceil((key.checklist_done/key.checklist_item_count)*100);
                    console.log("this is the progress"+progress+"for id"+key.id);
                    if(checkParams(progress,startProgress,endProgress) == true){
                        console.log("not removing because progress"+progress+"for id"+key.id);
                        //return;
                    }
                    else{check1 = false;}
                }
                else{check1 = false;}
                //else return;

                if(key.due_date != null){
                    var due = new Date(key.due_date);
                    console.log("this is the due date"+due+"for id"+key.id);
                    if(checkParams(due.getTime(),startDate.getTime(),endDate.getTime()) == true){
                        console.log("not removing due "+due+"for id"+key.id);
                        //return;
                    }
                    else{check2 = false;}
                }
                else{check2 = false;}
                //else return;

                var checkNotSubset = function(element){
                    return members_array.indexOf(element) === -1;
                }

                /*
                if(key.members != null){
                    var members_array = key.members[0]['id'];
                    console.log(key.members);
                    if(members.some(checkNotSubset)){
                        console.log(members_array);
                        console.log("members is false for id"+key.id);
                        return;
                    }
                }
                //else return;
                */
                
                console.log("This task will be shown" + key.id);
                if((check1 == false) || (check2 == false)){
                if(document.getElementById("task-"+key.id)){
                    var element =document.getElementById("task-"+key.id);
                    hiddenTasks.push(element)
                    element.style.display = "none";
                }
                check1 = true;
                check2 = true;
                check3 = true;
                }
                else{
                    document.getElementById("task-"+key.id).style.display = "block";

                }

            });
            //console.log(hiddenTasks);
        }

        function resetTask(){
            hiddenTasks.forEach(function(element){
                element.style.display = "block";
            });
            hiddenTasks = [];
        }

        function checkParams(param, start, end){
            if((start == null || param >= start) && (end == null || param <= end))
                return true;
            else 
                return false;
        }

        function sendID(id){
            var inputTag = document.getElementById("input_status_id");
            //console.log(id);
            inputTag.value = id;
        }

        function stopClosingDropDown(event){
            event.preventDefault()
        }
    </script>

@endsection