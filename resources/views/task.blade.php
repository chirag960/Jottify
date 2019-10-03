@extends('layouts.app')

@section('style-link')
<link href="{{ asset('css/task.css') }}" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endsection

@section('full-content')
    <div class="task-container container card-panel">
        <div class="col s12 m12 l8 xl8"> 
                <div>
                    <h3 id="task-title">{{ $task->title }}  <!-- Make this remanable --></h3>
                </div>
                <div>
                    <p>Members</p>
                    <div class="row">
                    <div id="member-avatars" class="s6 m6 l6 xl6">

                    </div>
                    </div>
                </div>
                <div class="s12 m6 l6 xl6">
                <a class="btn yellow datepicker"><i class="material-icons">date_range</i></a>
                @if (isset($task->due_date))
                    <span id="date_text">{{$task->due_date}}</span>
                @else
                    <span id="date_text">Add a Due Date</span>
                @endif
                <span class="invalid-feedback" id="invalidDate" role="alert"></span>
                </div>
            <div class="s12 m12 l12 xl12 description-container" id="description">
                    <h6>Description</h6>
                <input type="hidden" name="description">
                <div id="quill-container"></div>
                <span class="invalid-feedback" id="invalidDescription" role="alert"></span>
                <button type="button" class="btn light-blue" id="description-button" onclick="changeDescription()">Update Description</button>
            </div>
            <hr/>
            <div class="checklist-container" id="checklist">
                <h6>CheckList</h6>
                @if($task->checklist_item_count > 0)
                {{-- {{ dd($task->checklist_item_count)}} --}}
                <div id='progress-info'> {{$task->checklist_done}} / {{$task->checklist_item_count}}</div>
                <div class="progress s12 m6 l6 xl6" id="progressBar">
                    <div id="progressBarDiv" class="determinate" role="progressbar"
                    style="width: {{ ceil(($task->checklist_done/$task->checklist_item_count)*100).'%'}}" >
                    </div>
                </div>
                @endif
                <div id="checklist-items"></div>
                <button type="button" class="btn light-blue" onclick="addItem()">Add Items</button>
            </div>
            <hr/>
            <div class="attachments-header" id="attachments">
                <h6>Attachments</h6>
                <button type="button" class="btn light-blue" onclick="openAttachmentModal()">Add Attachment</button>
                <div id="attachment-list" class="row attachments"></div>
            </div>
            <hr/>

            <!--div class="right-side">
                <p>Move to another status</p>
                <form method="POST">
                    @csrf
                    @method('patch')
                    <div class="form-group">
                        <select name="status" id="statusList">
                        </select>
                    </div>
                    <button type="button" class="btn light-blue" onclick="validateStatus();">Submit</button>
                </form>
            </div-->
        </div>
    <div class="col s12 m12 l4 xl4 sticky">
        <div id="task-buttons">
                <a class="btn red text-white" onclick="deleteTask()"><i class="material-icons">delete</i></a>
                <a class="btn light-blue modal-trigger" href="#assignMemberTaskModal"><i class="material-icons">people</i><span>assign members</span></a>
                <div id="status-form"></div>
                
        </div>
        <div class="comments-header" id="comments">
            <h6>Comments</h6>
            <div id="comment-list"></div>
            <form>
                <textarea class="materialize-textarea" id="comment-message" placeholder="Add a comment" required></textarea>
                <span class="invalid-feedback" id="invalidComment" role="alert"></span>
                <button type="button" onclick='validateComment()' class="btn light-blue">Send</button>
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

        var project_id = "{!! $task->project_id !!}";
        var status_id = "{!! $task->status_id !!}";
        var checklist_item_count = {!! ($task->checklist_item_count==null)?0:$task->checklist_item_count !!} ;
        var checklist_done = {!! ($task->checklist_done==null)?0:$task->checklist_done !!} ;
        var attachment_count = "{!! $task->attachment_count !!}";
        var role = "{!! $task->role !!}";
        var user_id = {!! Auth::user()->id !!};
        
  
    </script>

<script type="text/javascript" src="{{ asset('js/task.js') }}"></script>

@endsection