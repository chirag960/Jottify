@extends('layouts.app')

@section('style-link')
<link href="{{ asset('css/project.css') }}" rel="stylesheet">

@endsection

@section('full-content')

<div class="fixed-action-btn">
    <a class="btn-floating btn-large red">
        <i class="large material-icons">mode_edit</i>
    </a>
    <ul>
        <li><a class="btn-floating red tooltipped" data-position="left" data-tooltip="project settings"><i href="#" data-target="slide-out" class="material-icons sidenav-trigger show-on-large right">settings</i></a></li>
        @if($project['role'] > 0)
        <li><a class="btn-floating yellow darken-1 tooltipped" data-position="left" data-tooltip="invite members"><i class="material-icons modal-trigger" href="#addInviteProjectModal" >person_add</i></a></li>
        @endif
        <li><a class="btn-floating green tooltipped" data-position="left" data-tooltip="add task" ><i class="material-icons" onclick="openTaskModal()">playlist_add</i></a></li>
        <li><a class="btn-floating blue tooltipped" data-position="left" data-tooltip="add status"><i class="material-icons" onclick="openStatusModal()">label_outline</i></a></li>
    </ul>
</div>
<div class="project-bar row">
    <!-- Left Side Of Navbar -->
    <div class="col s12 m6 l6 xl6">
            <span class="project-heading">{{ $project['title'] }}</span>  <!-- Make this remanable -->
            <!--
            @if ($project['star'] == 1)
            <i class="material-icons yellow-text" onclick="toggleStar(this)">star</i>
            @else
            <i class="material-icons" onclick="toggleStar(this)">star_border</i>
            @endif
            -->
            <span id="project-admin-avatars">
                    @php 
                        $check = 1;
                        $count=count($project['members']);
                    @endphp
                    @foreach($project['members'] as $member)
                    <img id="icon-{{$member->id}}" class='avatar-image circle' src='{{$member->photo_location}}' title='{{$member->name." (".$member->email.")"}}'>
                    @php
                        $check++;
                    @endphp
                    @if(($check > 9) && ($count == 10))
                        <img id="icon-{{$member->id}}" class='avatar-image circle' src='{{$member->photo_location}}' title='{{$member->name." (".$member->email.")"}}'>
                    @break
                    @endif
                    @if(($check > 9) && ($count > 10))
                        <span class='avatar-image circle all-member-icon' id='admins-icons' onclick='openSideNav()'>+<span id='left-member-count'>{{$count - 9}}</span></span>
                    @break
                    @endif
                    @endforeach
            </span>
    </div>
    <!-- Right Side Of Navbar -->
    <div class="col s12 m6 l6 xl6">
        <!--i class="material-icons right">file_download</i-->
        <!--a class="waves-effect waves-light btn right" onclick="filterTask()">Filter</a-->
        <!--button type="buttton" class="btn btn-primary" onclick="resetTask()">Reset Tasks</button-->  
    </div>
</div>
<div class="status-card-container" id="status-list">

</div>


<ul id="slide-out" class="sidenav">
    <li>
        <div class="user-view">
            <div class="background">
                <img src="{{$project['background']}}">
            </div>
            <a class="center-align"><span class="white-text project-title">{{$project['title']}}</span></a>
        </div>
    </li>
    <li id="creation-time-heading"><i class="material-icons align-logo">access_time</i><span class="align-text-logo">Created on {{ $project['created_at'] }}</span></li>
    @isset($project['description'])
    <li><div class="divider"></div></li>
    <li id="description-heading">{{$project['description']}}</li>
    @endisset
    <li><div class="divider"></div></li>
    <li id="members-heading">All Members</li>
    
        @if(count($project['members']) != 0)
            <ul class="collection" id="members-list">
            @foreach ($project['members'] as $member)
            <li class="collection-item avatar min-height-auto" id="member-{{$member->id}}" onmouseover="showOps(this)" onmouseout="hideOps(this)">
                    @if($member->id == auth()->user()->id)
                        <img src="{{$member->photo_location}}" alt="" class="circle">
                        
                        @if($member->role == 2)
                        <span class="title">You <span class='role'>(creator)</span></span>
                        @elseif($member->role == 1)
                        <span class="title">You <span class='role'>(admin)</span></span>
                        @else
                        <span class="title">You<span class="role"></span></span>
                        @endif
                        
                        
                        <p>{{$member->email}}</p>
                    @else
                        
                        <img id='member-image-{{$member->id}}' src="{{$member->photo_location}}" alt="" class="circle">
                        @if($member->role == 2)
                        <span class="title">{{$member->name}} <span class='role'>(creator)</span></span>
                        @elseif($member->role == 1)
                        <span class="title">{{$member->name}} <span class='role'>(admin)</span></span>
                        @else
                        <span class="title">{{$member->name}} <span class="role"></span></span>
                        @endif
                        <p>{{$member->email}}</p>

                        @if($project['role'] > 0 && $member->role != 2)
                            <div class="overlay-options none">
                        @if($member->role == 0)
                            <i title="make admin" class="material-icons member-options" id="admin-{{$member->id}}" onclick="addAdmin({{ $member->id}},this)">group_add</i>
                        @else
                            <i title="remove as admin" class="material-icons member-options" id="admin-{{$member->id}}" onclick="removeAdmin({{ $member->id}},this)">remove_circle</i>
                        @endif
                        <i title="remove from project" class="material-icons member-options" id="delete-{{$member->id}}" onclick="deleteMember({{ $member->id}},this)">delete</i>
                            </div>
                        @endif
                    @endif
                </li>    
            @endforeach
            </ul>
        @else 
            <li><a>Invite members to the project</a></li>
        @endif
  </ul>

@include('taskMemberModal')

@include('inviteProjectModal')

@include('statusModal')

@include('taskModal')

@endsection

@section('links')

<script>
    var project_id = "{!! $project['id'] !!}";
    var project_title = "{!! $project['title'] !!}";
    var memberCount = "{!! count($project['members']) !!}";
    memberCount = parseInt(memberCount);
</script>
<script type="text/javascript" src="{{ asset('js/project.js') }}"></script>

@endsection