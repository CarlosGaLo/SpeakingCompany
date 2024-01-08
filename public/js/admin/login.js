jQuery(function ($) {
    $('a[data-toggle="tab"]').on('click', function () {
        $('a[data-toggle="tab"]').removeClass('active');
    });
    
    $('#restoreForm').on('submit', function(e){
        e.preventDefault();
                        
        $.post($(this).data('url'), { mail: $('#id-recover-email').val() }, function(data){ }, 'json');
        
        $('#restoreModal').modal();
    });
    
    $('#restoreModal').on('hide.bs.modal', function (e) {
        $('#id-recover-email').val('');        
    });
    
    $('#restoreModal').on('hidden.bs.modal', function (e) {
        $('#navTab a[href="#id-tab-login"').tab('show');
    });
    
    $('#signupForm').on('submit', function(e){
        e.preventDefault();
        $('#signupError').hide('fast');
        
        $.post($(this).data('url'), $('#signupForm').serialize(), function(data){
            if(data['error']){
                $('#signupErrorText').text(data['errorDescription']);
                $('#signupError').show('fast');                
            }else{
                $('#signupModal').modal();                
            }           
        });
    });    
    
    $('#signupModal').on('hide.bs.modal', function (e) {
        $('#id-signup-name').val('');
        $('#id-signup-email').val('');
        $('#id-signup-password').val('');
        $('#id-signup-password2').val('');
        $('#id-signup-agree').prop("checked", false);
    });
    
    $('#signupModal').on('hidden.bs.modal', function (e) {
        $('#navTab a[href="#id-tab-login"').tab('show');
    });
});