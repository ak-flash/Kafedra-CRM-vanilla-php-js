<script>

function mask(id, event,next) {
    var element = $("#" + id);
    var len = element.val().length + 1;
    var max = element.attr("maxlength");

    var cond = (48 < event.which && event.which < 53) || (48 < event.keyCode && event.keyCode < 53|| event.keyCode==8);
    
    if (!(cond && len <= max)) {
        event.preventDefault();
        
        return false;
    }
    else $("#" + next).focus();
}

function loadTopicList(semester, course_id){

var data = {
  "semester" : semester,
  "course_id" : course_id,
}

$.post('api/topics/listshort', JSON.stringify(data))
  .done(function(data) {
    $('#topic_id').empty();
    $('#topic_id').append('<option value="">Выберите...</option>');
    
    $.each(data["topics"], function(key, val) {
        if(val.t_name.length>100) val.t_name = val.t_name.substr(0,50) + " ...";
        $('#topic_id').append('<option value="' + val.id + '">' + val.t_name + '</option>');
    });
  });
}

loadCoursesList(1);

function loadQuizKeyVersion(){

  var data = {
    "topic_id" : $('#topic_id').val()
  }

  $.post('api/quiz_checkers/load_versions', JSON.stringify(data))
    .done(function(data) {
      $('#version_id').empty();

      $.each(data["key_versions"], function(key, val) {
          $('#version_id').append('<option value="' + val.version + '">' + val.version + '</option>');
      });
    });
}

function loadQuizKeyVariant(){

var data = {
  "topic_id" : $('#topic_id').val(),
  "version_id" : $('#version_id').val(),
}

$.post('api/quiz_checkers/load_variants', JSON.stringify(data))
  .done(function(data) {
    $('#variant_id').empty();
    $('#variant_id').append('<option value="">...</option>');
    
    $.each(data["key_variants"], function(key, val) {
        $('#variant_id').append('<option value="' + val.variant + '">' + val.variant + '</option>');
    });
  });
}

function loadQuizKey(){

var data = {
  "topic_id" : $('#topic_id').val(),
  "version_id" : $('#version_id').val(),
  "variant_id" : $('#variant_id').val(),
}

$.post('api/quiz_checkers/load', JSON.stringify(data))
  .done(function(data) {
    toastr.success(data.message);
  })
  .fail(function(data) {
    toastr.error(data.responseJSON['message']);
  });
}

$(function() {
$("form").submit(function(event) {
  event.preventDefault();
  var data = $('form').serializeArray();
  
  $('#btn_details').prop({'disabled' : false});

  $.ajax({
        type: "POST",
        url: "api/objects/test_checker.php?action=check",
        dataType: 'json',
            data: data,
            error: function (result) {
              toastr.error(result.responseText);
            },
            success: function (result) { 
                if (result['status'] == true) {
                  if(result['result']<60) color="text-danger"; else color="text-success";
                  $('#checker_result1').html('<b class="'+color+'">'+result['result']+'</b>');
                  $('#checker_result2').html('<h2 class="d-inline">'+result['result_info']+'</h2> из 10');
                }
                else {
                  
                  if(result['message']=='Загружено') {
                    if(result['empty']==0) toastr.error('Внимание! Часть ответов не заполнены'); else toastr.success(result['message']);
                    $('#btn_check').prop({'disabled' : false});
                  } else toastr.error(result['message']);
                  
                    }
            }
    
    }); 
    });  
  }); 

  function resetCheckForm(){
    $('#btn_details').prop({'disabled' : true});
    
    $('#checker_result1').html('');$('#checker_result2').html('');
    $('#q1').val('');$('#q2').val('');$('#q3').val('');$('#q4').val('');$('#q5').val('');
    $('#q6').val('');$('#q7').val('');$('#q8').val('');$('#q9').val('');$('#q10').val('');
  }

  function changeQuestionPart(v){
    if(v==1){
        $('#ql1').html('1');$('#ql2').html('2');$('#ql3').html('3');$('#ql4').html('4');$('#ql5').html('5');
        $('#ql6').html('6');$('#ql7').html('7');$('#ql8').html('8');$('#ql9').html('9');$('#ql10').html('10');
    } else {
        $('#ql1').html('11');$('#ql2').html('12');$('#ql3').html('13');$('#ql4').html('14');$('#ql5').html('15');
        $('#ql6').html('16');$('#ql7').html('17');$('#ql8').html('18');$('#ql9').html('19');$('#ql10').html('20');  
    }
        $('form').submit();
}


