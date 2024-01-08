$('.imgChangeBtn').on('click', function(e){
    e.preventDefault();
    
    $('.imgBlock').slideUp('fast');
    $('.imgFieldBlock').slideDown('fast');
});

$('.imgDeleteBtn').on('click', function(e){
    e.preventDefault();
    
    var obj = $(this);
    var swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-danger mx-2',
            cancelButton: 'btn btn-light mx-2'
        },
        buttonsStyling: false
    });        

    swalWithBootstrapButtons.fire({    
        title: "Eliminar imagen",
        html: "Esta acción eliminará la imagen actual del sistema inmediatamente y no se podrá deshacer",
        showCancelButton: true,
        scrollbarPadding: false,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No, cancelar'
    }).then(function (result) {
        if (result.value) {
            obj.prop('disabled', 'disabled');
            $.post(obj.data('url'), function(data){
                swalWithBootstrapButtons.fire(
                    '¡Imagen eliminada!',
                    'Sube otra mejor ahora o cuando quieras.',
                    'success'
                );
        
                $('.imgBlock').slideUp('fast');
                $('.imgFieldBlock').slideDown('fast');
                $('.imgFieldCancelBlock').hide();
            }, 'json');                                
        }
    });
});

$('.imgCancelBtn').on('click', function(e){
    e.preventDefault();
    
    $('.ace-file-input a.remove').click();
    $('.imgBlock').slideDown('fast');
    $('.imgFieldBlock').slideUp('fast');
});

$('#activity_image').aceFileInput({    
     btnChooseClass: 'bgc-grey-l2 pt-15 px-2 my-1px mr-1px',
     btnChooseText: 'Buscar',
     btnChangeText: 'Cambiar',
     placeholderText: 'No hay imagen seleccionada',
//     placeholderIcon: '<i class="fa fa-image bgc-warning-m1 text-white w-4 py-2 text-center"></i>'    
});