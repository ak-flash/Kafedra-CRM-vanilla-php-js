<div class="modal fade bd-modal-lg" id="spinnerModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
  <div class="modal-dialog h-100 d-flex flex-column justify-content-center my-0">
    <div class="d-flex justify-content-center">
      <div class="spinner-border text-success" style="width: 4rem; height: 4rem;" role="status">
        <span class="sr-only">Загрузка...</span>
      </div>
    </div>
  </div>
</div>

<input type="hidden" class="form-control" name="items_per_page" id="items_per_page" value="10">
<input type="hidden" class="form-control" name="num_page" id="num_page" value="1">

<script>
// Load list of faculties for question tags
$.getJSON('api/topics/listshort', function(data) {
      $.each(data["topics"], function(key, val) {
            if(val.t_name.length>30) val.t_name = val.t_name.substr(0,50) + " ...";
            $('#topic_tags').append('<option value="' + val.id + '">' + val.t_name + '</option>');
            $('#filterQuestions').append('<option value="0">Показать все</option>');
            $('#filterQuestions').append('<option value="' + val.id + '">' + val.t_name + '</option>');
      });
});



function loadQuiz(page){
  
  $('#num_page').val(page);
  $('#spinnerModal').modal('show');


  var data = {
    "items_per_page" : $('#items_per_page').val(),
    "page" : page,
    "search_q" : $('#search_q').val(),
    "search_topic" : $('#filterQuestions').val(),
  }
  
  $.post( "api/quizzes/list", JSON.stringify(data))
    .done(function(data) {

      let quizzes_array = data['quizzes'];
       let response="";

          for(let quiz in data['quizzes']){
              
    
                  response += "<tr>"+
              "<th scope='row'>"+quizzes_array[quiz].id+"</th>"+
              "<td><a href='javascript:' onclick='loadQuizId("+quizzes_array[quiz].id+");'>"+quizzes_array[quiz].question+"</a></td>"+
              "<td><b>"+quizzes_array[quiz].good_answer+"</b></td>"+
              "<td>"+quizzes_array[quiz].bad1_answer+"</td>"+
              "<td>"+quizzes_array[quiz].bad2_answer+"</td>"+
              "<td>"+quizzes_array[quiz].bad3_answer+"</td>"+
              "<td><small>"+quizzes_array[quiz].author+"<br>"+quizzes_array[quiz].updated_at+"</small></td>"+
              "</tr>";
          
          }
          
          $("#quiz_table").html(response);
          $("#quiz_count").html(data['count']);

          $("#pagination").html('<li class="page-item disabled" id="back_page"><a class="page-link" href="#" aria-label="Назад" onclick="loadQuiz('+(page-1)+');"><span aria-hidden="true">&laquo;</span></a></li>');
                
         let i = 1, cl="", last_page = Math.ceil(data['count']/$('#items_per_page').val());
            
         while (i <= last_page) { 
              if(i==page)  cl=" active"; else cl="";
                $("#pagination").append('<li class="page-item'+cl+'"><a class="page-link" href="#" onclick="loadQuiz('+i+');">'+i+'</a></li>');
                i++;
            }

        $("#pagination").append('<li class="page-item" id="forward_page"><a class="page-link" href="#" aria-label="Далее" onclick="loadQuiz('+(page+1)+');"><span aria-hidden="true">&raquo;</span></a></li>');

        if(page==1) $('#back_page').addClass('disabled'); else $('#back_page').removeClass('disabled');
        if(page==last_page) $('#forward_page').addClass('disabled'); else $('#forward_page').removeClass('disabled');  
        
 
    })
    .fail(function(data) {
        toastr.error(data.responseJSON['message']);
        setTimeout(function () { $('#spinnerModal').modal('hide'); }, 200);
    })
    .always(function() {
      setTimeout(function () { $('#spinnerModal').modal('hide'); }, 200);
    });
    
}
        
 
loadQuiz(1);

function loadQuizId(id){
    $.getJSON("api/quizzes/show/"+id, function(data) {
          $("#id").val(data['id']);
          $("#question").val(data['question']);
          $("#good_answer").val(data['good_answer']);
          $("#bad1_answer").val(data['bad1_answer']);
          $("#bad2_answer").val(data['bad2_answer']);
          $("#bad3_answer").val(data['bad3_answer']);
          
          if(data['topic_tags']) {
              t_tags = data['topic_tags'].split(',');
              $('#topic_tags').val(t_tags).change();
          } else {
              $('#topic_tags').val(0).change();
          }


          $('#detailsModalLabel').html('Редактировать вопрос №<b>'+data['id']+'</b>');
          $('#action').val('update');
          $('#detailsModal').modal();
    }); 
}


$(function() {
$("form").submit(function(event) {
  event.preventDefault();
    var data = $('form').serializeJSON();

    //$('#btn_save').prop({'disabled' : true});

    $.ajax({
      type: "POST",
      url: "api/quizzes/"+$('#action').val()+"/"+$('#id').val(),
      dataType: 'json',
      data: JSON.stringify(data),
        error: function (result) {
              toastr.error(result.responseJSON['message']);
        },
        success: function (result) { 
              loadQuiz($('#num_page').val());
              $('#detailsModal').modal('hide');
              resetQuizForm();
              toastr.success(result['message']);
        }, complete: function ()  {
              $('#btn_save').prop({'disabled' : false});
        }
    }); 
  }); 

}); 


function resetQuizForm(){
    $('#save_quiz')[0].reset();
    $('#id').val(0);
    $('#topic_tags').val(0).change();
}