</script>
  <form id="send_test_answ" action="#" method="POST">
     
      <input type="hidden" class="form-control" name="semester" id="semester" value="1">
      <input type="hidden" name="course_id" id="course_id" value="0">
      <input type="hidden" class="form-control" name="question_part" id="question_part" value="1">

      <!-- Main content -->
      <section class="content mt-2">
  
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <!-- Default box -->
              <div class="card">
                <div class="card-header bg-dark">
                
                    <h4 class="d-inline pl-3 font-weight-bold">Проверка бланков тестов</h4>
                 

                  <div class="card-tools float-right">
                      <labels>Семестр:&nbsp;&nbsp;</label>
                          <div class="btn-group btn-group-toggle" data-toggle="buttons">
                              <label class="btn btn-primary btn-sm active">
                                <input type="radio" name="options" id="semester1" onchange="loadCoursesList(1);$('#semester').val(1);$('#topics_table').html('');$('#topic_id').val(0).change();" autocomplete="off" checked value="1"> осенний
                              </label>
                              <label class="btn btn-primary btn-sm">
                                <input type="radio" name="options" id="semester2" onchange="loadCoursesList(2);$('#semester').val(2);$('#topics_table').html('');$('#topic_id').val(0).change();" autocomplete="off" value="2"> весенний
                              </label>
                          </div>

                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                      <i class="fas fa-minus"></i></button>
                   
                  </div>
                </div>
                <div class="card-body">
                  
                    

                      
 <div class="row">

    <div class="col-3">
      
        <div class="form-group">
            <label for="faculty">Дисциплина</label>
            <select class="custom-select" name="faculty" id="faculty" onchange="$('#quizkeys_table').html('');$('#topic_id').empty();loadTopicList($('#semester').val(), $(this).val());$('#course_id').val($(this).val());">

            </select>
          </div>
    
    </div>
    <div class="col-1">
      
      <div class="form-group">
          <label for="variant">Вариант</label>
          <select class="custom-select" name="variant_id" id="variant_id" onchange="loadQuizKey()">
            
          </select>
        </div>
  
  </div>
    <div class="col-4">
      
      <div class="form-group">
        <label for="topic">Тема занятия</label>
        <select class="custom-select" name="topic_id" id="topic_id" onchange="loadQuizKeyVersion()">
        
        </select>
      </div>
    
    </div>

   
    
        <div class="col-1">
      
      <div class="form-group float-right">
          <label for="version">Версия</label>
          <select class="custom-select" name="version_id" id="version_id" onchange="loadQuizKeyVariant()">

          </select>
        </div>
  
  </div>

        <div class="col-3 text-center pt-4">
            
          <labels>Вопросы:&nbsp;&nbsp;</label>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-info active">
                  <input type="radio" name="options" id="question_part1" onchange="$('#question_part').val(1);changeQuestionPart(1);" autocomplete="off" checked value="1"> 1 - 10
                </label>
                <label class="btn btn-info">
                  <input type="radio" name="options" id="question_part2" onchange="$('#question_part').val(2);changeQuestionPart(2);" autocomplete="off" value="2" disabled> 11 - 20
                </label>
            </div>

            </div>



    </div>
    </div>
  </div>

