$(document).ready(function() {
	$(".form_ajax_go").submit(function() {
		var th = $(this);
		$.ajax({
			type: "POST",
			url: "mail.php",
			data: th.serialize()
		}).done(function() {
			th.trigger("reset");
			$.fancybox.open('<div class="message" style="text-align:center"><h2>Спасибо!</h2><p>Менеджер свяжется с Вами в течение 10 минут</p></div>');
			setTimeout(function() {
				$.fancybox.close();
				$.fancybox.close();
			}, 3000);
		});
		return false;
	});
});
