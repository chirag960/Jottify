<div class="modal fade" id="addItemModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <p class="text-center">Add Item to checklist</p>
            <form method="POST">
            @csrf
            <div class="form-group">
                <div class="text-center">
                <label for="item_list">
                    <input type="text" name="item_list" id="item_name" required>
                </label>
            </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="validateItemName();">Submit</button>
            <div class="error" id="error-message"></div>
      </div>
    </div>
  </div>
</div>

<script>
function validateItemName(){
var item_text = document.getElementById("item_name").value;
if(item_text.length > 255){
    document.getElementById("error-message").innerHTML = "*Length of item should not be more than 255";
}
else if(item_text.length < 1){
    document.getElementById("error-message").innerHTML = "*item name cannot be empty";
}
else{
    var item_text_json = JSON.stringify({"message":item_text});
    console.log(item_text_json);
    loadDoc("POST","/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/checklist",item_text_json,displayaddedItem);
    document.getElementById("item_name").value = null;
    $('#addItemModal').modal('hide');
}

}
</script>