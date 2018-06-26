$(document).ready(function() {
	$(".form_ajax_go").submit(function() {
		var th = $(this);
		$.ajax({
			type: "POST",
			url: "mail.php",
			data: th.serialize(),
            beforeSend: function () {
                //th.prop('disabled', true);
                //th.find('input,textarea').prop('disabled', true);
                th.append(
                  '<div style="position: absolute;z-index: 999;display: flex;flex-direction: column;justify-content: center;text-align: center;width: 100%;height: 100%;background: rgba(0,0,0,.25);">' +
                    '<img src="/preloader.svg" alt="">' +
                    '</div>'
                );
            },
			success: function () {
                th.trigger("reset");
                $.fancybox.open(
                    '<div class="message" style="text-align:center">' +
                    '<h2>Спасибо!</h2>' +
                    '<p>Менеджер свяжется с Вами в течение 10 минут</p>' +
                    '</div>'
                );
                setTimeout(function() {
                    $.fancybox.close();
                    $.fancybox.close();
                }, 3000);
            }
		});
		return false;
	});
});
