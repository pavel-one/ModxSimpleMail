$('.form_ajax_go').find('[type=file]').change(function(event) {
    files = this.files;
    if (this.files[0].size > 10374958) {
        alert('Размер файла не должен превышать 10мб')
        return ;
    }
    var data = new FormData();
    $.each( files, function( key, value ){
        data.append( key, value );
    });
    $('.form_ajax_go').css('opacity', '0.5');
    $.ajax({
        url: 'fileMail.php',
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        dataType: 'json',
        data: data,
        success: function( respond, textStatus, jqXHR ) {
            if (respond.success) {
                $('.form_ajax_go').css('opacity', '1');
                $('.form_ajax_go').find('[name=upload_file]').val(respond.file);
            } else {
                alert(respond.message);
                $('.form_ajax_go').css('opacity', '1');
            }
        },
        error: function( respond, textStatus, jqXHR ) {
            alert('Неизвестная ошибка');
        }
    });
    
});