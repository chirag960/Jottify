@extends('layouts.app')

@section('style-link')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('full-content')

<div class="container">
    <div class="row text-center create-form">
    <ul class="collapsible collHead col s6 m6 l6 xl6" data-collapsible="expandable">
        <li id="collHead-li">
            <div class="collapsible-header" style="font-size: 20px;"><i class="material-icons">create</i>Create new project...</div>
            <div class="collapsible-body collBody">
                <form>
                        <div class="input-field col s12">
                            <input id="title" type="text" class="form-control" name="title" required autocomplete="off">
                            <label for="title">Title</label>
                                <span class="invalid-feedback" id="invalidTitle" role="alert"></span>
                        </div>
                    
                        <div class="input-field col s12">
                            <textarea id="description" class="materialize-textarea" name="description" autocomplete="off" rows="3"></textarea>
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
        </div>
    </div>
    <div>
        <h5 class="text-black">All Projects</h5>
        <div id="project" class="row">                
        </div>
    </div>
</div>
    
@endsection

@section('links')

@if (session('status'))
    <script>
        M.toast({html: {{ session('warning') }},classes: 'rounded'});
    </script>
@endif

<script type="text/javascript" src="{{ asset('js/home.js') }}"></script>
@endsection


