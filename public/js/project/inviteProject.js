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
                    console.log("something");
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
                        text = "<li class='black-text white row'><a>No users found</a></li>";
                        var lList = $(text);
                        uList.append(lList);
                    }
                    else{
                        //ulist.addClass('dropdown-content')
                        membersList.forEach(function(key,index){
                            var name = key.name;
                            console.log(key);
                            text = "<li class='black-text title-list white'><label><input type='checkbox' onclick='checkedMember("+key.id+","+JSON.stringify(key.name)+")'><span class='black-text'>"+key.name+"("+key.email+")</span></label></li>";
                            //text+=;
                            var lList = $(text);
                            //heading.attr('href','/project/'+key.id);
                            uList.append(lList);
                        });
        
                        //document.getElementById("results").style.display = "block";
                    }
                }  
            }
        
            function checkedMember(id,name){
                console.log(id + "is clicked");
                var text = "<div class='chip'>"+id+"<i class='close material-icons'>close</i></div>";
                $('#membersView').append(text);
            }