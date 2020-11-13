<script>

    loadClassesList();
    loadCoursesList(1);

    function loadClassesList(){

        $.post("api/classes/list")
            .done(function(data) {

                let class_array = data['classes'];
                let response = "", week_type = '';
                let i = 1;
                for(let class_info in data['classes']){

                    switch (parseInt(class_array[class_info].week_type)) {
                        case 0:
                            week_type = '';
                            break;
                        case 1:
                            week_type = '<div class="alert-default-danger w-75 mx-auto" style="font-size: smaller;">нечётная</div>';
                            break;
                        case 2:
                            week_type = '<div class="alert-default-success w-75 mx-auto" style="font-size: smaller;">чётная</div>';
                            break;
                    }



                    response += '<tr><td class="text-center align-middle"><a href="javascript:;" onclick="loadClassId('+class_array[class_info].id+');">'+i+'</a></td>'+
                        '<td class="text-center align-middle"><b>'+getFullNameOfDay(class_array[class_info].day_of_week)+'</b>'+week_type+'</td>'+
                        '<td class="text-center align-middle">'+class_array[class_info].time_start+' - '+class_array[class_info].time_end+'</td>'+
                        '<td class="text-center align-middle">'+getFacultiesColor(class_array[class_info].color, class_array[class_info].course_id)+'</td>'+
                        '<td class="text-center align-middle"><b>'+class_array[class_info].group_number+'</b></td>'+
                        '<td class="text-center align-middle">'+class_array[class_info].user_id+'</td>'+
                        '<td class="text-center align-middle"><small>'+class_array[class_info].updated_at+'</small></td>'+
                        '<td class="text-center align-middle"><button class="btn btn-info btn-sm m-1" onclick="loadClassId('+class_array[class_info].id+');"><i class="fas fa-edit"></i> Ред.</button><button class="btn btn-danger btn-sm ml-2" onclick="dialogdeleteClassId('+class_array[class_info].id+');"><i class="fas fa-remove"></i> Удалить</button></td></tr>';
                    i++;
                }
                $("#classes_table").html(response);

            });
    }

    function loadClassId(id){

        $.post( "api/classes/show/"+id)
            .done(function(data) {
                if(data.status==false) {
                    toastr.error(data.message);
                } else {
                    $("#id").val(data.id);
                    $("#username").val(data.user_id).change();
                    $("#faculty").val(data.course_id).change();
                    $("#day_of_week").val(data.day_of_week).change();
                    $("#week_type").val(data.week_type).change();
                    $("#group_number").val(data.group_number).change();
                    $("#time_start").val(data.time_start).change();
                    $("#time_end").val(data.time_end);
                    $("#room_id").val(data.room_id).change();
                    if(data.moodle_forum_topic==1) $("#moodle_forum_topic").prop('checked', true);
                    if(data.moodle_forum_end_messages==1) $("#moodle_forum_end_messages").prop('checked', true);
                    if(data.tabel==1) $("#tabel").prop('checked', true);

                    $('#detailsModalLabel').html('Редактировать занятие в <span class="text-uppercase">'+data.day_of_week+'</span> в '+data.time_start);
                    $('#action').val('update');
                    $('#detailsModal').modal();
                    $('#detailsModal').show().scrollTop(0);
                }
            });
    }

    function dialogdeleteClassId(id){
        $("#id").val(id);
        $('#modal-danger').modal();
    }

    function deleteClassId(id){

        $.post( "api/classes/delete/"+id)
            .done(function(data) {
                toastr.success(data.message);
            })
            .fail(function(data) {
                toastr.error(data.responseJSON['message']);
            })
            .always(function() {
                $('#modal-danger').modal('hide');
                loadClassesList($('#course_id').val());
            });
    }


    $(function() {
        $("form").submit(function(event) {
            event.preventDefault();
            var data = $('form').serializeJSON();

            $('#btn_save').prop({'disabled' : true});

            $.ajax({
                type: "POST",
                url: "api/classes/"+$('#action').val()+"/"+$('#id').val(),
                dataType: 'json',
                data: JSON.stringify(data),
                error: function (result) {
                    toastr.error(result.responseJSON['message']);
                },
                success: function (result) {
                    loadClassesList();
                    getClassesCount();
                    $('#detailsModal').modal('hide');
                    resetForm();
                    toastr.success(result['message']);
                }, complete: function ()  {
                    $('#btn_save').prop({'disabled' : false});
                }
            });
        });
    });

    function btnAdd () {
        $('#action').val('create');
        $('#detailsModalLabel').html('Добавить занятие');

        <?php if(isAdmin()): ?>
            loadUsersList('id', 'instructors', <?=$jwt_response->data->id ?>);
        <?php endif ?>

        $('#detailsModal').modal();
    }
