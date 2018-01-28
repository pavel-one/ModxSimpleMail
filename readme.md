## Описание

Маленький скрипт, который в будущем возможно перерастет в крутой большой компонент который позволяет реализовать крутую отправку писем в MODX с анти-спам защитой и интеграцией в компонент CallBack

## Установка
Кидаем php - файл в корень, создаем системную настройку **ms2_email_manager** и не забываем установить компонент **CallBack**. Как использовать скрипт есть в js - файле. Форма должна обязательно иметь input name **phone** и input name **name**, также в форме должен присутствовать hidden name form_subject

	<input type="hidden" name="form_subject" value="Тема письма"/>

## Использование
Компонент подтягивает любые поля у формы, с классом **form_ajax_go**. Название поля которое отправится на почту и запишется в админку точно такое же, как и name у инпута, пример рабочей формы:

    <form class="form_ajax_go">
		<div>
		  <label>Как к вам обращаться</label>
		  <input type="text" name="name">
		</div>
		<div>
		  <label>Телефон</label>
		  <input type="phone" name="phone">
		</div>
		<div>
		  <label>Тест2</label>
		  <input type="text" name="Тест2">
		</div>
		<div>
		  <input type="checkbox"/>
		  <label>Даю согласие на&nbsp; <a href="#">обработку персональных данных</a></label>
		</div>
      <button>
        Записаться
      </button>
    </form>