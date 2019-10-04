var newDiv="",projectsText;
makeGetRequest("/projects",displayProjects);

    $(document).ready(function(){
        //$("").dropdown();
        $(".dropdown-trigger").dropdown();
        $('.collapsible').collapsible({'onOpenEnd': focusInput});
        document.getElementById("searchBar").addEventListener("keyup", throttleSearchTask(showTasks, 500));

    });

    jQuery(document).bind("keydown", function(e){
        if(e.ctrlKey && e.keyCode == 80){
            e.preventDefault();
            if($('#collHead-li').hasClass('active')){
                $('.collapsible').collapsible('close');    
            }
            else{
                $('.collapsible').collapsible('open');
            }
            
        }
        
        if(e.ctrlKey && e.keyCode == 70){
            e.preventDefault();
            $('#searchBar').focus();
            
        }
    });

function focusInput(){
    $('#title').focus();
}

function closeValidationMessage(){
    document.getElementById("alertMessage").style.display = "none";
}

function showNewProject(xhttp){
    var key = JSON.parse(xhttp.responseText);
    if(key.message == "success"){
        document.getElementById("title").value = null;
        document.getElementById("description").value = null;
        $('.collapsible').collapsible('close', 0);
        var text = '<div class="col s12 m6 l3 xl3 project-card" onclick="redirect('+key.id+')" id="project-'+key.id+'"><div class="card" onmouseover="showOps(this)" onmouseout="hideOps(this)">';
        text+='<div class="card-image">';
        text+='<img src="'+key.background+'">';
        text+='<div class="card-title projectTitle">';
        text+='<span class="projectName truncate">'+key.title+'</span>';
        text+='<div class="projectOps"><i class="material-icons right none" id="unstar-'+key.id+'" onclick="starProject(event)">star_border</i>';
        text+='<i class="material-icons right none" id="report-'+key.id+'" onclick="reportProject(event)">file_download</i>';
        text+='<i class="material-icons right none" id="delete-'+key.id+'" onclick="deleteProject(event)">delete</i></div>';
        text+='</div></div></div></div></div>';
        htmlElement = $.parseHTML(text);
        if($("#projectList").length){
            $("#projectList").append(htmlElement);
        }
        else{
            var projectList = $("<div></div>").addClass("projectRow row");
            projectList.attr('id','projectList');
            projectList.append(htmlElement);
            $('#project').empty();
            $('#project').append(projectList);
        }
        M.toast({html: "New project created", classes: 'rounded'});
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
    if(title.length > 30 || title.length < 3){
        ele.innerHTML = "<strong>The title should not be more than 30 letters or less than 3.</strong>";
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
     var starProjects = new Array();
     if(projectsText.length !=0 ){
        var rowDiv = '<div class="projectRow row" id="projectList">';
        projectsText.forEach(function(key,index){  
            var text = '<div class="col s12 m6 l3 xl3 project-card" onclick="redirect('+key.id+')" id="project-'+key.id+'"><div class="card" onmouseover="showOps(this)" onmouseout="hideOps(this)">';
            text+='<div class="card-image">';
            text+='<img src="'+key.background+'">';
            text+='<div class="card-title projectTitle">';
            text+='<span class="projectName truncate">'+key.title+'</span>';
            text+='<div class="projectOps"><i class="material-icons right none" id="unstar-'+key.id+'" onclick="starProject(event)">star_border</i>';
            if(key.star == 1){
                starProjects.push(key.id);
            }
            if(key.role == 2){
                text+='<i class="material-icons right none" id="report-'+key.id+'" onclick="reportProject(event)">file_download</i>';
                text+='<i class="material-icons right none" id="delete-'+key.id+'" onclick="deleteProject(event)">delete</i>';
            }
            text+='</div>';
            text+='</div></div></div></div>';

            rowDiv = rowDiv + text;
        });
        rowDiv = rowDiv + "</div>";
        document.getElementById("project").innerHTML = rowDiv;
     }
     else{
        document.getElementById("project").innerHTML = '<h6 class="grey-text" id="project-text">Once you create a project, it will show up here!</h6>';
     }

    if(starProjects.length !=0){
        for(i = 0; i < starProjects.length; i++){
            var star = document.getElementById("unstar-"+starProjects[i]);
            star.innerHTML = "star";
            star.classList.remove("none");
            star.classList.add("yellow-text");
            star.style.display = "block";
            var project = document.getElementById("project-"+starProjects[i]).cloneNode(true);
            var starredStar = project.querySelector("#unstar-"+starProjects[i]);
            starredStar.id = "starred-"+starProjects[i];
            project.id = "starProject-"+starProjects[i];
            document.getElementById("star-list").appendChild(project);
        }
    }else{
        document.getElementById("star-list").innerHTML = "<h6 class='grey-text' id='star-text'>Star your important projects to find them here!</h6>";
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
            if($('#star-text').length){
                $('#star-text').remove();
            }
            document.getElementById("star-list").appendChild(project);
            M.toast({html: "Successfully star marked", classes: 'rounded'});
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
                if(document.getElementById("star-list").childElementCount == 0){
                    document.getElementById("star-list").innerHTML = "<h6 class='grey-text' id='star-text'>Star your important projects to find them here!</h6>";
                }
                M.toast({html: "Successfully removed star marked", classes: 'rounded'});
        }
        }
        else{
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

    function deleteProject(event){
        event.stopPropagation();
        console.log(event);
        var project = event.target;
        var pid = project.id.split("-");
        var id = pid[1];
        makeDeleteRequest("/project/"+id,updateDeleteProject);
    }

    function updateDeleteProject(xhttp){
        var response = JSON.parse(xhttp.responseText);
        if (response.message == "success"){
            var id = response.id;
            if($('#starProject-'+id).length){
                $('#starProject-'+id).remove();
                if(document.getElementById("star-list").childElementCount == 0){
                    document.getElementById("star-list").innerHTML = "<h6 class='grey-text' id='star-text'>Star your important projects to find them here!</h6>";
                }
            }
            if($('#project-'+id).length){
                $('#project-'+id).remove();
                if(document.getElementById("projectList").childElementCount == 0){
                    $("#projectList").remove();
                    document.getElementById("project").innerHTML = "<h6 class='grey-text' id='star-text'>Once you create a project, it will show up here!</h6>";
                }
            }
            M.toast({html: "Successfully deleted", classes: 'rounded'});
        }
        else{
            M.toast({html: response.message, classes: 'rounded'});
        }
    }

    function updateReport(xhttp){
        console.log(xhttp.status);
        var blob = xhttp.response;
        console.log("this is response");
        console.log(xhttp.response);
        if(xhttp.response.message == "error"){
            M.toast({html: response.error, classes: 'rounded'});
        }
        else{
            var disposition = xhttp.getResponseHeader('filename');
            console.log(disposition);
            var matches = /"([^"]*)"/.exec(disposition);
            var filename = disposition+".csv";
    
            // The actual download
            var blob = new Blob([xhttp.response], { type: 'text/csv' });
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
    }

    function reportProject(event){
        event.stopPropagation();
        var div = event.target;
        var did = div.id.split("-");
        var id = did[1];
        makeGetRequestForCSV("/project/"+id+"/report",updateReport);
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

    function redirect(id){
        window.location.href = "/project/"+id;
    }
