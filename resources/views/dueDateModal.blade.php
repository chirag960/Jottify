<div class="modal fade" id="dueDateModal" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
          <div class="modal-body">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <p class="text-center">Add due Date</p>
          <form method="POST" action="/project/{{$task->project_id}}/task/{{$task->id}}/dueDate">
                @csrf
                <div class="form-group">
                    <div class="text-center">
                    <label for="date">Date
                        <input type="date" name="date" id="date" required>
                    </label>
                </div>
                <div class="text-center">
                    <label for="time">Time
                        <input type="time" name="time" id="time" required>
                    </label>
                </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="validateDueDate();">Submit</button>
          </form>
          
  <input type="text" class="datepicker">
        
          <!--div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div-->

          </div>
        </div>
      </div>
</div>

<script>
/*
$(function () {
     $('#datetimepicker1').datetimepicker();
     $("#datetimepicker1").datetimepicker("minDate", moment(new Date()))
     $("#datetimepicker1").datetimepicker("maxDate", moment().add(2,'y'))
});    
*/
/*
$(document).ready(function(){
    $('.datepicker').datepicker();
  });
*/

function validateDueDate(){
    var input_date = document.getElementById("date").value;
    var input_date = new Date(input_date);
    var today = new Date()
    console.log(today);
    console.log(input_date.setHours(0,0,0,0) < today.setHours(0,0,0,0))
    if(input_date.setHours(0,0,0,0) < today.setHours(0,0,0,0)){
        console.log("input date is before today");
    }

    var input_time = document.getElementById("time").value;
    console.log(input_time);

    var dd = String(input_date.getDate()).padStart(2, '0');
    var mm = String(input_date.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();


    date_str = yyyy + '/' + mm + '/' + dd + ' ' + input_time + ":00";
    var date_json = JSON.stringify({"date":date_str});
    console.log(date_str);
    console.log(date_json)
    loadDoc("POST","/project/" + {!! $task->project_id !!} + "/task/" +{!! $task->id !!} + "/duedate",date_json,displayDate);
    $('#dueDateModal').modal('hide');
}
</script>