function deleteQuizId(id){
  var result = confirm("Вы уверены что хотите удалить вопрос?"); 
    if (result == true) {     
      $.post( "api/quizzes/delete/"+id)
      .done(function(data) {
          toastr.success(data.message);     
      })
      .fail(function(data) {
          toastr.error(data.responseJSON['message']);
      })
      .always(function() {
          $('#detailsModal').modal('hide');
          loadQuiz(1);
      });   
  } 
}


$(document).ready(function() {
    $('.select2').select2();
    $('#search_close_btn').hide();
});

function showTopicsQuestions() {
  loadQuiz(1);
  $('#filterModal').modal('hide');
}
</script>


 <!-- Main content -->
 <section class="content mt-2">
        <div class="card">
                <div class="card-header bg-dark">

                 <div class="row">   
                 <div class="col-5">
                      <h4 class="d-inline pl-2 font-weight-bold">База тестовых вопросов</h4>
                  </div> 
                  
                  <button type="button" id="btn_filter_topic" class="btn btn-info mr-3" onclick="$('#filterModal').modal();" ><i class="fas fa-filter"></i> </button>
    
    <!-- Search form -->
    <div class="col-2">
    <div class="input-group md-form form-sm form-1 pl-0">
                <div class="input-group-prepend">
                  <span class="input-group-text lighten-3"><i class="fas fa-search text-green" aria-hidden="true"></i></span>
                </div>
                <input class="form-control my-0 py-1" type="text" id="search_q" placeholder="Найти" aria-label="Search" onchange="if($(this).val().length>=3) { loadQuiz(1); $('#search_close_btn').show();}">
                <button class="btn bg-transparent" id="search_close_btn" style="margin-left: -40px; z-index: 100;" onclick="$('#search_q').val('');loadQuiz(1);$(this).hide();">
                  <i class="fa fa-times"></i>
                </button>
              </div>


 
              </div>               
                 
                 
              <div class="col ml-6">
                      
              
              
              <button type="button" class="btn btn-success mr-3" onclick="$('#action').val('create');$('#detailsModalLabel').html('Добавить вопрос для теста');$('#detailsModal').modal();"><i class="fas fa-plus-square mr-2"></i> Вопрос</button>
                    
              <button type="button" id="btn_import" class="btn btn-warning mr-3" onclick="$('#importModal').modal();"><i class="fas fa-upload"></i> </button> 

                    <button type="button" id="btn_refresh" class="btn btn-info mr-3" onclick="loadQuiz(1);" ><i class="fas fa-sync"></i> </button>

                    <select class="custom-select" style="width:65px;" name="items" id="items" onchange="$('#items_per_page').val($(this).val());loadQuiz(1);">
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
                      <tr>
                        <td>№</td>
                        <td>Вопрос</td>
                        <td class="text-success font-weight-bold">Ответ+</td>
                        <td class="text-danger">Ответ-</td>
                        <td class="text-danger">Ответ-</td>
                        <td class="text-danger">Ответ-</td>
                         <td>Автор/дата:</td>
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
                    <div class="modal-header table-active">
                      <h5 class="modal-title" id="detailsModalLabel">Modal title</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="resetQuizForm()">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body" id="checker_result_modal">
                            
                        
                    <input type="hidden" class="form-control" name="id" id="id" value="0">
                    <input type="hidden" class="form-control" name="action" id="action" value="0">
            
          
                   Вопрос: <textarea class="form-control mb-2 bg-light" name="question" id="question" rows="2"></textarea>
                           
                            
                <input type="text" class="form-control mb-2 bg-success" name="good_answer" id="good_answer">
                <input type="text" class="form-control mb-2" name="bad1_answer" id="bad1_answer">
                <input type="text" class="form-control mb-2" name="bad2_answer" id="bad2_answer">
                <input type="text" class="form-control mb-2" name="bad3_answer" id="bad3_answer">
                 
            
                    <div class="row p-2">
                      <div class="col-2 font-bold">Для тем:</div>
                      <div class="col-10">
                        <select class="select2 form-control" name="topic_tags[]" multiple="multiple" id="topic_tags" style="width: 100%">
                        </select>
                      </div>
                    </div>
                                                   
                 
                    <div class="modal-footer" style="display:inline-block;width:100%;";>
                      <button type="button" class="btn btn-danger" style="float:left;" id="btn_delete" onclick="$('#action').val('delete');deleteQuizId($('#id').val());">Удалить</button>
					  
                      <button type="button"   style="float:right;" class="btn btn-info" data-dismiss="modal" onclick="resetQuizForm()">Закрыть</button>&nbsp;&nbsp;&nbsp;
                      <button type="submit" class="btn btn-primary"   style="float:right;"  id="btn_save">Сохранить</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
</form>


<div id="filterModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="filterModalLabel">Выбрать впоросы  указанной темы</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                            
                
                  <div class="row mb-2">
                    <div class="col-3">
                          <b>Тема</b>:
                    </div>
                    <div class="col-9">
                    <select class="custom-select" id="filterQuestions" onchange="">
    
                    </select>
                    </div>
                    
                  </div>
                            
         
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary mr-2" id="btn_filter_modal" onclick="showTopicsQuestions();">Показать</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>

<div id="importModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="importModalLabel">Импортировать вопросы в базу</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                  
      
        <div class="row mb-2">
          <div class="col-3">
                <b>Файл</b>:
          </div>
          <div class="col-9">
          ссс
          </div>
          
        </div>
                  

          <div class="modal-footer">
            <button type="button" class="btn btn-success mr-2" id="btn_filter_modal" onclick="importQuestions();">Загрузить</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
            
          </div>
        </div>
      </div>
    </div>
  </div>

