$(document).ready(function () {    
    $('.addTextBtn').on('click', function (e) {
        addText($(this).data('widgetId'));
    });
    
    $('.addVideoBtn').on('click', function (e) {
        addVideo($(this).data('videoId'), $(this).data('widgetId'));
    });
    $('#videoUrlField').on('input', function (e) {
        checkVideoUrl($(this).val());
    });
    
    $('body').on('click', '.galleryElement', function(e){
        e.preventDefault();
        $('#showYoutubeIframeBlock').empty().hide();
        
        $('#showModuleVideo').modal('show');
        $('#showYoutubeIframeBlock').append($('<iframe width="100%" src="' + $(this).prop('href') + '" frameborder="0" allowfullscreen></iframe>').on('load', function () {
            $(this).height(0.6 * $(this).width());
        })).hide().removeClass('hide').show('fast'); 
                
        $('#deleteVideoBtn').data('imgUrl', $(this).find('.imagesFiles_img').data('imageUrl'))
    });
    
    

    $('#imagesFiles').each(function () {
        Sortable.create(this, {
            // give them a common name, so `.card` elements can be drag & dropped between different `.cards-containers`
            group: "widgets",

//            draggable: "> .card", // we are interested in dragging/sorting `.card` elements that are direct children of `.cards-container` elements
            animation: 200,

            ghostClass: "brc-grey-m1",
            chosenClass: "",
            dragClass: "bgc-yellow-m2",

//            handle: ('ontouchstart' in window ? ".card-header" : null), // in touch devices only drag using .card-header
//            filter: ".card-toolbar-btn",
            preventOnFilter: false,

            onEnd: function (evt) {
                ui_orderResources();
            }
        })
    })

    ui_orderResources();
});

function addText(widgetId){    
    // Check file
    if(!$('#textFile').val()){
        alert("Tienes que seleccionar un archivo para subirlo");
    }else{        
        var myFormData = new FormData();
        myFormData.append('textFile', $('#textFile').prop('files')[0]);

        $.ajax({
            url: $('#formModuleText').data('url'),
            type: 'POST',
            processData: false, // important
            contentType: false, // important
            dataType: 'json',
            data: myFormData
        }).done(function(data) {            
            var prototype = $('#moduleTextsWidget_' + widgetId).data('prototype');
            var newForm = prototype.replace(/__name__/g, $('.moduleVideoItem').length);
            var resourceRow = $(newForm);    
            var textTitle = $('#textTitleField').val();
            var textUrl = data;
            
            $('#moduleTextsWidget_' + widgetId).append(resourceRow);
            // Fill new form data
            $('#moduleTextsWidget_' + widgetId).find('.moduleTextItem:last .moduleTextTitle').val(textTitle);
            $('#moduleTextsWidget_' + widgetId).find('.moduleTextItem:last .moduleTextUrl').val(textUrl);  
        
            ui_orderResources();
        
            $('#formModuleText').modal('hide');        
        });                        
    }
}

function addVideo(videoId, widgetId) {
// Add new image to real resources object
    var prototype = $('#moduleVideosWidget_' + widgetId).data('prototype');
    var newForm = prototype.replace(/__name__/g, $('.moduleVideoItem').length);
    var resourceRow = $(newForm);    
    var videoUrl = '';
    var videoThumbnail = '';

    $('#moduleVideosWidget_' + widgetId).append(resourceRow);
    // Fill new form data
    $('#moduleVideosWidget_' + widgetId).find('.moduleVideoItem:last .moduleVideoVideoUrl').val(videoId);
    $('#moduleVideosWidget_' + widgetId).find('.moduleVideoItem:last .moduleVideoVideoKey').val(videoId);        
    
    // Add vídeo to resources list    
    videoUrl = '//www.youtube.com/embed/' + videoId + '?autoplay=1';
    videoThumbnail = '//img.youtube.com/vi/' + videoId + '/0.jpg';
     
    var elementBlock = '<li style="border-color: #cccccc; float: left; overflow: hidden; margin: 2px; border: 2px solid #333;"><a href="' + videoUrl + '" rel="propertyGallery" class="galleryElement fancybox.iframe cboxElement watermarkYoutube"><img data-image-url="' + videoId + '" class="imagesFiles_img galleryVideo" src="' + videoThumbnail + '" width="300" height="225" /></a></li>';
    
    $('#imagesFiles').append($(elementBlock));
    // Empty vídeo data
    $('#videoUrlField').val('');
    $('#youtubeErrorBlock').hide();
    $('#youtubeIframeBlock').empty().hide();
    $('.addVideoBtn').attr('disabled', 'disabled');
    $('.addVideoBtn').data('videoId', '');
    $('#youtubeStandbyBlock').show();
    // Refresh order    
    ui_orderResources();
    $('#formModuleVideo').modal('hide');
//    refreshBootstrap();
}

