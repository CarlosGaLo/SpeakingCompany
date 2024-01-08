//update icons
    $.extend($.summernote.options.icons, {
      'align': 'fa fa-align',
      'alignCenter': 'fa fa-align-center',
      'alignJustify': 'fa fa-align-justify',
      'alignLeft': 'fa fa-align-left',
      'alignRight': 'fa fa-align-right',
      'indent': 'fa fa-indent',
      'outdent': 'fa fa-outdent',
      'arrowsAlt': 'fa fa-arrows-alt',
      'bold': 'fa fa-bold',
      'caret': 'fa fa-caret-down text-grey-m3 ml-1',
      'circle': 'fa fa-circle',
      'close': 'fa fa fa-close',
      'code': 'fa fa-code',
      'eraser': 'fa fa-eraser',
      'font': 'fa fa-font',
      'italic': 'fa fa-italic',
      'link': 'fa fa-link text-success-m1',
      'unlink': 'fas fa-unlink',
      'magic': 'fa fa-magic text-brown-m3',
      'menuCheck': 'fa fa-check',
      'minus': 'fa fa-minus',
      'orderedlist': 'fa fa-list-ol text-blue',
      'pencil': 'fa fa-pencil',
      'picture': 'far fa-image text-purple',
      'question': 'fa fa-question',
      'redo': 'fa fa-repeat',
      'square': 'fa fa-square',
      'strikethrough': 'fa fa-strikethrough',
      'subscript': 'fa fa-subscript',
      'superscript': 'fa fa-superscript',
      'table': 'fa fa-table text-danger-m2',
      'textHeight': 'fa fa-text-height',
      'trash': 'fa fa-trash',
      'underline': 'fa fa-underline',
      'undo': 'fa fa-undo',
      'unorderedlist': 'fa fa-list-ul text-blue',
      'video': 'far fa-file-video text-pink-m2'
    });

    $('#user_curriculum').summernote({
        lang: 'es-ES',
        height: 350,
        minHeight: 150,
        maxHeight: 600,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['codeview']],
        ],
    }); 

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
        title: "Eliminar foto",
        html: "Esta acción eliminará la foto actual del sistema inmediatamente y no se podrá deshacer",        
        showCancelButton: true,
        scrollbarPadding: false,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No, cancelar'
    }).then(function (result) {
        if (result.value) {
            obj.prop('disabled', 'disabled');
            $.post(obj.data('url'), function(data){
                swalWithBootstrapButtons.fire(
                    '¡Foto eliminada!',
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

$('#user_photo').aceFileInput({    
     btnChooseClass: 'bgc-grey-l2 pt-15 px-2 my-1px mr-1px',
     btnChooseText: 'Buscar',
     btnChangeText: 'Cambiar',
     placeholderText: 'No hay foto seleccionada',
//     placeholderIcon: '<i class="fa fa-image bgc-warning-m1 text-white w-4 py-2 text-center"></i>'    
});

$('#student_photo').aceFileInput({    
     btnChooseClass: 'bgc-grey-l2 pt-15 px-2 my-1px mr-1px',
     btnChooseText: 'Buscar',
     btnChangeText: 'Cambiar',
     placeholderText: 'No hay foto seleccionada',
//     placeholderIcon: '<i class="fa fa-image bgc-warning-m1 text-white w-4 py-2 text-center"></i>'    
});

$('#recoverPassBtn').on('click', function () {
    var obj = $(this);
    var swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success mx-2',
            cancelButton: 'btn btn-danger mx-2'
        },
        buttonsStyling: false
    });        

    swalWithBootstrapButtons.fire({            
        html: "Enviaremos un e-mail con instrucciones para cambiar la contraseña a <strong>" + obj.data('userMail') + "</strong>",        
        showCancelButton: true,
        scrollbarPadding: false,
        confirmButtonText: 'Sí, enviar',
        cancelButtonText: 'No, cancelar'
    }).then(function (result) {
        if (result.value) {
            obj.prop('disabled', 'disabled');
            $.post(obj.data('url'), function(data){
                if(data.error){
                    swalWithBootstrapButtons.fire(
                        'No se envió el e-mail',
                        'Error: ' + data.errorDescription,
                        'error'
                    )
                }else{
                    swalWithBootstrapButtons.fire(
                        '¡E-mail enviado!',
                        'El correo se ha enviado correctamente.',
                        'success'
                    )
                }

                obj.prop('disabled', false);
            }, 'json');                                
        }
    });
});