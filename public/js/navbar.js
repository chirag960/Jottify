    // if(document.getElementById("searchBar")){
    //     console.log("inside if");
        
    // }

    function throttleSearchTask(fn, threshhold) {
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
                fn(document.getElementById("searchBar").value);
                }, threshhold);
            } else {
                console.log("hey");
                last = now;
                fn(document.getElementById("searchBar").value);
            }
        };
    }

    function showTasks(pattern){
        if(pattern.length > 2){
            makeGetRequest("/projectAndTask?pattern="+pattern,showResultDropDown,false);
        }
        else{
            if($('#dropTaskList')){
                $('#dropTaskList').empty();
            }
            console.log("something");
        }
    }

    function showResultDropDown(xhttp){
        console.log(JSON.parse(xhttp.responseText));
        var titleList =  JSON.parse(xhttp.responseText);
        
        document.getElementById("results").innerHTML = "";
        
        if(titleList.length == 0){
            var ulist = $("<ul></ul>");
            ulist.attr('id','dropTaskList');
            //ulist.addClass('dropdown-content')
            var lList = $("<li></li>").addClass("black-text white row");
            var heading = $("<a></a>").text("No results found");
            lList.append(heading);
            ulist.append(lList);
            $('#results').append(ulist);
            document.getElementById("results").style.display = "block";
        }
        else{
            var ulist = $("<ul></ul>");
            ulist.attr('id','dropTaskList');
            //ulist.addClass('dropdown-content')
            if(titleList.projects){

                var lList = $("<li></li>").addClass("black-text title-list white");
                lList.text("Matching projects :");
                ulist.append(lList);

                titleList.projects.forEach(function(key,index){
                    var lList = $("<li></li>").addClass("black-text title-list white");
                    var heading = $("<a></a>").text(key.title);
                    heading.attr('href','/project/'+key.id);
                    heading.addClass('black-text');
                    lList.append(heading);
                    ulist.append(lList);
                });
            }
            else{
                var lList = $("<li></li>").addClass("black-text title-list white");
                lList.text("No projects found");
                ulist.append(lList);
            }

            if(titleList.tasks){

                var lList = $("<li></li>").addClass("black-text title-list white");
                lList.text("Matching tasks :");
                ulist.append(lList);

                titleList.tasks.forEach(function(key,index){
                    var lList = $("<li></li>").addClass("black-text title-list white");
                    var heading = $("<a></a>").text(key.title);
                    heading.attr('href','/project/'+key.project_id + "/task/"+ key.id);
                    heading.addClass('black-text title-a');
                    // var heading2 = $("<span></span>").text("in project: " + key.project_title);
                    // heading2.attr('href','/project/'+key.project_id);
                    // heading2.addClass('black-text');
                    lList.append(heading);
                    //lList.append(heading2);
                    ulist.append(lList);
                });
            }
            else{
                var lList = $("<li></li>").addClass("black-text title-list white");
                lList.text("No tasks found");
                ulist.append(lList);
            }

            $('#results').append(ulist);
            document.getElementById("results").style.display = "block";
        }

        // document.getElementById("results").onmouseleave = function(){

        //     document.getElementById("results").style.display = "none";
        //     document.getElementById("searchBar").innerHTML = "";

        // };
        
    }