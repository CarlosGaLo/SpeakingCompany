var mailingCurrSelected = null;
var mailingErrorModal = null;
jQuery(function ($) {        
    $('.tooltipElement').tooltip();                    
    
    mailingErrorModal = new bootstrap.Modal(document.getElementById('mailingErrorModal'), {
        keyboard: false
    });
    
//    // Launch MailElement btns
    $('body').on('click', '.launchMailingBtn', function(e){
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
        
        var swalWithBootstrapButtonsAccept = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success mx-2',
                cancelButton: 'btn btn-light mx-2'
            },
            buttonsStyling: false
        });
        
        swalWithBootstrapButtonsAccept.fire({      
            title: "¿Estás seguro?",
            html: "Se va a lanzar el mailing, el tiempo del proceso dependerá del número de correos a enviar.<br><br>No cierre esta página hasta que se complete.",
            showCancelButton: true,
            scrollbarPadding: false,
            confirmButtonText: 'Sí, lanzar mailing',
            cancelButtonText: 'No, cancelar'
        }).then(function (result) {
            if (result.value) {   
                obj.siblings().hide('fast');
                obj.hide('fast');
                
                $('#mailing_row_' + obj.data('id')).addClass('active bgc-danger-l3');
                $('#mailing_rowMark_processing_' + obj.data('id')).show('fast');
                $('#mailing_rowLabel_processing_' + obj.data('id')).show('fast');
                
                mailingLaunchMail(obj);
            }
        });
    });        
    
    // Delete mail element
    $('body').on('click', '.deleteMailingBtn', function(e){
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
            html: "Se eliminará este mailing, los correos que hayan sido enviados no se podrán borrar.<br><br>Esta acción no se podrá deshacer.",
            showCancelButton: true,
            scrollbarPadding: false,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then(function (result) {
            if (result.value) {   
                // Send AJAX
                $.post($('#mailing_row_' + obj.data('id')).data('deleteUrl'), function(){ });
                
                $('#mailing_row_' + obj.data('id')).hide('fast');
            }
        });
    });        
});

function mailingLaunchMail(obj){
    $.post($('#mailing_row_' + obj.data('id')).data('launchUrl'), function(data){
        $('#mailing_currMails_' + obj.data('id')).text((data.totalMails + 0));
        
        if(data.finished){
            // Mark as finished            
            $('#mailing_currMails_' + obj.data('id')).text($('#mailing_totalMails_' + obj.data('id')).text());            
            
            $('#mailing_row_' + obj.data('id')).removeClass('bgc-danger-l3');
            $('#mailing_row_' + obj.data('id')).addClass('bgc-success-l3');
            $('#mailing_rowMark_processing_' + obj.data('id')).hide('fast');
            $('#mailing_rowLabel_processing_' + obj.data('id')).hide('fast');
            
            $('#mailing_rowMark_finished_' + obj.data('id')).show('fast');
            $('#mailing_rowLabel_finished_' + obj.data('id')).show('fast');
        }else if(data.error){            
            // Error, stop mailing
            $('#mailingErrorText').text(data.error);
            mailingErrorModal.show();
            
            obj.siblings().show('fast');
            obj.show('fast');

            $('#mailing_row_' + obj.data('id')).removeClass('active bgc-danger-l3');
            $('#mailing_rowMark_processing_' + obj.data('id')).hide('fast');
            $('#mailing_rowLabel_processing_' + obj.data('id')).hide('fast');
        }else{
            mailingLaunchMail(obj);
        }
    });
}