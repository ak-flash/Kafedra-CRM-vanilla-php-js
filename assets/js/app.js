
function loadFaculty(){
    $.getJSON('api/faculties/list', function(data) {
        $('#faculty').empty();
        $('#faculty').append('<option value="">Выберите...</option>');
        $.each(data, function(key, val) {
                        $('#faculty').append('<option value="' + key + '">' + val + '</option>');
                    });
    });
  }

  function loadCoursesList(faculty, semester) {
    data = {
        'faculty':faculty,
        'semester':semester,
    }
    $.post('api/faculties/courses', JSON.stringify(data), function(data) {
        $('#course').empty();
        $('#course').append('<option value="">Выберите...</option>');
        $.each(data, function(key, val) {
                        $('#course').append('<option value="' + key + '">' + val + '</option>');
                    });
    });
  }

function resetForm(){
    $('#modal_form')[0].reset();
    $('#id').val(0);
}