<!-- page script "<td>"+((data[question].topic == 0)? "1": "Female")+"</td>"+ -->
<script>

function shuffle(array) {
  var currentIndex = array.length, temporaryValue, randomIndex;
  
  // Пока не дошли до конца массива - тасуем...
  while (0 !== currentIndex) {

    // берем оставшийся элемент
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // Меняем местами его с текущим элементом
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

function loadQuizKeys(faculty, semestr, topic){
      
  if(faculty!=""){
    if(topic!=""){
  $.ajax({
              type: "GET",
              url: "api/objects/test.php?token="+$('#token').val()+"&action=list&f="+faculty+"&s="+semestr+"&t="+topic+"&question_part="+$('#question_part').val(),
              dataType: 'json',
              success: function(data) {
                if($('#question_part').val()==1) var response="<thead><tr class='table-secondary text-center'><th>Ver</th><th>Вар.№</th><th>В1</th><th>В2</th><th>В3</th><th>В4</th><th>В5</th><th>В6</th><th>В7</th><th>В8</th><th>В9</th><th>В10</th><th>Ред.</th></tr></thead><tbody>";
                if($('#question_part').val()==2) var response="<thead><tr class='table-secondary text-center'><th>Ver</th><th>Вар.№</th><th>В11</th><th>В12</th><th>В13</th><th>В14</th><th>В15</th><th>В16</th><th>В17</th><th>В18</th><th>В19</th><th>В20</th><th>Ред.</th></tr></thead><tbody>";
              
                if(data=='')  {response += "<tr class='text-danger text-center font-weight-bold'><td colspan='12'>Не найдено ни одного варианта</td></tr></tbody>";$("#quizkeys_table").html(response);} else {
                for(var answer in data){
                      
                          response += "<tr class='text-center'>"+
                      "<td title='Создано (отредактировано) - "+data[answer].timestamp+" пользователем - "+data[answer].owner+"'>"+data[answer].version+"</td>"+
                      "<td class='font-weight-bold'><a href='#' onclick='loadQuizKeys_id("+data[answer].id+");' title='Посмотреть вопросы и ответы варианта'>"+data[answer].variant+"</a></td>"+
                      "<td>"+data[answer].q1a+"</td>"+
                      "<td>"+data[answer].q2a+"</td>"+
                      "<td>"+data[answer].q3a+"</td>"+
                      "<td>"+data[answer].q4a+"</td>"+
                      "<td>"+data[answer].q5a+"</td>"+
                      "<td>"+data[answer].q6a+"</td>"+
                      "<td>"+data[answer].q7a+"</td>"+
                      "<td>"+data[answer].q8a+"</td>"+
                      "<td>"+data[answer].q9a+"</td>"+
                      "<td>"+data[answer].q10a+"</td>"+
                      "<td><a href='#' onclick='editQuizKeys_id("+data[answer].id+");'><i class='fa fa-magic' aria-hidden='true'></i></a></td>"+
                      "</tr>";
                     

                  }
                  response += "</tbody>";
                  $("#quizkeys_table").html(response);
                } 
              }
          });
        } else toastr.error('Выберите тему занятия!');
      } else toastr.error('Выберите факультет и дисциплину!');
      }
        
 
       

        function loadQuizKeys_id(id){
          $.ajax({
              type: "GET",
              url: "api/objects/test.php?token="+$('#token').val()+"&id="+id,
              dataType: 'json',
              success: function(data) {
                var response="<table>";
                $("#variant_id").val(data['id']);
                $("#variant_id_text").html(data['variant']);
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
        url: "api/objects/test.php?token="+$('#token').val()+"&action=update",
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


function loadFaculty(semestr){

$.getJSON('api/objects/faculty.php?p=list&s='+semestr, function(data) {
    $('#faculty').empty();
    $('#faculty').append('<option value="">Выберите...</option>');
    $.each(data, function(key, val) {
                    $('#faculty').append('<option value="' + key + '">' + val + '</option>');
                });
});
}

loadFaculty(1);

function loadTopicList(faculty, semestr){

$.getJSON('api/objects/topics.php?p=list&s='+semestr+'&f='+faculty, function(data) {
    $('#topic').empty();
    $('#topic').append('<option value="">Выберите...</option>');
    $.each(data, function(key, val) {
                    $('#topic').append('<option value="' + key + '">' + val.substr(0,70) + '...</option>');
                });
});
}


      </script>

<input type="hidden" class="form-control" name="question_part" id="question_part" value="1">
<input type="hidden" class="form-control" name="semestr" id="semestr" value="1">

 <!-- Main content -->
 <section class="content mt-2">
        <div class="card">
                <div class="card-header bg-light">
                
                    <h4 class="d-inline pl-3 font-weight-bold"> Редактирование вариантов</h4>
                 <div class="card-tools float-right">
                    <button type="button" id="btn_generate" class="btn btn-success" onclick="" disabled><i class="fas fa-bacon"></i> Сгенерировать</button>&nbsp;&nbsp;&nbsp;
                    <button type="button" id="btn_print" class="btn btn-info" onclick="" disabled><i class="fas fa-print"></i> Распечатать</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                  <labels>Семестр:&nbsp;&nbsp;</label>
                      <div class="btn-group btn-group-toggle" data-toggle="buttons">
                              <label class="btn btn-primary btn-sm active">
                                <input type="radio" name="options" id="semestr1" onchange="loadFaculty(1);$('#semestr').val(1);" autocomplete="off" checked value="1"> осенний
                              </label>
                              <label class="btn btn-primary btn-sm">
                                <input type="radio" name="options" id="semestr2" onchange="loadFaculty(2);$('#semestr').val(2);" autocomplete="off" value="2"> весенний
                              </label>
                          </div>

                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                              <i class="fas fa-minus"></i></button>

                  </div>
                </div>
           
                <div class="card-body">
                  
               
                    <div class="row">
                   <div class="col-3">
                               <div class="form-group">
                                                       <label for="faculty">Факультет</label>
                                                       <select class="custom-select" name="faculty" id="faculty" onchange="$('#quizkeys_table').html('');$('#topic').empty();loadTopicList($(this).val(), $('#semestr').val())">
                                          
                                                       </select>
                                                     </div>
                                               
                                               </div>
                                               
                                               <div class="col-4">
                                                
                                                   <div class="form-group">
                                                       <label for="topic">Тема занятия</label>
                                                       <select class="custom-select" name="topic" id="topic" onchange="loadQuizKeys($('#faculty').val(),$('#semestr').val(),$(this).val());">
                                                         
                                                       </select>
                                                     </div>
                                               
                                               </div>
                                              
                                                                                          
                                               <div class="col-2 mx-auto my-auto">
                                                  
                                           <button type="button" class="btn btn-primary" onclick="loadQuizKeys($('#faculty').val(),$('#semestr').val(),$('#topic').val());">Показать</button>
                                              </div>

                                              <div class="col-2">
                                                  <labels>Вопросы:&nbsp;&nbsp;</label>
                                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-success active">
                                                          <input type="radio" name="options" id="question_part1" onchange="$('#question_part').val(1);loadQuizKeys($('#faculty').val(),$('#semestr').val(),$('#topic').val());" autocomplete="off" checked value="1"> 1 - 10
                                                        </label>
                                                        <label class="btn btn-success">
                                                          <input type="radio" name="options" id="question_part2" onchange="$('#question_part').val(2);loadQuizKeys($('#faculty').val(),$('#semestr').val(),$('#topic').val());" autocomplete="off" value="2"> 11 - 20
                                                        </label>
                                                    </div>
                                                  </div>

                                               </div>
                                               </div>
           
           
              </div>






                    <table  id="quizkeys_table" class="table table-bordered table-hover">
                      
                     
                      
                     
                    </table>
               
        </section>

<form id="save_quiz_keys" action="#" method="POST"> 


        <div id="detailsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="detailsModalLabel">Редактировать вариант №<b><div id="variant_id_text" class="d-inline"></div></b></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="checker_result_modal">
                            
                        
                            <input type="hidden" class="form-control" name="variant_id" id="variant_id" value="0">
                            
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
                      <button type="button" class="btn btn-primary" id="btn_print">Распечатать</button>
                      &nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
</form>