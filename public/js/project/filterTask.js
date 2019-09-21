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
