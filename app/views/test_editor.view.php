<!-- page script 
  "<td>"+((data[question].topic == 0)? "1": "Female")+"</td>"+ 
 | <a href='#' onClick=Quiz_remove('"+data[question].id+"')><i class='fas fa-eraser'></i></a>
  onchange="if($(this).val()=='') {$(this).removeClass('is-valid');$(this).addClass('is-invalid');} else {$(this).removeClass('is-invalid');$(this).addClass('is-valid');}"

$(\'#forward_page\').addClass(\'disabled\');
-->

<input type="hidden" class="form-control" name="items_per_page" id="items_per_page" value="10">
<input type="hidden" class="form-control" name="num_page" id="num_page" value="1">

<script>
toastr.options = {
  "closeButton": false,
  "progressBar": true
}


function loadQuiz(page, filter, filter_value){
          var items_per_page=$('#items_per_page').val();
          $('#num_page').val(page);
          $.ajax({
              type: "GET",
              url: "api/objects/quiz/quiz_read.php?token="+$('#token').val()+"&limit="+items_per_page+"&page="+page+"&filter="+filter+"&filter_value="+filter_value,
              dataType: 'json',
              success: function(data) {
                
                var response="";
                  for(var question in data){
                      if(data[question].id!=undefined){
                          response += "<tr>"+
                      "<th scope='row'><a href='#' onclick='loadQuiz_id("+data[question].id+");'>"+data[question].id+"</a></th>"+
                      "<td>"+data[question].question+"</td>"+
                      "<td>"+data[question].good+"</td>"+
                      "<td>"+data[question].bad1+"</td>"+
                      "<td>"+data[question].bad2+"</td>"+
                      "<td>"+data[question].bad3+"</td>"+
                      
                      "<td><a href='#' onclick='loadQuiz_id("+data[question].id+");'><i class='fas fa-wrench'></i></a></td>"+
                      "</tr>";
                      } else 
                      {
                        $("#quiz_count").html(data[question].quiz_count);
                    
                        $("#pagination").html('<li class="page-item disabled" id="back_page"><a class="page-link" href="#" aria-label="Назад" onclick="loadQuiz('+(page-1)+', 0, 0);"><span aria-hidden="true">&laquo;</span></a></li>');
                        
                        let i = 1;cl="";last_page=Math.ceil(data[question].quiz_count/items_per_page);
while (i <= last_page) { 
  if(i==page)  cl=" active"; else cl="";
  $("#pagination").append('<li class="page-item'+cl+'"><a class="page-link" href="#" onclick="loadQuiz('+i+', 0, 0);">'+i+'</a></li>');
  i++;
}
                        $("#pagination").append('<li class="page-item" id="forward_page"><a class="page-link" href="#" aria-label="Далее" onclick="loadQuiz('+(page+1)+', 0, 0);"><span aria-hidden="true">&raquo;</span></a></li>');
 
                        if(page==1) $('#back_page').addClass('disabled'); else $('#back_page').removeClass('disabled');
                        if(page==last_page) $('#forward_page').addClass('disabled'); else $('#forward_page').removeClass('disabled');
                        
                        
                      }

                  }
                  $("#quiz_table").html(response);
                  
              }
          });
        }
        
 
        loadQuiz(1, 0, 0);

        function loadQuiz_id(id){
          $.ajax({
              type: "GET",
              url: "api/objects/quiz/quiz_read.php?token="+$('#token').val()+"&id="+id,
              dataType: 'json',
              success: function(data) {
           
                $("#quiz_id").val(data['id']);
                $("#quiz_question").val(data['question']);
                $("#quiz_good").val(data['good']);
                $("#quiz_bad1").val(data['bad1']);
                $("#quiz_bad2").val(data['bad2']);
                $("#quiz_bad3").val(data['bad3']);
                $("#quiz_topic1").val(data['tag_topic1']);
                $("#quiz_topic2").val(data['tag_topic2']);
                if(data['tag_lech']==1) {$('#tag_lech').val(1);$('#tag_lech')[0].checked = true;} else $('#tag_lech')[0].checked = false;
                if(data['tag_ped']==1) {$('#tag_ped').val(1);$('#tag_ped')[0].checked = true;} else $('#tag_ped')[0].checked = false;
                if(data['tag_lecheng']==1) {$('#tag_lecheng').val(1);$('#tag_lecheng')[0].checked = true;} else $('#tag_lecheng')[0].checked = false;
                if(data['tag_stom']==1) {$('#tag_stom').val(1);$('#tag_stom')[0].checked = true;} else $('#tag_stom')[0].checked = false;
                if(data['tag_stomeng']==1) {$('#tag_stomeng').val(1);$('#tag_stomeng')[0].checked = true;} else $('#tag_stomeng')[0].checked = false;
                if(data['tag_mpd']==1) {$('#tag_mpd').val(1);$('#tag_mpd')[0].checked = true;} else $('#tag_mpd')[0].checked = false;
                if(data['tag_mbf3']==1) {$('#tag_mbf3').val(1);$('#tag_mbf3')[0].checked = true;} else $('#tag_mbf3')[0].checked = false;
                if(data['tag_mbf4']==1) {$('#tag_mbf4').val(1);$('#tag_mbf4')[0].checked = true;} else $('#tag_mbf4')[0].checked = false;
                $('#detailsModalLabel').html('Редактировать вопрос №<b>'+data['id']+'</b>');
                $('#action').val('update');
                $('#detailsModal').modal();
              }
          });
        }


$(function() {
$("form").submit(function(event) {
  event.preventDefault();
  var data = $('form').serializeArray();
  
  if($('#quiz_topic1').val()!=''&&$('#quiz_topic1').val()!=0){
  $('#btn_save').prop({'disabled' : true});

  $.ajax({
        type: "POST",
        url: "api/objects/quiz/quiz_edit.php?token="+$('#token').val()+"&action="+$('#action').val(),
        dataType: 'json',
            data: data,
            error: function (result) {
              toastr.error(result.responseText);
            },
            success: function (result) { 
                if (result['status'] == true) {
                    loadQuiz($('#num_page').val(), 0, 0);
                    $('#detailsModal').modal('hide');
                    $('#btn_save').prop({'disabled' : false});
                    resetQuizForm();
                    toastr.success(result['message']);
                }
                else {
                  toastr.error(result['message']);

                    }
            }
    
    }); 
  } else {
    toastr.error('Укажите тему занятий!');
    
  } 
    }); 
 
  }); 

  function resetQuizForm(){
    $('#save_quiz')[0].reset();
    $('#tag_lech').val(0);$('#tag_lecheng').val(0);$('#tag_ped').val(0);
    $('#tag_stom').val(0);$('#tag_stomeng').val(0);$('#tag_mpd').val(0);
    $('#tag_mbf3').val(0);$('#tag_mbf4').val(0);$('#quiz_id').val(0);
  }


 function deleteQuiz(){
  var result = confirm("Вы уверены что хотите удалить вопрос?"); 
    if (result == true) {     
	 var data = $('form').serializeArray();
     $.ajax({
        type: "POST",
        url: "api/objects/quiz/quiz_edit.php?token="+$('#token').val()+"&action="+$('#action').val(),
        dataType: 'json',
            data: data,
            error: function (result) {
              toastr.error(result.responseText);
            },
            success: function (result) { 
                if (result['status'] == true) {
                    loadQuiz($('#num_page').val(), 0, 0);
                    $('#detailsModal').modal('hide');
                    $('#btn_save').prop({'disabled' : false});
                    resetQuizForm();
                    toastr.success(result['message']);
                }
                else {
                  toastr.error(result['message']);

                    }
            }
    
    }); 
   
  }
  
}

loadFaculty(1);

function loadFaculty(){
  $.getJSON('api/objects/faculty.php?p=list', function(data) {
          $.each(data, function(key, val) {
              $('#t_faculty_tags').append('<option value="' + key + '">' + val + '</option>');
            });
      });
}


  $(document).ready(function() {
    $('.select2').select2();
});

</script>


 <!-- Main content -->
 <section class="content mt-2">
        <div class="card">
                <div class="card-header bg-light">
                    
                  <h4 class="d-inline w-75 pl-3 font-weight-bold">Редактирование тестов</h4>
                    
                        
                    
                    
                 <div class="float-right">
                    
                      
                    <button type="button" class="btn btn-success mr-3" onclick="$('#action').val('create');$('#detailsModalLabel').html('Добавить вопрос для теста');$('#detailsModal').modal();"><i class="fas fa-plus-square mr-2"></i> Вопрос</button>
                    
                    <button type="button" id="btn_refresh" class="btn btn-info" onclick="loadQuiz(1, 0, 0);" disabled><i class="fas fa-sync"></i> </button>

                  
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select class="custom-select" style="width:65px;" name="items" id="items" onchange="$('#items_per_page').val($(this).val());loadQuiz(1, 0, 0);">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                      </select>
                  
                  </div>
                    

                  </div>
            </div>






                    <table class="table table-bordered table-hover">
                      <thead>
                      <tr class="table-secondary">
                        <th scope="col">№</th>
                        <th scope="col" class="w-75 p-3">Вопрос</th>
                        <th scope="col" class="text-success"><b>Ответ+</b></th>
                        <th scope="col" class="text-danger">Ответ-</th>
                        <th scope="col" class="text-danger">Ответ-</th>
                        <th scope="col" class="text-danger">Ответ-</th>
                        <th scope="col"></th>
                      </tr>
                      </thead>
                      <tbody id="quiz_table">
                      </tbody>
                      <tfoot>
                   
                      <tr class="table-light">
                          <th colspan="2"><h4>Всего: <div id="quiz_count" class="d-inline font-weight-bold"></div></h4></th>
                            <th colspan="5">
                              
                                <nav aria-label="Page navigation">
                                <ul class="pagination" id="pagination"></ul>
                              </nav>

                                </th>
                      </tr>
                      </tfoot>
                    </table>
               
        </section>

<form id="save_quiz" action="#" method="POST"> 
        <div id="detailsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header table-secondary">
                      <h5 class="modal-title" id="detailsModalLabel">Modal title</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="resetQuizForm()">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="checker_result_modal">
                            
                        
                            <input type="hidden" class="form-control" name="quiz_id" id="quiz_id" value="0">
                            <input type="hidden" class="form-control" name="action" id="action" value="0">

                            <textarea class="form-control" name="quiz_question" id="quiz_question" value="" rows="2"></textarea>
                           
                            
                              <input type="text" class="form-control" name="quiz_good" id="quiz_good" value="">
                            <input type="text" class="form-control" name="quiz_bad1" id="quiz_bad1" value="">
                            <input type="text" class="form-control" name="quiz_bad2" id="quiz_bad2" value="">
                            <input type="text" class="form-control" name="quiz_bad3" id="quiz_bad3" value="">
                        
                            <div class="input-group pt-2">
                                    <label class="pt-2">Темы</label>&nbsp;&nbsp;
                                    <select class="custom-select" name="quiz_topic1" id="quiz_topic1">
                                        <option value="">...</option>
                                      <option value="2">весенний</option>
                                    </select>
                                    &nbsp;&nbsp;
                                    <select class="custom-select" name="quiz_topic2" id="quiz_topic2">
                                        <option value="0">...</option>
                                      <option value="2">весенний</option>
                                    </select>
                                  </div>
                    
                                  <div class="row p-2">
                                    <div class="col-4 font-bold">Для факультетов:</div>
                                    <div class="col">
                                      <select class="select2 form-control" name="t_faculty_tags[]" multiple="multiple" id="t_faculty_tags">
                                      </select>
                                    </div>
                                  </div>
                                                   
                 
                    <div class="modal-footer" style="display:inline-block;width:100%;";>
                      <button type="button" class="btn btn-danger" style="float:left;" id="btn_delete" onclick="$('#action').val('delete');delete_question();">Удалить</button>
					  
                      <button type="button"   style="float:right;" class="btn btn-info" data-dismiss="modal" onclick="resetQuizForm()">Закрыть</button>&nbsp;&nbsp;&nbsp;
                      <button type="submit" class="btn btn-primary"   style="float:right;"  id="btn_save">Сохранить</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
</form>