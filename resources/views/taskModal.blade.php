<div class="modal modal-container" id="addTaskModal">
        <div class="modal-content">
            <div class="modal-body">
                <i class="material-icons close modal-close">close</i>  
                <h5 class="text-center">Create new Task</h5>
                  
                  
              <form>
                    <div class="row">
                        <div class="input-field col s12">
                        <input id="task-title" type="text" name="title" placeholder="Title" autocomplete="off" required >
                        <label for="task-title">Title</label>
                        <span class="invalid-feedback" id="invalidTaskTitle" role="alert"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12" id="status_order_in_task">
                        </div>
                    </div>
                    <button type="button" class="btn waves-effect waves-light light-blue" onclick="validateTask()">Create</button>
              </form>
              </div>              
            </div>  
    </div>