<div class="card-footer">
                  
  <div class="row">
   <div class="col-4 d-inline pl-4">
      <h2 class="d-inline"><b>Результат</b> -  <div id="checker_result1" class="d-inline display-4"></div>%</h2>
    </div>
   <div class="col-1 text-center"> 
      <label id="ql1">1</label>
      <input size="1" type="number" maxlength="1" onkeypress="mask(this.id, event,'q2')" class="form-control" name="q1" id="q1" min="0" max="4" required>
   </div>
   <div class="col-1 text-center"> 
      <label id="ql2">2</label>
      <input size="1" type="number" maxlength="1" onkeypress="mask(this.id, event,'q3')" class="form-control" name="q2" id="q2" min="0" max="4" required>
   </div>
   <div class="col-1 text-center"> 
      <label id="ql3">3</label>
      <input size="1" type="number" maxlength="1" onkeypress="mask(this.id, event,'q4')" class="form-control" name="q3" id="q3" min="0" max="4" required>
   </div>
   <div class="col-1 text-center"> 
      <label id="ql4">4</label>
      <input size="1" type="number" maxlength="1" onkeypress="mask(this.id, event,'q5')" class="form-control" name="q4" id="q4" min="0" max="4" required>
   </div>
   <div class="col-1 text-center"> 
      <label id="ql5">5</label>
      <input size="1" type="number" maxlength="1" onkeypress="mask(this.id, event,'q6')" class="form-control" name="q5" id="q5" min="0" max="4" required>
   </div>
   <div class="col-3">
      <br>
      <button type="submit" id="btn_check" class="btn btn-primary btn-lg" disabled onclick="$('form').submit();">Проверить</button>
   </div>
   
</div>

<br>

<div class="row">
        <div class="col-4 d-inline pl-4">
          <h4 class="d-inline">правильных:</h4> <div id="checker_result2" class="d-inline"></div></div>
        <div class="col-1 text-center"> 
          <label id="ql6">6</label><input size="1" type="number" maxlength="1" onkeypress="mask(this.id, event,'q7')" class="form-control" name="q6" id="q6" min="0" max="4" required></div>
        <div class="col-1 text-center"> 
          <label id="ql7">7</label><input size="1" type="number" maxlength="1" onkeypress="mask(this.id, event,'q8')" class="form-control" name="q7" id="q7" min="0" max="4" required></div>
        <div class="col-1 text-center"> 
          <label id="ql8">8</label><input size="1" type="number" maxlength="1" onkeypress="mask(this.id, event,'q9')" class="form-control" name="q8" id="q8" min="0" max="4" required></div>
        <div class="col-1 text-center"> 
          <label id="ql9">9</label><input size="1" type="number" maxlength="1" onkeypress="mask(this.id, event,'q10')" class="form-control" name="q9" id="q9" min="0" max="4" required></div>
        <div class="col-1 text-center"> 
          <label id="ql10">10</label><input size="1" type="number" maxlength="1" onkeypress="mask(this.id, event,'submit')" class="form-control" name="q10" id="q10" min="0" max="4" required onchange="$('#btn_check').prop({'disabled' : false})"></div>
        <div class="col-3">
          <br>
        
        <?php
          if($jwt_response->data->group=='admin' || $jwt_response->data->group=='instructor') {
              echo '<button type="button" data-toggle="modal" id="btn_details" data-target="#detailsModal" class="btn btn-outline-success btn-lg" disabled>Детали</button>';
          }
        ?>

        </div>
        
     </div>

     <br> 
     <button type="button" class="btn btn-secondary btn-sm" onclick="resetCheckForm();">Очистить</button>    

                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

    </form>



<div id="detailsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="detailsModalLabel">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="checker_result_modal">
                    
                    <h1>70%</h1>
              <p>This <a href="#" role="button" class="btn btn-secondary popover-test" title="Popover title" data-content="Popover body content is set in this attribute." data-container="#detailsModal">button</a> triggers a popover on click.</p>
              <hr />
              <h5>Tooltips in a modal</h5>
              <p><a href="#" class="tooltip-test" title="Tooltip" data-container="#detailsModal">This link</a> and <a href="#" class="tooltip-test" title="Tooltip" data-container="#detailsModal">that link</a> have tooltips on hover.</p>
            </div>
            
            
            <div class="modal-footer">
              <button type="button" class="btn btn-primary mr-3">Сохранить результат</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
              
            </div>
          </div>
        </div>
      </div>


       