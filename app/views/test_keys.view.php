<!-- page script "<td>"+((data[question].topic == 0)? "1": "Female")+"</td>"+ -->
<script>

function loadQuizKeys(topic_id){
  $('#btn_generate').prop({'disabled' : false}); 
  $('#btn_print').prop({'disabled' : false});
  $('#print_version').html('');
  $('#spinnerModal').modal('show');
  var data = {
    "topic_id" : topic_id,
    "question_part" : $('#question_part').val(),
  }

      $.ajax({
              type: "POST",
              url: "api/quiz_keys/list",
              dataType: 'json',
              data: JSON.stringify(data),
              success: function(data) {

                let quiz_keys_array = data['quiz_keys'];
     

                if($('#question_part').val()==1) {
                  var response="<thead><tr class='table-secondary text-center'><th>Ver</th><th>Вар.№</th><th>В1</th><th>В2</th><th>В3</th><th>В4</th><th>В5</th><th>В6</th><th>В7</th><th>В8</th><th>В9</th><th>В10</th><th>Ред.</th></tr></thead><tbody>";
                }
                
                if($('#question_part').val()==2) {
                  var response="<thead><tr class='table-secondary text-center'><th>Ver</th><th>Вар.№</th><th>В11</th><th>В12</th><th>В13</th><th>В14</th><th>В15</th><th>В16</th><th>В17</th><th>В18</th><th>В19</th><th>В20</th><th>Ред.</th></tr></thead><tbody>";
                }
              
                if(data['quiz_keys']=='')  {
                  response += "<tr class='text-danger text-center font-weight-bold'><td colspan='12'>Не найдено ни одного варианта</td></tr></tbody>";
                  $("#quizkeys_table").html(response);
                  version = 0;
                } else {
        
        for(let quiz_key in data['quiz_keys']){
          
          if(version != quiz_keys_array[quiz_key].version) {
            var version = quiz_keys_array[quiz_key].version;
            $('#print_version').append('<option value="' + version + '">' + version + '</option>');
          }

        response += "<tr class='text-center'>"+
        "<td title='Создано (отредактировано) - "+quiz_keys_array[quiz_key].updated_at+" пользователем - "+quiz_keys_array[quiz_key].user_id+"'>"+quiz_keys_array[quiz_key].version+"</td>"+
        "<td class='font-weight-bold'><a href='#' onclick='loadQuizKeysId("+quiz_keys_array[quiz_key].id+");' title='Посмотреть вопросы и ответы варианта'>"+quiz_keys_array[quiz_key].variant+"</a></td>"+
        "<td><a href='javascript:'>"+quiz_keys_array[quiz_key].q1a + "</a></td>"+
        "<td><a href='javascript:'>"+quiz_keys_array[quiz_key].q2a + "</a></td>"+
        "<td><a href='javascript:'>"+quiz_keys_array[quiz_key].q3a + "</a></td>"+
        "<td><a href='javascript:'>"+quiz_keys_array[quiz_key].q4a + "</a></td>"+
        "<td><a href='javascript:'>"+quiz_keys_array[quiz_key].q5a + "</a></td>"+
        "<td><a href='javascript:'>"+quiz_keys_array[quiz_key].q6a + "</a></td>"+
        "<td><a href='javascript:'>"+quiz_keys_array[quiz_key].q7a + "</a></td>"+
        "<td><a href='javascript:'>"+quiz_keys_array[quiz_key].q8a + "</a></td>"+
        "<td><a href='javascript:'>"+quiz_keys_array[quiz_key].q9a + "</a></td>"+
        "<td><a href='javascript:'>"+quiz_keys_array[quiz_key].q10a + "</a></td>"+
        '<td><button class="btn btn-info btn-sm" onclick="editQuizKeysId('+quiz_keys_array[quiz_key].id+');"><i class="fas fa-edit"></i> Ред.</button></td>'+
        "</tr>";
                     
        }
                  response += "</tbody>";
                  $("#quizkeys_table").html(response);
                 
            

            } 
            
            $('#version_id').val(version);
            setTimeout(function () { $('#spinnerModal').modal('hide'); }, 200);
            

              }
          });
      }
        
 
       

