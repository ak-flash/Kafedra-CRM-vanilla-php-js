
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
    data = {
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

function resetForm(){
    $('#modal_form')[0].reset();
    $('#id').val(0);
}