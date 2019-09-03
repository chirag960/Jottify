<div class="modal fade" id="addAttachmentModal" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
          <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <p class="text-center">Add Attachment</p>
                <div class="" >
                    <p data-toggle="collapse" data-target="#attachFile" class="pointer">Select from computer :</p>        
                    <div id="attachFile">
                        <form method="POST" id="fileCatcher" enctype="multipart/form-data">
                        @csrf
                            <input type="file" id="fileInput" accept="image/*,.pdf,.doc,.rtf,.txt,.xlsx" multiple>
                            <button type="button" class="btn" id="submit_button" onclick="submit_files()">Submit</button>
                        </form>
                    </div>
                    <p class="text-center">OR</p>
                    <p class="pointer" data-toggle="collapse" data-target="#attachLink">Add a link:</p>
                    <div id="attachLink" class="collapse">
                    <input class="form-control" id="url" type="url" size="30">
                    <button type="button" class="btn" onclick='validateLink()'>Attach</button>
                    </div>
                </div>
                <div class="error" id="error-message"></div>
          </div>
        </div>
      </div>
</div>

<script>

function submit_files(){
    var fileList = document.getElementById("fileInput").files;
    var fileLength =fileList.length;
    var formData = new FormData();

    Array.prototype.forEach.call(fileList, file => {
        formData.append("files[]",file);
        console.log(file);
    });

    /*
    fileList.forEach(function(element){
        formData.append("files",element);
    });
    */

    //console.log("This is formdata:" + formData);
    //var files_text = JSON.stringify({"files":files});
    loadDoc("POST2","/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/attachment",formData,updateAttachment);
}

function validateLink(){

}

</script>