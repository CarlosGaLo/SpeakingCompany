jQuery(function ($) {        
    $('.tooltipElement').tooltip();           

    //responsive table using basic table plugin
    $('#responsive-table').basictable({
        breakpoint: 800
    });


    $('.deleteActivityBtn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        var obj = $(this);
        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-danger mx-2',
                cancelButton: 'btn btn-light mx-2'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({      
            title: "¿Estás seguro?",
            html: "Se eliminará la actividad de <strong>" + obj.data('activityName') + "</strong>.<br><br>Esta acción no se podrá deshacer.",
            showCancelButton: true,
            scrollbarPadding: false,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then(function (result) {
            if (result.value) {   
                $('#table-detail-' + obj.data('activityId')).collapse('hide');   
                obj.parents('tr').fadeTo('fast', 0.25);
                $.post(obj.data('url'), function(data){
                    if(!data.error){         
                        obj.parents('tr').hide('fast');
                        swalWithBootstrapButtons.fire(
                            'Actividad eliminada',
                            'La actividad se ha eliminado correctamente del sistema',
                            'success'
                        );
                    }                    
                }, 'json');                                
            }
        });
    });        
});