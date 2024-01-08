jQuery(function ($) {
    $('.contractCourseBtn').on('click', function (e) {
        e.preventDefault();
        
        var obj = $(this);
        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success mx-2',
                cancelButton: 'btn btn-light mx-2'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({      
            title: "¿Contratar este curso?",
            html: "Vas a contratar este curso <strong>(" + obj.data('coursePrice') + ")</strong>.<br><br>Esta acción no se podrá deshacer.",
            showCancelButton: true,
            scrollbarPadding: false,
            confirmButtonText: 'Sí, contratar',
            cancelButtonText: 'No, cancelar'
        }).then(function (result) {
            if (result.value) {    
                $('#detailCourse_' + obj.data('courseId')).modal('hide');
                $.post(obj.data('url'), { courseId: obj.data('courseId') }, function(data){
                    if(!data.error){  
                        obj.hide();
                        
                        swalWithBootstrapButtons.fire(
                            'Curso contratado',
                            'El curso se ha contratado correctamente. Ya puedes verlo en tus cursos !!',
                            'success'
                        );
                    }                    
                }, 'json');                                
            }
        });
    });
});