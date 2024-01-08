var mailingCurrSelected = null;
jQuery(function ($) {        
    $('.tooltipElement').tooltip();           

    //responsive table using basic table plugin
    $('#responsive-table').basictable({
        breakpoint: 800
    });
    
    
    // highlight simple table row when selected
    function _highlight(row, checked) {
      // `classList.toggle` with 2 arguments isn't supported in IE10+
      // row.classList.toggle('active', checked)
      // row.classList.toggle('bgc-yellow-l3', checked)
      // row.classList.toggle('bgc-h-default-l3', !checked)

      if (checked) {
        row.classList.add('active')
        row.classList.add('bgc-success-l3')
        row.classList.remove('bgc-h-default-l3')
      } else {
        row.classList.remove('active')
        row.classList.remove('bgc-success-l3')
        row.classList.add('bgc-h-default-l3')
      }
    }
    
    function updateMailElementRolSelect(data){
        $('.mailElementRolSelector').each(function(){
            $(this).empty();
            
            $(this).append('<option selected>Rol ...</option>');
            
            if(data){
                for(i=0; i<data.length; i++){
                    $(this).append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                } 
            }
        })
    }
    
    var mailElementRolFormModal = new bootstrap.Modal(document.getElementById('formMailElementRol'), {
        keyboard: false
    })
    
    $('#mailElementRolConfigBtn').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        
        // Set fields
        $('#mailElementRolForm_id').val('');
        $('#mailElementRolForm_name').val('');
        
        // Clear last error
        $('#mailElementRolForm_error').hide();
        $('#mailElementRolFormSaveBtn').prop('disabled', false);
        
        // Start with current roles
        $('#mailElementRolCurrent').show();
        $('#mailElementRolForm').hide();
        
        // Open modal
        mailElementRolFormModal.show();
    });
    
    $('#mailElementRolNewBtn').on('click', function(e){
        // Set fields
        $('#mailElementRolForm_id').val('');
        $('#mailElementRolForm_name').val('');
        
        // Clear last error
        $('#mailElementRolForm_error').hide();
        $('#mailElementRolFormSaveBtn').prop('disabled', false);
        
        // Start with current roles
        $('#mailElementRolCurrent').hide('fast');
        $('#mailElementRolForm').show('fast');
    });
    
    $('body').on('click', '.modifyMailEleRolBtn', function(e){
        e.preventDefault();
        e.stopPropagation();
        
        // Set fields
        $('#mailElementRolForm_id').val($(this).data('id'));
        $('#mailElementRolForm_name').val($(this).data('name'));
        
        // Clear last error
        $('#mailElementRolForm_error').hide();
        $('#mailElementRolFormSaveBtn').prop('disabled', false);
        
        // Start with current roles
        $('#mailElementRolCurrent').hide('fast');
        $('#mailElementRolForm').show('fast');
    });
    
    $('body').on('click', '.deleteMailEleRolBtn', function(e){
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
            html: "Se eliminará este rol, pero los destinatarios NO serán eliminados.<br><br>Esta acción no se podrá deshacer.",
            showCancelButton: true,
            scrollbarPadding: false,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then(function (result) {
            if (result.value) {   
                // Send AJAX
                $.post($('#mailElementRol_row_' + obj.data('id')).data('deleteUrl'), function(data){
                    // Remove all rows
                    $('#mailingElementRolList').empty();
                    $('#mailElementRol_row_' + obj.data('id')).hide('fast');                        
                    
                    updateMailElementRolSelect(data.rolData);
                });                               
            }
        });
    });
    
    $('#mailElementRolFormSaveBtn').on('click', function(e){
        $(this).prop('disabled', true);
        
        // Get values
        var id = $('#mailElementRolForm_id').val();
        var name = $('#mailElementRolForm_name').val();        
        
        if(!name || !(name.trim())){
            $('#mailElementRolForm_error').show('fast');
            $(this).prop('disabled', false);
        }else{
            $('#mailElementRolForm_error').hide('fast');            
            // Send AJAX
            $.post($('#formMailElementRol').data('submitUrl'), {
                id: id,
                name: name,            
            }, function(data){                                
                // Refresh data
                
                // Remove all rows
                $('#mailingElementRolList').empty();
                
                if(data.rolData){
                    for(i=0; i<data.rolData.length; i++){
                        $('#mailingElementRolList').append('\
                            <tr class="bgc-h-yellow-l4 d-style" id="mailElementRol_row_' + data.rolData[i].id + '"  data-delete-url="' + data.rolData[i].deleteUrl + '">\n\
                                <td>' + data.rolData[i].name + '</td>\n\
                                <td style="text-align: right;">\n\
                                    <a href="#" data-name="' + data.rolData[i].name + '" data-id="' + data.rolData[i].id + '" class="modifyMailEleRolBtn mx-2px btn radius-1 border-2 btn-xs btn-brc-tp btn-light-secondary btn-h-lighter-success btn-a-lighter-success">\n\
                                        <i class="fa fa-pencil-alt"></i>\n\
                                    </a>\n\
                                    <a href="#" data-id="' + data.rolData[i].id + '" class="deleteMailEleRolBtn mx-2px btn radius-1 border-2 btn-xs btn-brc-tp btn-light-secondary btn-h-lighter-danger btn-a-lighter-danger">\n\
                                        <i class="fa fa-trash-alt"></i>\n\
                                    </a>\n\
                                </td>\n\
                            </tr>'
                        );
                    }
                    
                    updateMailElementRolSelect(data.rolData);
                }

                // Set fields
                $('#mailElementRolForm_id').val('');
                $('#mailElementRolForm_name').val('');

                // Clear last error
                $('#mailElementRolForm_error').hide();
                $('#mailElementRolFormSaveBtn').prop('disabled', false);

                // Open current roles
                $('#mailElementRolCurrent').show();
                $('#mailElementRolForm').hide();
            }, 'json');                        
        }
    });
    
    $('#mailElementFilterBtn').on('click', function(e){
        let showAll = true;
        let textFilter = $('#mailElementFilterText').val();
        let rolFilter = $('#mailElementFilterRol').val();
        
        // Text filter
        if(textFilter || rolFilter){            
            showAll = false;            
            
            $('.modifyMailEleBtn').each(function(){
                let showText = false;
                let showRol = false;
                let show = false;
                
                if(textFilter){
                    if($(this).data('name').search(textFilter) !== -1){
                        showText = true;
                    }
                    if($(this).data('mail').search(textFilter) !== -1){
                        showText = true;
                    }
                }else showText = true;
                
                if(rolFilter){
                    if($(this).data('typeRol') == rolFilter){
                        showRol = true;
                    }
                }else showRol = true;
                
                if(showText && showRol) show = true;
                
                if(show){                    
                    $(this).parents('.mailElement_row').show();
                }else{                 
                    $(this).parents('.mailElement_row').hide();
                    $(this).parents('.mailElement_row').removeClass('active');
                    $(this).parents('.mailElement_row').removeClass('bgc-success-l3');
                    $(this).parents('.mailElement_row').addClass('bgc-h-default-l3');
                    
                    $('#mailElementInput_' + $(this).data('id')).prop('checked', false);
                    
                }
            });
        }                
        
        if(showAll){
            $('.mailElement_row').show();
        }
    });

    $('body').on('click', '#mailingElementList tbody tr', function(e) {
        var ret = false
        try {
          // return if clicked on a .btn or .dropdown
          ret = e.target.classList.contains('btn') || e.target.parentNode.classList.contains('btn') || e.target.closest('.dropdown') != null
        } catch (err) {}

        if (ret) return

        var inp = this.querySelector('input')
        if (inp == null) return

        if (e.target.tagName != "INPUT") {
          inp.checked = !inp.checked
        }
        _highlight(this, inp.checked)
      })

    $('body').on('change', '#mailingElementList thead input', function() {
        var checked = this.checked
        $('#mailingElementList tbody input[type=checkbox]')
          .each(function() {
            this.checked = checked
            var row = $(this).closest('tr').get(0)
            _highlight(row, checked)
          })
      })

    // responsive table using basic table plugin
    $('#responsive-table').basictable({
      breakpoint: 800
    })
    
    
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

    $('.summernote').summernote({
        lang: 'es-ES',
        height: 350,
        minHeight: 150,
        maxHeight: 600,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']],
        ],
    });  
    
    var mailElementFormModal = new bootstrap.Modal(document.getElementById('formMailElement'), {
        keyboard: false
    })
    
    // Create new MailElement btn
    $('.newMailElementBtn').on('click', function(e){
        // Clear fields
        $('#mailElementForm_id').val('');
        $('#mailElementForm_name').val('');
        $('#mailElementForm_surname1').val('');
        $('#mailElementForm_surname2').val('');
        $('#mailElementForm_mail').val('');
        $('#mailElementForm_phone').val('');
        $('#mailElementForm_lastCourse').val('');
        $('#mailElementForm_origin').val('');
        $('#mailElementForm_birthday').val('');
        $('#mailElementForm_rolType').val('');
        $('#mailElementForm_testMail').prop('checked', false);
        
        // Clear last error
        $('#mailElementForm_error').hide();
        $('#mailElementForm_submit').prop('disabled', false);
        
        // Change title
        $('#mailElementForm_title').val('Nuevo destinatario');
        
        // Open modal
        mailElementFormModal.show();                
    });
    
    // Modify MailElement btns
    $('body').on('click', '.modifyMailEleBtn', function(e){
        e.preventDefault();
        e.stopPropagation();

        // Set fields
        $('#mailElementForm_id').val($(this).data('id'));
        $('#mailElementForm_name').val($(this).data('name'));
        $('#mailElementForm_surname1').val($(this).data('surname1'));
        $('#mailElementForm_surname2').val($(this).data('surname2'));
        $('#mailElementForm_mail').val($(this).data('mail'));
        $('#mailElementForm_phone').val($(this).data('phone'));
        $('#mailElementForm_lastCourse').val($(this).data('lastCourse'));
        $('#mailElementForm_origin').val($(this).data('origin'));
        $('#mailElementForm_birthday').val($(this).data('birthday'));
        $('#mailElementForm_rolType').val($(this).data('typeRol'));
        $('#mailElementForm_testMail').prop('checked', false);        
        if($(this).data('testMail') == 1) $('#mailElementForm_testMail').prop('checked', true);
        
        // Clear last error
        $('#mailElementForm_error').hide();
        $('#mailElementForm_submit').prop('disabled', false);
        
        // Change title
        $('#mailElementForm_title').val('Modificar destinatario');
        
        // Open modal
        mailElementFormModal.show();
    });
    
    // Save mail element data
    $('#mailElementForm_submit').click(function(){
        $(this).prop('disabled', true);
        
        // Get values
        var id = $('#mailElementForm_id').val();
        var name = $('#mailElementForm_name').val();
        var surname1 = $('#mailElementForm_surname1').val();
        var surname2 = $('#mailElementForm_surname2').val();
        var mail = $('#mailElementForm_mail').val();
        var phone = $('#mailElementForm_phone').val();
        var lastCourse = $('#mailElementForm_lastCourse').val();
        var origin = $('#mailElementForm_origin').val();
        var birthday = $('#mailElementForm_birthday').val();
        var rolType = $('#mailElementForm_rolType').val();
        var testMail = $('#mailElementForm_testMail').prop('checked');
        
        if(!mail || !  /(.+)@(.+){2,}\.(.+){2,}/.test(mail)  ){
            $('#mailElementForm_error').show('fast');            
            $(this).prop('disabled', false);
        }else{
            $('#mailElementForm_error').hide('fast');
            
            // Save current selection
            mailingCurrSelected = [];
            $('.mailElementInput:checked').each(function(){
                mailingCurrSelected.push($(this).data('id'));
            });
            
            // Send AJAX
            $.post($('#mailingElementList').data('submitUrl'), {
                id: id,
                name: name,
                surname1: surname1,
                surname2: surname2,                
                mail: mail,
                phone: phone,
                lastCourse: lastCourse,
                origin: origin,
                birthday: birthday,
                rolType: rolType,
                testMail: ((testMail)?testMail:'')
            }, function(data){                                
                // Refresh data
                $('#mailingElementList').replaceWith(data.list);
                
                // Restore selected
                $('.mailElementInput').each(function(){
                   if($.inArray($(this).data('id'), mailingCurrSelected) !== -1){
                       $(this).prop('checked', true);
                   }
                });
                
                // Reload filter
                $('#mailElementFilterBtn').click();
                
                mailElementFormModal.hide();                
                $(this).prop('disabled', false);
            }, 'json');                        
        }                
    });
    
    // Delete mail element
    $('body').on('click', '.deleteMailEleBtn', function(e){
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
            html: "Se eliminará este destinatario de la lista para este y futuros mailings.<br><br>Esta acción no se podrá deshacer.",
            showCancelButton: true,
            scrollbarPadding: false,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then(function (result) {
            if (result.value) {   
                // Send AJAX
                $.post($('#mailElement_row_' + obj.data('id')).data('deleteUrl'), function(){ });

                $('#mailElementInput_' + obj.data('id')).prop('checked', false);
                $('#mailElement_row_' + obj.data('id')).hide('fast');
            }
        });
    }); 
    
    
    // Create mailing btn
    $('#createMailingBtn').on('click', function(e){        
        e.preventDefault();
        e.stopPropagation();
        
        $(this).prop('disabled', true);
        
        // Check data
        if($('.mailElementInput:checked').length <= 0){  alert('ERROR: Tienes que seleccionar al menos un destinatario'); $(this).prop('disabled', false); }
        else if(!$('#mailSubject').val()){               alert('ERROR: Tienes que establecer el asunto del correo'); $(this).prop('disabled', false); }
        else if(!$('#mailBody').val()){                  alert('ERROR: Tienes que establecer el cuerpo del correo'); $(this).prop('disabled', false); }
        else{            
            // Send data
            mailingCurrSelected = [];
            $('.mailElementInput:checked').each(function(){
                mailingCurrSelected.push($(this).data('id'));
            });
            
            $.post($(this).data('url'), {
                subject: $('#mailSubject').val(),
                body: $('#mailBody').val(),
                to: mailingCurrSelected.join()
            }, function(data){
                location.href = data.url;
            }, 'json');
        }               
    });    
});