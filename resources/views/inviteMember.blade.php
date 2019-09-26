<div class="modal modal-container" id="assignMemberTaskModal">
      <div class="modal-content">
      <div class="modal-body">
        <i class="material-icons close modal-close">close</i>
        <h5 class="text-center">Add members</h5>
            <form>
            <div class="input-field col s12" id="search-member-div">
                <input placeholder="Search Members" id="member_pattern" type="text" autocomplete="off" onkeyup="searchMembers()">
                <label for="member_pattern">Search Members</label>
              </div>
          <div id="members-list">

          </div>
            <button type="button" class="btn btn-primary" onclick="assignMember();">Submit</button>
            <div class="error" id="error-message"></div>
            </form>
      </div>
    </div>
  </div>
