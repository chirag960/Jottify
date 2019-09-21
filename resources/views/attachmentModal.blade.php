<div class="modal modal-container" id="addAttachmentModal">
  <div class="modal-content">
    <div class="modal-body">
        <i class="material-icons close modal-close">close</i>  
        <h5 class="text-center">Add Attachment</h5>
          <div class="" >
              <p>Select from computer :</p>        
              <div id="attachFile">
                  <form method="POST" id="fileCatcher" enctype="multipart/form-data">
                  @csrf
                  <div class="file-field input-field">
                      <div class="btn light-blue">
                        <span>File</span>
                        <input type="file" id="fileInput" accept="image/*,.pdf,.doc,.rtf,.txt,.xlsx" multiple autocomplete="off">
                      </div>
                      <div class="file-path-wrapper">
                          <input class="file-path validate" type="text" placeholder="Upload one or more files" autocomplete="off">
                        </div>
                        
                    </div>
                      <button type="button" class="btn light-blue" id="submit_button" onclick="submit_files()">Submit</button>
                      <span class="invalid-feedback" id="invalidAttachment" role="alert"></span>
                  </form>
              </div>
          </div>
    </div>
  </div>
</div>

<script>

// function submit_files(){
//     var fileList = document.getElementById("fileInput").files;
//     var fileLength =fileList.length;
//     var formData = new FormData();

//     Array.prototype.forEach.call(fileList, file => {
//         formData.append("files[]",file);
//         console.log(file);
//     });

//     loadDoc("POST2","/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/attachment",formData,updateAttachment);
// }

// function validateLink(){

// }

</script>