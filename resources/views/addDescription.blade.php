<div class="modal fade" id="addDescriptionModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          @if (!isset($task->description))
          <p class="text-center">Add Description</p>
            <form method="POST">
            @csrf
            <div class="form-group">
                <div class="text-center">
                <label for="description">
                    <textarea name="description" id="description_id" required></textarea>
                </label>
            </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="validateDescription('post');">Submit</button>
            
            @else
            <p class="text-center">Change Description</p>
            <form method="POST">
            @method('patch')
            @csrf
            <div class="form-group">
                <div class="text-center">
                <label for="description">
                    <textarea name="description" id="description_id" required></textarea>
                </label>
            </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="validateDescription('patch');">Submit</button>
            
            @endif
            <div class="error" id="error-message"></div>
      </div>
    </div>
  </div>
</div>

<script>
function validateDescription(method){
var description_text = document.getElementById("description_id").value;
if(description_text.length > 255){
    document.getElementById("error-message").innerHTML = "*Length of description should not be more than 255";
}
else if(description_text.length < 1){
    document.getElementById("error-message").innerHTML = "*Description cannot be empty";
}
else{
    var description_text_json = JSON.stringify({"message":description_text});
    console.log(description_text_json);
    loadDoc(method,"/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/description",description_text_json,updateDescription);
    $('#addDescriptionModal').modal('hide');
}

}
</script>