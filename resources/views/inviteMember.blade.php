<div class="modal fade" id="inviteMember" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <p class="text-center">Invite Member</p>
            <form method="POST">
            @csrf
            <div class="form-group">
                <div class="text-center">
                <label for="invite">
                    <textarea name="invite" id="invite_id" required></textarea>
                </label>
            </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="validateMember();">Submit</button>
            <div class="error" id="error-message"></div>
      </div>
    </div>
  </div>
</div>

<script>

document.getElementById("invite_id").addEventListener("keyup", throttleSeachMember(showUser, 500));

// function throttle (callback, limit) {
//     var wait = false;                  
//     return function () {               
//         if (!wait) {                   
//             showUser(document.getElementById("invite_id").value);
//             wait = true;               
//             setTimeout(function () {   
//                 wait = false;          
//             }, limit);
//         }
//     }
// }

function throttleSeachMember(fn, threshhold) {
  var last,deferTimer;
  return function () {
    var now = +new Date,
        args = arguments;
    if (last && now < last + threshhold) {
      // hold on to it
      clearTimeout(deferTimer);
      deferTimer = setTimeout(function () {
        last = now;
        fn(document.getElementById("invite_id").value);
      }, threshhold);
    } else {
      last = now;
      fn(document.getElementById("invite_id").value);
    }
  };
}


function showUser(pattern){
    if(pattern.length > 2){
        loadDoc("GET","/project/"+ {!! $project['id'] !!} + "/allMembers",null,showUserDropDown);
    }
    else{
        //remove the menu
    }
}

function showUserDropDown(xhttp){
    console.log(JSON.parse(xhttp.responseText));
}

function validateMember(){

}
</script>