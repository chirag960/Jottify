@extends('layouts.app')

@section('styles')
.row{
    display:block;
}
.profile-image{
    float:none;
}
.edit-image{
    float:right;
}
@endsection

@section('full-content')
<div class="container row">
    <div class="card-panel white col s10 offset-s1 m8 offset-m2 l4 offset-l4">
    <div class="card-body row" style="margin-left: 0px;margin-right: 0px;">
        <div class="row">
                <img id="profileDiv" src="{{Auth::user()->photo_location}}" alt=""  width="300" class="circle profile-image responsive-img">      
                <form method="POST" class="col s12 center">
                        @csrf
                        @method('patch')
                            <div class="btn file-field input-field">
                                <span>Change profile Image</span>
                                <input type="file" id="profileImage" accept=".png,.jpg,.jpeg" onchange="uploadImage()">
                            </div>
                        
                    </form>
        
            </div>
        <div class="row">
            <form method="POST">
            @csrf
            @method('patch')
            <div class="input-field col s12">
                <i class="material-icons prefix">account_circle</i>
                <input value="{{Auth::user()->name}}" id="first_name2" type="text" class="validate" onblur="changeName(this)">
                <label class="active" for="first_name2">Name</label>
            </div>
            </form>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <i class="material-icons prefix">email</i>
                <input disabled value={{Auth::user()->email}} id="disabled" type="text" class="validate">
                <label for="disabled">Email</label>
            </div>
        </div>
</div>
</div>
</div>

<script>
    var username = "{{ Auth::user()->name }}" ;
    $(document).ready(function(){
    $('.profile-image').materialbox();
  });

    function uploadImage(){
        console.log("yes");
        var image = document.getElementById("profileImage").files;
        var fileLength =image.length;
        var formData = new FormData();

        Array.prototype.forEach.call(image, file => {
            formData.append("image",file);
            console.log("the filename inside for loop is : " + file);
        });
        console.log("the formdata is : " + formData);
        makePostRequestForImages("/user/Image",formData,updateImage);
    }

    function updateImage(xhttp){
        console.log(xhttp.responseText);
        document.getElementById("profileDiv").src =  xhttp.responseText;
        document.getElementById("navbarProfile").src =  xhttp.responseText;
    }


    function changeName(element){
        if(element.value == "{{ Auth::user()->name}}" ) {
            console.log("same same");
        }
        else{
            console.log("not same");
            var message = JSON.stringify({"name":element.value});
            makePatchRequest("/profile",message,showNameChange);
        }
    }

    function showNameChange(xhttp){
        console.log("done");
        username = xhttp.responseText;
    }

</script>
@endsection