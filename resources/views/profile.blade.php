@extends('layouts.app')

@section('style-link')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection

@section('full-content')
<div class="row">
    <div class="card-panel white col s10 offset-s1 m8 offset-m2 l4 offset-l4 xl4 offset-xl4">
    <div class="card-body row" style="margin-left: 0px;margin-right: 0px;">
        <div id="image-container">    
        <img id="profileDiv" src="{{Auth::user()->photo_location}}" alt=""  width="300" class="circle profile-image">      
        </div>
            <form class="col s12 center">
                    <div class="btn file-field input-field">
                        <span>Change Image</span>
                        <input type="file" id="profileImage" accept=".png,.jpg,.jpeg" onchange="uploadImage()">
                    </div>
                    <span class="invalid-feedback hideMessage" id="invalidImage" role="alert"></span>
            </form>
            <form class="">
                <div class="row" id="editButtonDiv">
                <div class="input-field col s10 m10 l10 xl10 left">
                    <i class="material-icons prefix">account_circle</i>
                <input value="{{Auth::user()->name}}" id="name" type="text" class="validate" disabled>
                    <label class="active" for="name">Name</label>
                    <span class="invalid-feedback hideMessage" id="invalidName" role="alert"></span>
                </div>
                <a class="btn-floating btn red left s1 m1 l1 xl1 edit-button" onclick="changeName()">
                    <i class="large material-icons" id="editButton">mode_edit</i>
                </a>
                </div>
            </form>
            <div class="input-field col s12 m12 l12 xl12 left">
                <i class="material-icons prefix">email</i>
                <input disabled value={{Auth::user()->email}} id="disabled" type="text" class="validate">
                <label for="disabled">Email</label>
            </div>
</div>
</div>
</div>
@endsection


@section('links')
<script type="text/javascript" src="{{ asset('js/profile.js') }}"></script>

<script>
        var username = "{{ Auth::user()->name }}" ;
    
        $(document).ready(function(){
            $('.dropdown-trigger').dropdown();
            document.getElementById("searchBar").addEventListener("keyup", throttleSearchTask(showTasks, 500));
        });
    
        $(document).on("click", function(event){
            var $trigger = $("#editButtonDiv");
            if($trigger !== event.target && !$trigger.has(event.target).length){
                $("#editButton").html("mode_edit");
                $("#name").val(username);
                $("#name").prop('disabled',true);
            }            
        });
        
</script>

@endsection