function loadQuizKeysId(id){
          $.ajax({
              type: "GET",
              url: "api/quiz_keys/show/"+id,
              dataType: 'json',
              success: function(data) {
                var response="<table>";
                $("#quizkey_id").val(data['id']);
                $("#quizkey_id_text").html(data['variant']);
                $("#detailsModalLabel").html('Просмотр варианта теста №<b>'+data['variant']+'</b>&nbsp;&nbsp;(версия №<b>'+data['version']+'</b>)');
                
                for(var lists in data['list']){
                  var ans1="",ans2="",ans3="",ans4="";
                  var answ_arr=[data['list'][lists].bad1, data['list'][lists].bad2, data['list'][lists].bad3];
                  shuffle(answ_arr);
                  switch (data['list'][lists].good_position) {
                      case '1':
                        ans1="<b>"+data['list'][lists].good+"</b>";ans2=answ_arr[0];ans3=answ_arr[1];ans4=answ_arr[2];
                        break;
                      case '2':
                        ans2="<b>"+data['list'][lists].good+"</b>";ans1=answ_arr[0];ans3=answ_arr[1];ans4=answ_arr[2];
                        break;
                      case '3':
                        ans3="<b>"+data['list'][lists].good+"</b>";ans2=answ_arr[0];ans1=answ_arr[1];ans4=answ_arr[2];
                        break;
                      case '4':
                        ans4="<b>"+data['list'][lists].good+"</b>";ans2=answ_arr[0];ans3=answ_arr[1];ans1=answ_arr[2];
                        break;
                  }

                  response += "<tr><td>"+data['list'][lists].number+") <b>"+data['list'][lists].question+"</b></td></tr>"+
                  "<tr><td>а) "+ans1+"</td></tr>"+
                  "<tr><td>б) "+ans2+"</td></tr>"+
                  "<tr><td>в) "+ans3+"</td></tr>"+
                  "<tr><td>г) "+ans4+"</td></tr>"+
                  "<tr><td>&nbsp;</td></tr>";

                }
                response += "</table>";
                $("#questions_list_table").html(response);
                
                $('#detailsModal').modal();
              }
          });
        }


$(function() {
$("form").submit(function(event) {
  event.preventDefault();
  var data = $('form').serializeArray();
    $('#btn_save').prop({'disabled' : true});

  $.ajax({
        type: "POST",
        url: "api/quiz_keys/update/"+$('#quizkey_id').val(),
        dataType: 'json',
            data: data,
            error: function (result) {
              toastr.error(result.responseText);
            },
            success: function (result) { 
                if (result['status'] == true) {
                    
                    $('#detailsModal').modal('hide');
                    $('#btn_save').prop({'disabled' : false});
                    $('#save_quizkeys')[0].reset();
                    
                }
                else {
                  toastr.error(result['message']);

                    }
            }
    
    }); 
    });  
  }); 




loadCoursesList(1);

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

function generateNewVariants(){

  var data = {
  "version_id" : $('#version_id').val(),
  "variants_count" : $('#variants_count').val(),
  "topic_id" : $('#topic_id').val(),
  }

$.post('api/quiz_keys/generate', JSON.stringify(data))
  .done(function(data) {
    $('#generateModal').modal('hide');
    toastr.success(data['message']);

    loadQuizKeys($('#topic_id').val());
  }).fail(function(data) {
        toastr.error(data.responseJSON['message']);
    });
}

function generateBtn(){
  if($('#topic_id').val()!=null) {
    $('#generateModal').modal();
  } else {
    toastr.error('Выберите занятие!');
  }
}

function printVariants() {
 
  

$.post('api/quiz_keys/print', JSON.stringify(data))
  .done(function(data) {
    $('#printModal').modal('hide');

  }).fail(function(data) {
        toastr.error(data.responseJSON['message']);
    });
}

function printVariants(uid,ftype,topic,name,duration,token) {
  
  var data = {
    "version_id" : $('#print_version').val(),
    "topic_id" : $('#topic_id').val(),
    "questions_count" : $('#questions_count').val(),
  }

$('#spinnerModal').modal('show');

$.ajax({
        url: 'api/quiz_keys/print',
        method: 'POST',
        data:JSON.stringify(data),
        xhrFields: {
            responseType: 'blob'
        },
        success: function (data) {
            var a = document.createElement('a');
            var url = window.URL.createObjectURL(data);
            a.href = url;            

            //' + $('#faculty option:selected').text() + '.
            a.download = 'Тесты. Занятие №' + $('#topic_id option:selected').text() + '. Версия №' + $('#print_version option:selected').text() + '.xlsx';

            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
            $('#printModal').modal('hide');
            setTimeout(function () { $('#spinnerModal').modal('hide'); }, 200);
        }
    });

return false;
}

function printBtn() {
  if($('#topic_id').val()!=null) {
    $('#printModal').modal();
  } else {
    toastr.error('Выберите занятие!');
  }
}