function checkVideoUrl(url) {
    $('#youtubeErrorBlock').hide();
    $('#youtubeIframeBlock').empty().hide();
    $('.addVideoBtn').attr('disabled', 'disabled');
    $('.addVideoBtn').data('videoId', '');
    if (url) {
        $('#youtubeStandbyBlock').hide('fast');
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        var match = url.match(regExp);
        $('.addVideoBtn').data('originalUrl', url);
        
        if (match && match[2].length == 11) {
            let youtubeId = match[2];
            $('#youtubeIframeBlock').append($('<iframe width="100%" src="//www.youtube.com/embed/' + youtubeId + '?rel=0" frameborder="0" allowfullscreen></iframe>').on('load', function () {
                $(this).height(0.6 * $(this).width());
            })).hide().removeClass('hide').show('fast');
            $('.addVideoBtn').data('videoId', youtubeId);            
            $('.addVideoBtn').removeAttr("disabled");
        }else{
            $('#youtubeErrorBlock').show('fast');
        }        
    } else {
        $('#youtubeErrorBlock').hide('fast');
        $('#youtubeStandbyBlock:hidden').show('fast');
    }
}

function ui_orderResources() {
    // Set order for resources
    $('.imagesFiles_img').each(function (index) {
        var imgUrl = $(this).data('imageUrl');        
        $("input.moduleVideoVideoUrl").each(function () {            
            if (imgUrl == $(this).val()) {                
                $(this).siblings('.moduleVideoPosition').val(index + 1);
            }
        });
    });
    
    // Check number of images
    if ($('.imagesFiles_img').length > 0) {        
        $('#images_noImgBlock').hide();
    } else {        
        $('#images_noImgBlock').removeClass('hide').show();
    } 
    
    // Set order for texts 
    $('.textsFiles_text').each(function (index) {
        var textUrl = $(this).data('textFilename');        
        $("input.moduleTextUrl").each(function () {            
            if (textUrl == $(this).val()) {                
                $(this).siblings('.moduleTextPosition').val(index + 1);
            }
        });
    });
    
    // Check number of text
    if ($('.textsFiles_text').length > 0) {        
        $('#text_noImgBlock').hide();
    } else {        
        $('#text_noImgBlock').removeClass('hide').show();
    } 
}

$('#deleteVideoBtn').on('click', function(e){
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
        title: "Eliminar vídeo",
        html: "Esta acción eliminará el vídeo del módulo, pero no de Youtube.",        
        showCancelButton: true,
        scrollbarPadding: false,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No, cancelar'
    }).then(function (result) {
        if (result.value) {                        
            swalWithBootstrapButtons.fire(
                '¡Vídeo eliminado!',
                'Recuerda guardar el módulo para que los cambios tengan efecto.',
                'success'
            );

            //Delete videokey                        
            $("input.moduleVideoVideoUrl").each(function () {                
                if (obj.data('imgUrl') == $(this).val()) {
                    $(this).parent().remove();
                }
            });
            // Delete visual info
            $('#imagesFiles img').each(function () {
                if (obj.data('imgUrl') == $(this).data('imageUrl')) {
                    $(this).parents('li').hide('fast', function () {
                        $(this).remove();
                        ui_orderResources();
                    });
                }
            });  
            
            $('#showModuleVideo').modal('hide');
        }
    });
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

$('.textDeleteBtn').on('click', function(e){
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
        title: "Eliminar texto",
        html: "Esta acción eliminará este texto del sistema inmediatamente y no se podrá deshacer",        
        showCancelButton: true,
        scrollbarPadding: false,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No, cancelar'
    }).then(function (result) {
        if (result.value) {
            obj.prop('disabled', 'disabled');
            swalWithBootstrapButtons.fire(
                '¡Texto eliminado!',
                'Ya no estará accesible.',
                'success'
            );

            //Delete textkey                        
            $("input.moduleTextUrl").each(function () {                
                if (obj.data('textFilename') == $(this).val()) {
                    $(this).parent().remove();
                }
            });
            // Delete visual info
            $('.textsFiles_text').each(function () {
                if (obj.data('textFilename') == $(this).data('textFilename')) {
                    $(this).hide('fast', function () {
                        $(this).remove();
                        ui_orderResources();
                    });
                }
            });
        }
    });
});

$('.imgCancelBtn').on('click', function(e){
    e.preventDefault();
    
    $('.ace-file-input a.remove').click();
    $('.imgBlock').slideDown('fast');
    $('.imgFieldBlock').slideUp('fast');
});

$('#module_headerImage').aceFileInput({    
     btnChooseClass: 'bgc-grey-l2 pt-15 px-2 my-1px mr-1px',
     btnChooseText: 'Buscar',
     btnChangeText: 'Cambiar',
     placeholderText: 'No hay imagen seleccionada',
//     placeholderIcon: '<i class="fa fa-image bgc-warning-m1 text-white w-4 py-2 text-center"></i>'    
});

$('#textFile').aceFileInput({    
     btnChooseClass: 'bgc-grey-l2 pt-15 px-2 my-1px mr-1px',
     btnChooseText: 'Buscar',
     btnChangeText: 'Cambiar',
     placeholderText: 'No hay archivo seleccionado',
//     placeholderIcon: '<i class="fa fa-image bgc-warning-m1 text-white w-4 py-2 text-center"></i>'    
});