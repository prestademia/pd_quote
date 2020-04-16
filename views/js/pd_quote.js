$(function () {

    $('.btn-quote').on('click', function () {
        $('.msg').hide();
        $('.form').show();
        $('.primero').show();
        $('.segundo').hide();
        $('.tercero').hide();
        $("#pd_quote_customer_email").val('');
        $("#pd_quote_customer_phone").val('');
        $("#pd_quote_customer_description").val('');

    });
    
    $('#new-quote').on('click', function () {
        $('.msg').hide();
        $('.form').show();
        $('.primero').show();
        $('.segundo').hide();
        $('.tercero').hide();
        $("#pd_quote_customer_email").val('');
        $("#pd_quote_customer_phone").val('');
        $("#pd_quote_customer_description").val('');

    });


    $('.pd_quote_form').on('submit', function () {
        $('.primero').hide();
        $('.segundo').show();
        $.ajax({
             type: 'POST',
             url: pd_quote_url + '?ajax=1&rand=' + new Date().getTime(),
             async: true,
             cache: false,
             dataType : "json",
             data: $(this).serialize(),
             success: function(jsonData) {
                 if (jsonData['success'] == 1) {
                    $(".msg").fadeIn(1000);
                    $('.segundo').hide();
                    $('.tercero').show();
                    $('.form').hide();
                    // $('.form').fadeOut(1000);                    
    
                 }
             }
         });

        return false;
     });
    
    
});