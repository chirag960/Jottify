<div class="modal modal-container" id="addStatusModal">
    <div class="modal-content">
           
        <div class="modal-body">
            <i class="material-icons modal-close close">close</i> 
            <h5 class="text-center">Create new Status</h5>
               
              
          <form method="POST" action="/project/{{$project['id']}}/status">
                @csrf
                <div class="row">
                    <div class="input-field col s12">
                    <input id="status-title" type="text" name="title" placeholder="Title" autocomplete="off" required >
                    <label for="status-title">Title</label>
                    <span class="invalid-feedback" id="invalidStatusTitle" role="alert"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12" id="status_order">
                    </div>
                </div>
                <button type="button" class="btn waves-effect waves-light light-blue" onclick="validateStatus()">Create</button>
          </form>
          </div>
          
        </div>  
</div>