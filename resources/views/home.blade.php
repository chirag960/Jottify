@extends('layouts.app')

@section('styles')

.project-list:hover{
    opacity:0.5;
    cursor: pointer; 
    }

.projectRow{
    display:block;
}

.projectTitle{
    padding: 0px !important;
    margin: 0px !important;
    top: 0px !important;
    right: 0px !important;
}
.projectName{
    width:100%;
    margin-left:3%;
}
.projectOps{
    bottom: 2%;
    position: absolute;
    right: 2%;
}
.none{
    display:none;
}

.star-head{
    width:100%;
}

i{
    cursor:pointer;
}
@media only screen and (max-width: 600px) {
    .create-form{
        width:100%;
    }
} 

@media only screen and (min-width: 600px) {
    .create-form{
        width:50%;
    }
} 
.active{
    color:#000 !important;
}

.collHead{
    border-top:0;
    border-left:0;
    border-right:0;
    padding-left:0 !important;
    padding-right:0 !important;
    background-color:white !important;
}
.collBody{
    border-bottom:0;
    background-color:#fff !important;
}
#responseMessage{
    display:none;
}
#toast-container {
  top: auto !important;
  right: auto !important;
  bottom: 10%;
  left:7%;  
}
@endsection

@section('full-content')

<div class="container">
    <div>
        <div class="alert alert-success" role="alert" id="responseMessage">
                <span id="responseContent"></span>
                <i class="material-icons right" onclick="closeResponseMessage()">close</i>
        </div>
    </div>
    <div class="row text-center create-form">
    <ul class="collapsible collHead col s6 m6 l6 xl6" data-collapsible="expandable">
            <li>
              <div class="collapsible-header" style="font-size: 20px;"><i class="material-icons">create</i>Create new project...</div>
              <div class="collapsible-body collBody">

                    <form method="POST" action="/projects">
                            @csrf
                            
                                <div class="input-field col s12">
                                    <input id="title" type="text" class="form-control" name="title" required autocomplete="off">
                                    <label for="title">Title</label>
                                        <span class="invalid-feedback" id="invalidTitle" role="alert"></span>
                                </div>
                            
                                <div class="input-field col s12">
                                    <textarea id="description" class="materialize-textarea" name="description" autocomplete="off"></textarea>
                                    <label for="description">Description (optional)</label>
                                        <span class="invalid-feedback" id="invalidDesc" role="alert"></span>
                                </div>
                            
                                <button type="button" class="btn waves-effect waves-light light-blue" onclick="validateProject()">Create</button>
                            
                    </form>

              </div>
            </li>
    </ul>
    </div>
    <div class="row">
        <h5 class="text-black star-head">Starred Projects</h5>
        <div class="align-center" id="star-list">
            <h6 class="text-black" id="star-text">Star your important projects to find them here!</h6>
        </div>
    </div>
    <div class="row">
        <h5 class="text-black">All Projects</h5>    
        <div id="project">
            <h6 class="text-black" id="star-text">Once you create a project, it will show up here!</h6>
        </div>
    </div>
</div>

    <div class="modal fade" id="newProjectForm" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
          <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <p>Create new Project</p>
              <form method="POST" action="/projects">
                @csrf
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="Text" class="form-control" name="title" placeholder="Title" required >
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="textarea" class="form-control" name="description" placeholder="Description (optional)">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
          </div>
          
        </div>
      </div>


</div>

@if (session('status'))
    <script>
        M.toast({html: {{ session('warning') }},classes: 'rounded'});
    </script>
@endif