</script>



 <!-- Main content -->
 <section class="content mt-2">
        <div class="card">
                <div class="card-header bg-dark">
                
                <h5 class="d-inline pl-3 font-weight-bold">Редактирование вариантов <small>тестов</small></h5>
                 
                 <div class="float-right">
                    <button type="button" id="btn_generate" class="btn btn-success mr-4" onclick="generateBtn()" disabled><i class="fas fa-random mr-2"></i>Сгенерировать</button>
                    
                    <button type="button" id="btn_print" class="btn btn-primary mr-4" onclick="printBtn()" disabled><i class="fas fa-print mr-2"></i>Распечатать</button>


                  <labels>Семестр:&nbsp;&nbsp;</label>
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                              <label class="btn btn-primary btn-sm active">
                                <input type="radio" name="options" id="semester1" onchange="loadCoursesList(1);$('#semester').val(1);$('#topics_table').html('');$('#topic_id').val(0).change();" autocomplete="off" checked value="1"> осенний
                              </label>
                              <label class="btn btn-primary btn-sm">
                                <input type="radio" name="options" id="semester2" onchange="loadCoursesList(2);$('#semester').val(2);$('#topics_table').html('');$('#topic_id').val(0).change();" autocomplete="off" value="2"> весенний
                              </label>
                          </div>
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
                            
                            <div class="col-6">
                            
                                <div class="form-group">
                                    <label for="topic">Тема занятия</label>
                                    <select class="custom-select" name="topic_id" id="topic_id" onchange="loadQuizKeys($(this).val());">
                                      
                                    </select>
                                  </div>
                            
                            </div>
                          
                                                                                          
             
              <div class="col-2 mx-auto">
                  <labels>Вопросы:&nbsp;&nbsp;</label>
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-info active">
                          <input type="radio" name="options" id="question_part1" onchange="$('#question_part').val(1);loadQuizKeys($('#topic_id').val());" autocomplete="off" checked value="1"> 1 - 10
                        </label>
                        <label class="btn btn-info">
                          <input type="radio" name="options" id="question_part2" onchange="$('#question_part').val(2);loadQuizKeys($('#topic_id').val());" autocomplete="off" value="2"> 11 - 20
                        </label>
                    </div>
                  </div>

                </div>
                </div>

           
              </div>


                <table  id="quizkeys_table" class="table table-sm table-bordered table-hover"></table>
               
        </section>

<form id="save_quiz_keys" action="#" method="POST"> 


        <div id="detailsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="detailsModalLabel">Редактировать вариант №<b><div id="quizkey_id_text" class="d-inline"></div></b></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="checker_result_modal">
                            
                        
                  <input type="hidden" name="quizkey_id" id="quizkey_id" value="0">
                  <input type="hidden" name="question_part" id="question_part" value="1">
                  <input type="hidden" class="form-control" name="semester" id="semester" value="1">
                  <input type="hidden" name="course_id" id="course_id" value="0">
      
                <div class="row">
                  <div class="col-6">
                      <div id="q1"></div>
                  </div>
                  <div class="col-6">
                      <div id="q2"></div>
                  </div>
                </div>
                                      
          <div id="questions_list_table"></div>                                                            
                 
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary" id="btn_save">Сохранить</button>
                      &nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
</form>



<div id="generateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="generateModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="generateModalLabel">Создать новые варианты</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                            
                    <input type="hidden" name="version_id" id="version_id" value="0">

                  <div class="row mb-2">
                    <div class="col-3 ml-5">
                          Кол-во:
                    </div>
                    <div class="col-4">
                    <select class="custom-select" name="variants_count" id="variants_count">
                      <option value="4">4</option>
                      <option value="5" selected>5</option>
                      <option value="8">6</option>
                      <option value="8">8</option>
                      <option value="10">10</option> 
                      <option value="12">12</option>      
                    </select>
                    </div>
                  
                  </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary" id="btn_generate_modal" onclick="generateNewVariants();">Сгенерировать</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>


<div id="printModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="generateModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="printModalLabel">Бланки тестов для печати</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">

                  <div class="row mb-2">
                    <div class="col-2">
                          <b>Версия:</b>
                    </div>
                    <div class="col-2">
                      <select class="custom-select" name="print_version" id="print_version">
      
                      </select>
                    </div>
                    <div class="col-4 ml-3">
                          Кол-во вопросов:
                    </div>
                    <div class="col-3 mr-3">
                      <select class="custom-select" name="questions_count" id="questions_count">
                          <option value="1">10</option>
                          <option value="2">20</option>
                      </select>
                    </div>
                  </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-success" id="btn_print_modal" onclick="printVariants();">Скачать</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<div class="modal fade bd-modal-lg" id="spinnerModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog h-100 d-flex flex-column justify-content-center my-0">
    <div class="d-flex justify-content-center">
      <div class="spinner-border text-success" style="width: 4rem; height: 4rem;" role="status">
        <span class="sr-only">Загрузка...</span>
      </div>
    </div>
  </div>
</div>