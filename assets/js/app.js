
function loadFaculty(){
    $.getJSON('api/faculties/list', function(data) {
        $('#faculty').empty();
        $('#faculty').append('<option value="">Выберите...</option>');
        $.each(data, function(key, val) {
                        $('#faculty').append('<option value="' + key + '">' + val + '</option>');
                    });
    });
  }

function loadCoursesList(semester) {
let data = {
    'semester':semester,
}
$.post('api/faculties/courses', JSON.stringify(data), function(data) {
    $('#faculty').empty();
    $('#faculty').append('<option value="">Выберите...</option>');
    $.each(data, function(key, val) {
                    $('#faculty').append('<option value="' + key + '">' + val + '</option>');
                });
});
}

function loadRooms(){

}

function resetForm(){
    $('#modal_form')[0].reset();
    $('#id').val(0);
}

function loadUsersList(type, group, selected_id=0) {
    let data = {
        'group': group,
    }

    $.post('api/users/list', JSON.stringify(data), function(data) {
        $('#username').empty();
        $('#username').append('<option value="">Выберите...</option>');
        $.each(data, function(key, val) {
            let user_name = '', user_value = '';

            if(type == 'short_name') {
                user_short_name = val.firstname + ' ' + val.secondname;
            }  else {
                user_short_name = val.firstname + ' ' + val.secondname;
            }
            if(type == 'login') {
                user_value = val.email;
            } else {
                user_value = val.id;
            }
            $('#username').append('<option value="' + user_value + '">' + val.lastname + ' ' + user_short_name + '</option>');
        });

        if(selected_id != 0) {
            $('#username').val(selected_id).change();
        }

    });
}

function getClassesCount() {
    $.getJSON('api/classes/count', function(data) {
        $('#classes_count').html(data);
    });
}

function getFullNameOfDay(short_name){
    let data = {
        "пн" : 'Понедельник',
        "вт" : 'Вторник',
        "ср" : 'Среда',
        "чт" : 'Четверг',
        "пт" : 'Пятница',
        "сб" : 'Суббота',
    }

    return data[short_name];
}

function getFacultiesColor(color, data) {
    switch (color) {
        case 'red':
            faculty_color = '<div class="alert-danger p-1">'+data+'</div>';
            break;
        case 'red-light':
            faculty_color = '<div class="alert-default-danger p-1">'+data+'</div>';
            break;
        case 'blue':
            faculty_color = '<div class="alert-info p-1">'+data+'</div>';
            break;
        case 'blue-light':
            faculty_color = '<div class="alert-default-info p-1">'+data+'</div>';
            break;
        case 'yellow':
            faculty_color = '<div class="alert-warning p-1">'+data+'</div>';
            break;
        case 'yellow-light':
            faculty_color = '<div class="alert-default-warning p-1">'+data+'</div>';
            break;
        case 'green':
            faculty_color = '<div class="alert-success p-1">'+data+'</div>';
            break;
        case 'green-light':
            faculty_color = '<div class="alert-default-success p-1">'+data+'</div>';
            break;
        default:
            faculty_color = data;
            break;
    }
    return faculty_color;

}