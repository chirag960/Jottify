function showOps(ele) {
    elements = ele.querySelectorAll('.overlay-options');
    for (var i = 0; i < elements.length; i++) {
        elements[i].style.display="block";	
        }      
    }

    function hideOps(ele){
        elements = ele.querySelectorAll(".overlay-options");
        for (var i = 0; i < elements.length; i++) {
        elements[i].style.display="none";	
        }
    }

    function showAdminAdd(xhttp){
        console.log(xhttp.responseText);
        var response = JSON.parse(xhttp.responseText);
        if(response.message == 'success'){
            var admin_icon = document.getElementById("admin-"+response.id);
            admin_icon.innerHTML = "remove_circle";
            admin_icon.title = "remove as admin";
            admin_icon.onclick = function () {
                                     removeAdmin(response.id,admin_icon);
                                };
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
            var admin_icon = document.getElementById("admin-"+response.id);
            admin_icon.innerHTML = "group_add";
            admin_icon.title = "make admin";
            admin_icon.onclick = function () { 
                                    addAdmin(response.id,admin_icon);
                                };
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
        }
        else{
            M.toast({html: response.message, classes: 'rounded'});
        }
    }

    function deleteMember(id,element){
        element.parentNode.removeChild(element);
        makeDeleteRequest("/project/"+project_id+"/member/"+id,showDeleteMember);
    }