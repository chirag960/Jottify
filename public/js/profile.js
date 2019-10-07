function uploadImage(){
    var Max_Size = 2097152;
    var image = document.getElementById("profileImage").files;
    var fileLength =image.length;
    var filePath = document.getElementById("profileImage").value;
    console.log(filePath);
    var ele = document.getElementById("invalidImage");
    var valid;
    if(filePath == ""){
        ele.innerHTML = "<strong>Please enter a valid path</strong>";
        ele.style.display = "block";
        valid = false;
    }
    else{
        var extension = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
        if(!(extension == "png" || extension == "jpg" || extension == "jpeg")){
            ele.innerHTML = "<strong>Only png, jpg or jpeg supported.</strong>";
            ele.style.display = "block";
            valid = false;
        }
        else{
            var size = image[0].size;
            console.log(size);
            if(size >= Max_Size){
                ele.innerHTML = "<strong>Image size should not be more than 2MB</strong>";
                ele.style.display = "block";
                valid = false;
            }
            else{
                valid = true;
            }
        }
    }
    if(valid == true){
        ele.style.display = "none";
        var formData = new FormData();
        Array.prototype.forEach.call(image, file => {
            formData.append("image",file);
            console.log("the filename inside for loop is : " + file);
        });
        console.log("the formdata is : " + formData);
        makePostRequestForImages("/profile/image",formData,updateImage);
    }
}

function updateImage(xhttp){
    var response = JSON.parse(xhttp.responseText);

    if(response.message == "errors"){
        var imageError = response.message.image;
        var ele = document.getElementById("invalidImage");
            imageError.forEach(function(key,index){
                console.log(imageError[index]);
                ele.innerHTML += "<p><strong>"+imageError[index]+"</strong></p>";
            });
            ele.style.display = "block";
    }
    else if(response.message == "success"){
        var src =  response.location.replace(/\\/g, "");
        document.getElementById("profileDiv").src =  response.location;
        document.getElementById("navbarProfile").src =  response.location;
        M.toast({html: "Profile image updated", classes: 'rounded'});
    }
    else{
        M.toast({html: response.message, classes: 'rounded'});
    }
}

function validateName(){
    var name = document.getElementById("name").value.trim();
    var ele = document.getElementById("invalidName");
    if(name == username){
        $("#editButton").html("mode_edit");
        $("#name").val(username);
        $("#name").prop('disabled',true);
        return false;
    }
    else if(name.length == 0){
        ele.innerHTML = "<strong>Please enter a name</strong>";
        ele.style.display = "block";
        return false;
    }
    else if(name.length < 3 || name.length > 20){
        ele.innerHTML = "<strong>Length should not be less than 3 or greater than 20</strong>";
        ele.style.display = "block";
        return false;
    }
    else {
        ele.style.display = "none";
        return true;
    }
}

function changeName(){

    var type = $("#editButton").html();
    if(type == "mode_edit"){
        $("#editButton").html("save");
        $("#name").prop('disabled',false);
        $('#name').focus();
        $('#name').select();
    }
    else{
        if(validateName()){
            var message = JSON.stringify({"name":document.getElementById("name").value});
            makePatchRequest("/profile",message,showNameChange);
        }
    }

    $('#name').keydown(function(e){
        if(e.which == 13){
            e.preventDefault();
            $("#name").prop("onkeydown", null).off("keydown");
            console.log('key is pressed');
            if(validateName()){
                var message = JSON.stringify({"name":document.getElementById("name").value});
                makePatchRequest("/profile",message,showNameChange);
            }
        }

    });
}

function showNameChange(xhttp){
    console.log(xhttp.responseText);
    var response = JSON.parse(xhttp.responseText);
    if(response.message == "success"){
        username = response.name;
        $("#editButton").html("mode_edit");
        $("#name").val(username);
        $("#name").prop('disabled',true);
        M.toast({html: "Profile name updated successfully", classes: 'rounded'});
    }
    else if(response.message == "errors"){
        var nameError = response.message.name;
        var ele = document.getElementById("invalidName");
            nameError.forEach(function(key,index){
                console.log(nameError[index]);
                ele.innerHTML += "<p><strong>"+nameError[index]+"</strong></p>";
            });
            ele.style.display = "block";
    }
    else{
        M.toast({html: response.message, classes: 'rounded'});
    }
}