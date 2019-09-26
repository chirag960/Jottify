<div class="modal modal-container" id="addInviteProjectModal">
    <div class="modal-content">
        <div class="modal-body">
                <i class="material-icons close modal-close">close</i>
            <h5 class="text-center">Invite members</h5>
            
            <div id="membersView"></div>    
            <form>
                @csrf
                <div class="row">
                    <div class="input-field col s12">
                        <input id="invite-members" type="text" name="invite-members" placeholder="Title" autocomplete="off">
                        <label for="invite-members">Search Members</label>
                        <span class="invalid-feedback" id="invalidInviteMembers" role="alert"></span>
                        <ul id="users-list"></ul>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="invite-message" class="materialize-textarea" required>You have been invited to the project '{{ $project['title']}}' in Jottify!</textarea>
                        <label for="invite-message">Invite Message</label>
                        <span class="invalid-feedback" id="invalidInviteMessage" role="alert"></span>
                    </div>
                </div>
                <button type="button" class="btn waves-effect waves-light light-blue" onclick="validateInvite()">Invite</button>
            </form>
        </div>    
    </div>  
</div>