</script>

<!-- Main content -->
<section class="content mt-2">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h4 class="d-inline pl-3 font-weight-bold">Расписание <small>занятий семинарского типа</small></h4>


                        <div class="float-right">

                            <button type="button" class="btn btn-success mr-3" id="btn_add" onclick="btnAdd()"><i class="fas fa-plus-square mr-2"></i>Добавить</button>

                            <button type="button" id="btn_refresh" class="btn btn-info" onclick="loadClassesList();" ><i class="fas fa-sync"></i> </button>

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
            <th scope="col" class="text-center">День недели</th>
            <th scope="col" class="text-center">Время</th>
            <th scope="col" class="text-center">Факультет</th>
            <th scope="col" class="text-center">Группа</th>
            <th scope="col" class="text-center">Преподаватель</th>
            <th scope="col" class="text-center">Изменено</b></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody id="classes_table">
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

                    <input type="hidden" name="id" id="id" value="0">
                    <input type="hidden" name="action" id="action" value="0">

                    <div class="row p-2 vertical-align">
                        <div class="col-4 font-weight-bold">День недели:</div>
                        <div class="col">
                            <select class="custom-select" name="day_of_week" id="day_of_week" style="width: auto;" required>
                                <option value="">Выберите...</option>
                                <option value="пн">Понедельник</option>
                                <option value="вт">Вторник</option>
                                <option value="ср">Среда</option>
                                <option value="чт">Четверг</option>
                                <option value="пт">Пятница</option>
                                <option value="сб">Суббота</option>
                            </select>
                            <select class="custom-select" name="week_type" id="week_type" style="width: auto;" required>
                                <option value="0">Все</option>
                                <option value="1">НЕчётная</option>
                                <option value="2">Чётная</option>
                            </select>
                        </div>
                    </div>

                    <div class="row p-2 vertical-align">
                        <div class="col-4 font-weight-bold">Время:</div>
                        <div class="col">
                            <input type="time" class="form-control d-inline mr-2" id="time_start" name="time_start" style="width: auto;" required>

                            <input type="time" class="form-control d-inline" id="time_end" name="time_end" style="width: auto;" required>
                        </div>
                    </div>

                    <div class="row p-2 vertical-align">
                        <div class="col-4 font-weight-bold">Номер группы:</div>
                        <div class="col">
                            <select class="custom-select" name="group_number" id="group_number" style="width: auto;" required>
                                <option value="">...</option>
                                <?php for($i=1;$i<55;$i++) echo '<option value="'.$i.'">'.$i.'</option>'; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row p-2 vertical-align">
                        <div class="col-4 font-weight-bold">Факультет/Дисциплина:</div>
                        <div class="col">
                            <select class="custom-select" name="course_id" id="faculty" style="" required>
                                <option value="">Выберите...</option>
                            </select>
                        </div>
                    </div>

                    <div class="row p-2 vertical-align">
                        <div class="col-4 font-weight-bold">Уч. комната:</div>
                        <div class="col">
                            <select class="custom-select" name="room_id" id="room_id" style="width: auto;">
                                <option value="0">Выберите...</option>
                                <option value="1">Дистант</option>
                                <option value="2">116-1</option>
                            </select>
                        </div>
                    </div>

        <?php if(isAdmin()): ?>

                    <script>
                        loadUsersList('id', 'instructors', <?=$jwt_response->data->id ?>);
                    </script>

                    <div class="row p-2">
                        <div class="col-4 font-weight-bold">Преподаватель:</div>
                        <div class="col">
                            <select class="custom-select" name="user_id" id="username" style="width: auto;" required>
                            </select>
                        </div>
                    </div>

        <?php endif ?>

                    <div class="row p-2">
                        <div class="col">
                            <hr class="m-1">
                            <input type="checkbox" class="ml-2" name="moodle_forum_topic" id="moodle_forum_topic" value="1" onchange="$(this).val($(this).prop('checked')?1:0);" title="включить"> автосоздание <b>темы</b> в форуме проверок в ЭИОП
                            <br>
                            <input type="checkbox" class="ml-2" name="moodle_forum_end_messages" id="moodle_forum_end_messages" value="1" onchange="$(this).val($(this).prop('checked')?1:0);" title="включить"> автоотправка <b>сообщ. +</b> в конце занятия в форум ЭИОП
                            <hr class="m-1">
                            <input type="checkbox" class="ml-2" name="tabel" id="tabel" value="1" onchange="$(this).val($(this).prop('checked')?1:0);"> добавить занятие в <b>почасовой табель</b>

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
                <button type="button" class="btn btn-danger" onclick="deleteClassId($('#id').val())">Удалить</button>
            </div>
        </div>
    </div>
</div>