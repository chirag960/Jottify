@extends('layouts.app')

@section('style-link')
<link href="{{ asset('css/task.css') }}" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('full-content')
    <div class="task-container container card-panel">
        <div class="col s12 m12 l8 xl8"> 
                <div>
                    <div onclick="redirectToProject()"><i id="back-arrow" class="material-icons align-logo pointer">arrow_back</i><span class="align-text-logo backlink">Back</span></div>
                    <h3 id="task-title">{{ $task->title }}  <!-- Make this remanable --></h3>
                </div>
                <div class="divider"></div>
                <div id="display-member-icons">
                </div>
                <div class="s12 m6 l6 xl6" style="margin-top:10px">
                <a class="btn yellow datepicker" title="Update due date"><i class="material-icons">date_range</i></a>
                @if (isset($task->due_date))
                    <span id="date_text">{{$task->due_date}}</span>
                @else
                    <span id="date_text">Add a Due Date</span>
                @endif
                <span class="invalid-feedback" id="invalidDate" role="alert"></span>
                </div>
            <div class="s12 m9 l9 xl9 description-container" id="description">
                    <h6>Description</h6>
                <input type="hidden" name="description">
                <div id="quill-container"></div>
                <span class="invalid-feedback" id="invalidDescription" role="alert"></span>
                <a class="btn light-blue" style="margin-top:5px" onclick="changeDescription()" title="Update description"><i id="description-button" class="material-icons white-text">mode_edit</i></a>
            </div>
            <hr/>
            <div class="checklist-container" id="checklist">
                <h6>CheckList</h6>
                @if($task->checklist_item_count > 0)
                {{-- {{ dd($task->checklist_item_count)}} --}}
                <div id='progress-info'> {{$task->checklist_done}} / {{$task->checklist_item_count}}</div>
                <div class="progress s12 m9 l9 xl9" id="progressBar">
                    <div id="progressBarDiv" class="determinate" role="progressbar"
                    style="width: {{ ceil(($task->checklist_done/$task->checklist_item_count)*100).'%'}}" >
                    </div>
                </div>
                @endif
                <div id="checklist-items"></div>
                <a class="btn light-blue" onclick="addItem()" title="Add items to your checklist"><i class='material-icons white-text'>playlist_add</i></a>
            </div>
            <hr/>
            <div class="attachments-header" id="attachments">
                <div class="row" style="margin-right:0">
                <h6 class="col s9 m9 l9 xl9">Attachments</h6>
                <a class="btn light-blue col right right-icon-button" onclick="openAttachmentModal()" id="" title="Add/Upload an attachment"><i class="material-icons white-text pin-icon">attach_file</i></a>
                </div>
                <div id="attachment-list" class="row attachments"></div>
            </div>
            <hr/>
        </div>
    <div class="col s12 m12 l4 xl4 sticky">
        <div id="task-buttons">
                <a class="btn red text-white" onclick="deleteTask()" title="Delete task"><i class="material-icons">delete</i></a>
                <a class="btn light-blue modal-trigger" href="#assignMemberTaskModal" title="Add or remove member from this task"><i class="material-icons white-text">group_add</i></a>
                <div id="status-form"></div>
                
        </div>
        <div class="comments-header" id="comments">
            <h6>Comments</h6>
            <div id="comment-list"></div>
            <form>
                <div class="row comment-input-div">
                <textarea class="materialize-textarea col s9 m9 l9 xl9" id="comment-message" placeholder="Add a comment" required></textarea>
                <a class="btn light-blue col right-icon-button" onclick='validateComment()' title="Send comment"><i class='material-icons white-text'>send</i></a>
                </div>

                <p class="invalid-feedback" id="invalidComment" role="alert"></p>
                
            </form> 
        </div>
    </div>
</div>

@include('inviteMember')

@include('attachmentModal')

@endsection

@section('links')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>

        var task_id = "{!! $task->id !!}";
        
        @if(isset($task->description))
        var description = {!! $task->description !!};
        @else
        var description = "";
        @endif

        @if(isset($task->due_date))
        var due_date = "{!! $task->due_date !!}";
        @else
        var due_date = "";
        
        @endif

        var project_id = "{!! $task->project_id !!}";
        var status_id = "{!! $task->status_id !!}";
        var checklist_item_count = {!! ($task->checklist_item_count==null)?0:$task->checklist_item_count !!} ;
        var checklist_done = {!! ($task->checklist_done==null)?0:$task->checklist_done !!} ;
        var attachment_count = "{!! $task->attachment_count !!}";
        var role = "{!! $task->role !!}";
        var user_id = {!! Auth::user()->id !!};
        var memberList = [];      
  
    </script>

<script type="text/javascript" src="{{ asset('js/task.js') }}"></script>

@endsection