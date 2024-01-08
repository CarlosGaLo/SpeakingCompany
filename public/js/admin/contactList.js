jQuery(function ($) {
    if(window.location.hash) {
        var hashId = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
        
        $('#table-detail-' + hashId).collapse('show');
    }
    
    $('.tooltipElement').tooltip();
    
    $('#simple-table input').prop('checked', false); // for IE, because it doesn't respect autocomplete=off

    //highlight simple table row when selected
    function _highlight(row, checked) {
        //`toggle` with 2 arguments isn't supported in IE10+
        //row.classList.toggle('active', checked);
        //row.classList.toggle('bgc-yellow-l3', checked);
        //row.classList.toggle('bgc-h-default-l3', !checked);

        if (checked) {
            row.classList.add('active');
            row.classList.add('bgc-yellow-l3');
            row.classList.remove('bgc-h-default-l3');
        } else {
            row.classList.remove('active');
            row.classList.remove('bgc-yellow-l3');
            row.classList.add('bgc-h-default-l3');
        }
    }

    $('#simple-table tbody tr').on('click', function (e) {
        var inp = this.querySelector('input')
        if (inp == null)
            return;
        if (e.target.tagName != "INPUT") {
            inp.checked = !inp.checked;
        }
        _highlight(this, inp.checked)
    })
    $('#simple-table thead input').on('change', function () {
        var checked = this.checked;
        $('#simple-table tbody input[type=checkbox]').each(function () {
            this.checked = checked
            var row = $(this).closest('tr').get(0)
            _highlight(row, checked);
        })
    })


    //responsive table using basic table plugin
    $('#responsive-table').basictable({
        breakpoint: 800
    });


    $('.deleteContactBtn').on('click', function (e) {
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
            html: "Se eliminará el contacto de <strong>" + obj.data('contactName') + "</strong>.<br><br>Esta acción no se podrá deshacer.",
            showCancelButton: true,
            scrollbarPadding: false,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then(function (result) {
            if (result.value) {                 
                $('#table-detail-' + obj.data('contactId')).collapse('hide');                                
                obj.parents('tr').fadeTo('fast', 0.25);
                $.post(obj.data('url'), function(data){
                    if(!data.error){         
                        obj.parents('tr').hide('fast');
                        swalWithBootstrapButtons.fire(
                            'Contacto eliminado',
                            'El contacto se ha eliminado correctamente del sistema',
                            'success'
                        );
                    }                    
                }, 'json');                                
            }
        });
    });
    
    $('.reviewContactBtn').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        var obj = $(this);
        var swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success mx-2',
                cancelButton: 'btn btn-light mx-2'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({      
            title: "Revisar este contacto",
            html: "Se marcará el contacto como revisado y ya no contará como contacto pendiente.",
            showCancelButton: true,
            scrollbarPadding: false,
            confirmButtonText: 'Sí, revisado',
            cancelButtonText: 'No, cancelar'
        }).then(function (result) {
            if (result.value) {    
                obj.parents('tr').fadeTo('fast', 0.25);
                $.post(obj.data('url'), function(data){
                    if(!data.error){   
                        obj.parents('tr').fadeTo('fast', 1);
                        obj.parents('tr').find('.badgeNew').hide();
                        obj.parents('tr').find('.badgeReviewed').show();
                        obj.hide('fast');
                        swalWithBootstrapButtons.fire(
                            'Contacto revisado',
                            'Ya no contará en los contactos pendientes',
                            'success'
                        );
                    }                    
                }, 'json');                                
            }
        });
    });
});