<script>
    var newDiv="",projectsText;
    $(document).ready(function(){
        $(".dropdown-trigger").dropdown();
        $('.sidenav').sidenav();
        $('.collapsible').collapsible();
    });

    function closeValidationMessage(){
        document.getElementById("alertMessage").style.display = "none";
    }

    function closeResponseMessage(){
        document.getElementById("responseMessage").style.display = "none";
    }

    function showNewProject(xhttp){
        var key = JSON.parse(xhttp.responseText);
        if(key.message == "success"){
            document.getElementById("title").value = null;
            document.getElementById("description").value = null;
            $('.collapsible').collapsible('close', 0);
            var text = '<div class="col s12 m6 l3 xl3" onclick="redirect('+key.id+')" id="project-'+key.id+'"><div class="card" onmouseover="showOps(this)" onmouseout="hideOps(this)">';
            text+='<div class="card-image">';
            text+='<img src="'+key.background+'">';
            text+='<div class="card-title projectTitle">';
            text+='<span class="projectName truncate">'+key.title+'</span>';
            text+='<div class="projectOps"><i class="material-icons right none" id="unstar-'+key.id+'" onclick="starProject(event)">star_border</i>';
            text+='<i class="material-icons right none" id="report-'+key.id+'" onclick="reportProject(event)">file_download</i>';
            //text+='<i class="material-icons right none" id="delete-'+key.id+'" onclick="deleteProject(event)">delete</i></div>';
            text+='</div></div></div></div></div>';
            htmlElement = $.parseHTML(text);
            $("#projectList").prepend(htmlElement);
        }
        else if(key.message == "errors"){
            var titleError = key.errors.title;
            var descError = key.errors.description;
            if(titleError){
                var ele = document.getElementById("invalidTitle");
                titleError.forEach(function(key,index){
                    console.log(titleError[index]);
                    ele.innerHTML += "<p><strong>"+titleError[index]+"</strong></p>";
                });
                ele.style.display = "block";
            }
            if(descError){
                var ele = document.getElementById("invalidDesc");
                descError.forEach(function(key,index){
                    console.log(descError[index]);
                    ele.innerHTML += "<p><strong>"+descError[index]+"</strong></p>";
                });
                ele.style.display = "block";
            }
        }
        else{
            M.toast({html: response.message, classes: 'rounded'});
        }
    }

    function validateTitle(){
        var title = document.getElementById("title").value;
        var ele = document.getElementById("invalidTitle");
        if(title.length >= 30){
            ele.innerHTML = "<strong>The title should not be more than 30 letters.</strong>";
            ele.style.display = "block";
            return false;
        }
        else {
            ele.innerHTML = "";
            ele.style.display = "none";
            return true;
        }
        return true;
    }

    function validateDesc(){
        var desc = document.getElementById("description").value;
        var ele = document.getElementById("invalidDesc");
        if(desc.length != 0 && desc.length > 255){
            ele.innerHTML = "<strong>The description should not be more than 255 letters.</strong>";
            ele.style.display = "block";
            return false;
        }
        else {
            ele.innerHTML = "";
            ele.style.display = "none";
            return true;
        }
    }

    function validateProject(){
        var title = validateTitle();
        var desc = validateDesc();
        if(title && desc){
            var title = document.getElementById("title").value;
            var desc = document.getElementById("description").value;
            var message;
            if(desc.length != 0){
                message = JSON.stringify({"title":title,"description":desc});
            }
            else{
                message = JSON.stringify({"title":title});
            }
            makePostRequest("/projects",message,showNewProject);
        }
    }
    
    function displayProjects(xhttp) {
        
         var projectsText = JSON.parse(xhttp.responseText);

         if(projectsText['projects'].length !=0 ){
            var rowDiv = '<div class="projectRow row" id="projectList">';
            projectsText['projects'].forEach(function(key,index){  
                var text = '<div class="col s12 m6 l3 xl3" onclick="redirect('+key.id+')" id="project-'+key.id+'"><div class="card" onmouseover="showOps(this)" onmouseout="hideOps(this)">';
                text+='<div class="card-image">';
                text+='<img src="'+key.background+'">';
                text+='<div class="card-title projectTitle">';
                text+='<span class="projectName truncate">'+key.title+'</span>';
                text+='<div class="projectOps"><i class="material-icons right none" id="unstar-'+key.id+'" onclick="starProject(event)">star_border</i>';
                text+='<i class="material-icons right none" id="report-'+key.id+'" onclick="reportProject(event)">file_download</i>';
                //text+='<i class="material-icons right none" id="delete-'+key.id+'" onclick="deleteProject(event)">delete</i></div>';
                text+='</div></div></div></div></div>';

                rowDiv = rowDiv + text;
            });
            rowDiv = rowDiv + "</div>";
            document.getElementById("project").innerHTML = rowDiv;
         }
    
        if(projectsText['star'].length !=0){
            projectsText['star'].forEach(function(key,index){
                var star = document.getElementById("unstar-"+key.project_id);
                star.innerHTML = "star";
                star.classList.remove("none");
                star.classList.add("yellow-text");
                star.style.display = "block";
                var project = document.getElementById("project-"+key.project_id).cloneNode(true);
                var starredStar = project.querySelector("#unstar-"+key.project_id);
                starredStar.id = "starred-"+key.project_id;
                project.id = "starProject-"+key.project_id;
                document.getElementById("star-text").style.display = "none";
                document.getElementById("star-list").appendChild(project);
            });
        }
    }

        function checkStar(xhttp){
            var response = JSON.parse(xhttp.responseText);
            if (response.message == "success"){
                if(response.star == 1){
                star = document.getElementById("unstar-"+response.id);
                star.innerHTML = "star";
                star.classList.remove("none");
                star.classList.add("yellow-text");
                star.style.display = "block";
                var pid = "project-"+response.id;
                var project = document.getElementById(pid);
                var project = project.cloneNode(true);
                var starredStar = project.querySelector("#unstar-"+response.id);
                starredStar.id = "starred-"+response.id;
                project.id = "starProject-"+response.id;
                document.getElementById("star-list").appendChild(project);
                document.getElementById("star-text").style.display = "none";
                }
                else{
                    star = document.getElementById("unstar-"+response.id);
                    var sid = "unstar-"+response.id;
                    star = document.getElementById(sid);
                    star.innerHTML = "star_border";
                    star.classList.add("none");
                    star.classList.remove("yellow-text");
                    star.style.display = "none";
                    var spid = "starProject-"+response.id;
                    var starProject = document.getElementById(spid);
                    starProject.parentNode.removeChild(starProject);
                    console.log(document.getElementById("star-list").childElementCount);
                    if(document.getElementById("star-list").childElementCount == 1){
                        document.getElementById("star-text").style.display = "block";
                }
            }
            }
            else{
                // document.getElementById("responseContent").innerHTML += response.message;
                // document.getElementById("responseMessage").style.display = "block";
                M.toast({html: response.message, classes: 'rounded'});
            }
        }

        function starProject(event){
            event.stopPropagation();
            console.log(event);
            var star = event.target;
            var sid = star.id.split("-");
            var id = sid[1];
            var message = "";

            //starring the project
            if(star.innerHTML == "star_border"){        
                
                message = JSON.stringify({"star":1});
            }

            //unstarring the project
            else{
                
                
                message = JSON.stringify({"star":0});
            }
            makePatchRequest("/project/"+id+"/star",message,checkStar);
        }

        function updateReport(xhttp){
            //console.log("From update report :" + xhttp.responseText);
            var blob = xhttp.response;
            console.log(blob.size);
            var link=document.createElement('a');
            link.href=window.URL.createObjectURL(blob);
            link.download="Dossier_" + new Date() + ".pdf";
            document.body.appendChild(link);
            link.click();
        }

        function reportProject(event){
            event.stopPropagation();
            var div = event.target;
            var did = div.id.split("-");
            var id = did[1];
            makeGetRequestForPDF("/project/"+id+"/report",updateReport);
        }

        function deleteProject(event){
            event.stopPropagation();
            console.log("yes");
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

    makeGetRequest("/projects",displayProjects);
        
    function redirect(id){
        window.location.href = "/project/"+id;
    }
</script>
    
@endsection
