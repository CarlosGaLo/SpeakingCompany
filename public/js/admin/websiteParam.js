jQuery(function ($) {        
    $('.setWebsiteParamBtn').on('click', function(e){
        e.preventDefault();
        
        var obj = $(this);
        var myValue = $(obj.data('valueField')).val();
       
        
        $(this).prop('disabled', true);
        
        $.post(
            obj.data('url'),
            {
                value: myValue
            }, 
            function(data){
                // Done
                obj.prop('disabled', false);
                $.aceToaster.add({
                    placement: 'tr',
                    body: "<div class='p-3 m-2 border-l-4 brc-success d-flex'><span class='align-self-center text-center mr-3 py-2 px-1 border-1 bgc-success radius-round'><i class='fa fa-check text-180 w-4 text-white mx-2px'></i></span><div><strong class='text-success'>Cambiado realizado</strong><br><span class='text-dark-tp4 text-110'>¡Tu cambio se han guardado correctamente!</span></div></div><button data-dismiss='toast' class='btn text-grey btn-h-light-success position-tr mr-1 mt-1'><i class='fa fa-times'></i></button></div>",
                    width: 480,
                    delay: 5000,

                    close: false,

                    className: 'shadow border-0 radius-2',

                    bodyClass: 'border-0 p-0',
                    headerClass: 'd-none',
                });
                
            },'json'
        );        
    });   
});