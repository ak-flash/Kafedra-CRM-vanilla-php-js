<script>

loadCoursesList(1);

function loadTopicsList(semester, course_id){
  
  var data = {
    "semester" : semester,
    "course_id" : course_id,
  }

  $.post("api/topics/list", JSON.stringify(data))
    .done(function(data) {
      $('#btn_add').prop({'disabled' : false});
      $('#btn_refresh').prop({'disabled' : false});
      
      let topic_array = data['topics'];
      let response="";
          for(let topic in data['topics']){

                  response += '<tr><td class="text-center"><a href="javascript:;" onclick="loadTopicId('+topic_array[topic].id+');">'+topic_array[topic].t_number+'</a></td>'+
              '<td class="w-50">'+topic_array[topic].t_name+'</td>'+
              '<td class="text-center">'+topic_array[topic].updated_at+'</td>'+
              '<td class="text-center"><button class="btn btn-info btn-sm" onclick="loadTopicId('+topic_array[topic].id+');"><i class="fas fa-edit"></i> Ред.</button><button class="btn btn-danger btn-sm ml-2" onclick="dialogdeleteTopicId('+topic_array[topic].id+');"><i class="fas fa-remove"></i> Удалить</button></td></tr>';
          }
          $("#topics_table").html(response);
         
  });  
}

function loadTopicId(id){
  
  $.post( "api/topics/show/"+id)
    .done(function(data) {
        if(data.status==false) {
            toastr.error(data.message);
          } else {
            $("#id").val(data.id);
          $("#t_name").val(data.t_name);
          $("#t_number").val(data.t_number).change();
          $('#detailsModalLabel').html('Редактировать тему №<b>'+data.t_number+'</b>');
          $('#action').val('update');
          $('#detailsModal').modal();
          $('#detailsModal').show().scrollTop(0);
        }
  });
}

function dialogdeleteTopicId(id){
    $("#id").val(id);
    $('#modal-danger').modal();

}

function deleteTopicId(id){

  $.post( "api/topics/delete/"+id)
    .done(function(data) {
        toastr.success(data.message);     
    })
    .fail(function(data) {
        toastr.error(data.responseJSON['message']);
    })
    .always(function() {
        $('#modal-danger').modal('hide');
        loadTopicsList($('#semester').val(), $('#course_id').val()); 
    });
  }


$(function() {
  $("form").submit(function(event) {
    event.preventDefault();
    var data = $('form').serializeJSON();

    $('#btn_save').prop({'disabled' : true});

    $.ajax({
      type: "POST",
      url: "api/topics/"+$('#action').val()+"/"+$('#id').val(),
      dataType: 'json',
      data: JSON.stringify(data),
        error: function (result) {
                toastr.error(result.responseJSON['message']);
        },
        success: function (result) { 
                loadTopicsList($('#semester').val(), $('#course_id').val());
                $('#detailsModal').modal('hide');  
                resetForm();
                toastr.success(result['message']);
        }, complete: function ()  {
                $('#btn_save').prop({'disabled' : false});
        }
    }); 
  }); 
}); 
</script>

<!-- Main content -->
      <section class="content mt-2">
  
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <!-- Default box -->
              <div class="card">
                <div class="card-header bg-secondary">
                  <h4 class="d-inline pl-3 font-weight-bold">Тематический план <small>семинарских занятий</small></h4>
  
              

                  <div class="card-tools float-right">
                      <label>Семестр:&nbsp;&nbsp;</label>
                      <div class="btn-group btn-group-toggle" data-toggle="buttons">
                              <label class="btn btn-primary btn-sm active">
                                <input type="radio" name="options" id="semester1" onchange="loadCoursesList(1);$('#semester').val(1);$('#topics_table').html('');$('#course').val(0).change();" autocomplete="off" checked value="1"> осенний
                              </label>
                              <label class="btn btn-primary btn-sm">
                                <input type="radio" name="options" id="semester2" onchange="loadCoursesList(2);$('#semester').val(2);$('#topics_table').html('');$('#course').val(0).change();" autocomplete="off" value="2"> весенний
                              </label>
                          </div>

                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fas fa-minus"></i></button>
                 
                </div>
                </div>
                <div class="card-body">
                 
                  <div class="row">

                    <div class="col-12">
                      
                      <div class="form-group d-inline">
                            <label for="faculty" class="pr-2">Дисциплина</label>
                              <select class="custom-select" name="faculty" id="faculty" onchange="loadTopicsList($('#semester').val(), $(this).val()); $('#course_id').val($(this).val());" style="width: 230px;">
                              </select>

                            

                            <div class="float-right">
                          
                              <button type="button" class="btn btn-success mr-3" id="btn_add" onclick="$('#action').val('create');$('#detailsModalLabel').html('Добавить тему занятия');$('#detailsModal').modal();" disabled><i class="fas fa-plus-square mr-2"></i>Занятие</button>
                                             
                              <button type="button" id="btn_refresh" class="btn btn-info" onclick="loadTopicsList($('#faculty').val(), $('#semester').val());" disabled><i class="fas fa-sync"></i> </button>
                            
                            </div>
                        </div>
                    </div>  
                  </div>

                </div>
                <!-- /.card-body -->
              
                <!-- /.card-footer-->
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </section>

      <div class="pl-3 pr-3">
      <table class="table table-hover">
          <thead class="thead-light">
          <tr>
            <th scope="col" class="text-center">№</th>
            <th scope="col">Тема</th>
            <th scope="col" class="text-center">Изменено</b></th>
            <th scope="col"></th>
          </tr>
          </thead>
          <tbody id="topics_table">
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>


<form id="modal_form" action="#" method="POST">
          <div id="detailsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true" data-backdrop="static" >
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title ml-3 font-weight-bold" id="detailsModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"  onclick="resetForm()">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body" id="checker_result_modal">
                          
                    <input type="hidden" name="semester" id="semester" value="1">
                    <input type="hidden" name="id" id="id" value="0">
                    <input type="hidden" name="course_id" id="course_id" value="0">
                    <input type="hidden" name="action" id="action" value="0">

        <div class="row p-2">
          <div class="col-2 font-bold">Номер:</div>
          <div class="col">
            <select class="custom-select" name="t_number" id="t_number" style="width: 80px;" required>
            <option value="">...</option>
            <?php for($i=1;$i<20;$i++) echo '<option value="'.$i.'">'.$i.'</option>'; ?>
            </select>
          </div>
        </div> 
      
        <div class="row p-2">
          <div class="col-2 font-bold">Тема:</div>
          <div class="col">
            <textarea class="form-control" name="t_name" id="t_name" value="" rows="4" required></textarea>
          </div>
        </div>
 
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn_save">Сохранить</button>
                    &nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetForm()">Закрыть</button>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
</form>

<div class="modal modal-danger fade" id="modal-danger" data-backdrop="static" >
  <div class="modal-dialog">
    <div class="modal-content">   
      <div class="modal-body">
        <h4 class="ml-3">Вы уверены что хотите удалить?</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-danger" onclick="deleteTopicId($('#id').val())">Удалить</button>
      </div>
    </div>
  </div>
</div>