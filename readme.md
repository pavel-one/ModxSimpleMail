## Описание

Скрипт позволяет загружать файлы, прикреплять их к письму, привязываться к абсолютно любой форме, имеет защиту от спама, интеграцию с компонентом CallBack и еще много-много всего

## Использование

1. Кидаем php - файлы в корень
2. Создаем системную настройку **ms2_email_manager** (она отвечает за получателей писем, получателей указывать через запятую)
3. Устанавливаем компонент **CallBack**
4. Смотрим js файл mail.js и копируем к себе изменив методы success
5. Форме к которой нужно привязать скрипт даем класс **form_ajax_go**
6. В этой же форме ставим input


	<input type="hidden" name="form_subject" value="Тема письма"/>
он будет отвечать за тему письма
7. Форма должна иметь input name **Имя** и input name **Телефон** (это не обязательно)
8. Все дополнительные поля которые нужно отправить на почту и записать в админку должны начинаться с **rec_** например:


	<input type="text" name="rec_Почта">

где после rec_ идет название которое запишется в админку и отправится на почту
9. Создаем чанк **FormMailSent** с вот таким содержимым:


	{if is_array($data)}
	    {set $count = 1}
	    <table style='width: 100%;'>
	        {foreach $data as $key => $value}
	            <tr {if $count++ % 2 != 0}style="background-color: #f8f8f8;"{/if}>
	                <td style='padding: 10px; border: #e9e9e9 1px solid;'><b>{$key}</b></td>
	                <td style='padding: 10px; border: #e9e9e9 1px solid;'>{$value}</td>
	            </tr>
	        {/foreach}
	    </table>
	{/if}

Это чанк письма который будет отправляться на почту менеджерам **У вас должен быть установлен pdoTools**

## Прикрепление файла

Делаем все тоже самое, что и в использовании, только добавляются еще и эти пункты:

1. Форма должна обязательно иметь input type file:


	<input type="file" name="norec">

2. Также она должна иметь скрытое поле upload_file


	<input type="hidden" name="upload_file" value="">

3. В MODX_ASSETS_PATH должна быть создана папка **frontFile** туда будут грузится файлы с почты, которые будут называться также, как ID сессии загрузившего (это сделано чтобы не захломлять диск)
4.Смотрим файл **file.js** и подключаем себе

## Пример полноценной формы

	<form class="form_ajax_go">
	    <input type="hidden" name="form_subject" value="Присоебениться к команде"/>
	    <input type="hidden" name="upload_file" value="">

	        <div>
	            <label for="join-modal-name">Как к вам обращаться</label>
	            <input id="join-modal-name" type="text" name="Имя">
	        </div>
	        <div>
	            <label for="join-modal-phone">Телефон</label>
	            <input id="join-modal-phone" type="text" name="Телефон">
	        </div>
	        <div>
	            <label for="join-modal-mail">Почта</label>
	            <input id="join-modal-mail" type="text" name="rec_Email">
	        </div>
	        <div>
	            <label for="join-modal-mail">Имя собаки</label>
	            <input id="join-modal-mail" type="text" name="rec_Имя собаки">
	        </div>
	        <div>
	            <label for="join-modal-file">Прикрепить резюме</label>
	            <input id="join-modal-file" type="file" name="norec">
	        </div>
	    <button>
	        Отправить
	    </button>
	</form>