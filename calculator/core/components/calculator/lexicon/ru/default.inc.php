<?php
include_once 'setting.inc.php';

$_lang["jul_email_title"] = "Заказ в ювелирной студии Juliette’s jewelry";
$_lang["jul_step1_cost_text"] = "Стоимость изделия от:&nbsp;&nbsp;";
$_lang["jul_logo"] = "app/assets/logo.svg";
$_lang["jul_background"] = "app/assets/bg.jpg";
$_lang["jul_watermark"] = "app/assets/watermark.png";
$_lang["jul_increase_btn"] = "Увеличить картинку";
$_lang["jul_decrease_btn"] = "Уменьшить картинку";
$_lang["jul_cost_text"] = "Стоимость изделия:&nbsp;&nbsp;";
$_lang["jul_back_btn"] = '<svg style="display:inline-block" xmlns="http://www.w3.org/2000/svg" width="13" height="11" fill="none" viewBox="0 0 13 11">
  <path fill="currentColor" d="M5.752 10.808.824 5.88 5.752.952h1.936L3.48 5.16h8.704V6.6H3.48l4.208 4.208H5.752Z"/>
</svg> Назад';
$_lang["jul_next_btn"] = 'Дальше <svg style="display:inline-block" xmlns="http://www.w3.org/2000/svg" width="13" height="11" fill="none" viewBox="0 0 13 11">
  <path fill="currentColor" d="M5.32 10.808 9.528 6.6H.824V5.16h8.704L5.32.952h1.936l4.928 4.928-4.928 4.928H5.32Z"/>
</svg>';
$_lang["jul_currency_sign"] = "₽";
$_lang["jul_step1_title"] = "Шаг №1 из 5";
$_lang["jul_step1_description"] = "Выберите материал изделия:";
$_lang["jul_step1_description_dop"] = "Выберите плетение цепочки:";
$_lang["jul_step2_title"] = "Шаг №2 из 5";
$_lang["jul_step2_description"] = "Выберите тип кулона:";
$_lang["jul_step2_description_dop"] = "Перетащите кулоны на цепочку";
$_lang["jul_step3_title"] = "Шаг №3 из 5";
$_lang["jul_step3_description"] = "Длина браслета, см:";
$_lang["jul_step3_description_dop"] = "Тип камней";
$_lang["jul_step4_title"] = "Шаг №4 из 5";
$_lang["jul_step4_description"] = "Стоимость гравировки одного кулона – 1 тыс.<br>Добавить гравировку (по желанию):";
$_lang["jul_step5_title"] = "Шаг №5 из 5";
$_lang["jul_step5_description"] = "Оформите заказ.";
$_lang["jul_form_text"] = '<p class="text-xs font-light lg:text-sm">* Обязательно для заполнения</p><p class="text-xs font-light lg:text-sm">Нажимая “Дальше” вы соглашаетесь с <a href="" class="underline hover:no-underline">политикой обработки персональных данных</a>.</p>';
$_lang["jul_cost_engraving"] = "1000";
$_lang["jul_max_items"] = "9";
$_lang["jul_label_name"] = "Ф.И.О";
$_lang["jul_label_phone"] = "Ваш телефон*";
$_lang["jul_label_email"] = "Ваш e-mail";
$_lang["jul_label_address"] = "Адрес доставки (индекс, город, улица, дом, квартира)";
$_lang["jul_label_message"] = "Комментарий к заказу";
$_lang["jul_phone_mask"] = "#(###)###-##-##";
$_lang["jul_phone_regexp"] = "^\+7\([3489]{1}[0-9]{2}\)[0-9]{3}-[0-9]{2}-[0-9]{2}$";
$_lang["jul_final_message"] = '<div class="flex flex-col items-center gap-4 text-center">
	<h2 class="text-3xl uppercase">Благодарим за заказ!</h2>
	<p class="text-xl">Менеджер свяжется с вами в самое ближайшее время по Whats App / Telegram или по телефону, <br>а пока подпишитесь на наши странички в соц.сетях, чтобы не пропустить новинки и выгодные предложения</p>
	<div class="flex justify-center gap-4">
		<a href="https://www.instagram.com/mamy_braslets/" class="flex h-[70px] w-[70px] rounded-full border border-lgray bg-white" target="_blank">
			<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 36 36">
				<path
					fill="#A4A1A1"
					d="M27.775 9.571a1.348 1.348 0 0 1-1.346-1.346V5.533c0-.742.604-1.346 1.346-1.346h2.692c.742 0 1.346.604 1.346 1.346v2.692c0 .742-.604 1.346-1.346 1.346h-2.692ZM18.351 12.517c-.977 0-1.879.286-2.656.758a2.54 2.54 0 0 1 .76-.126 2.603 2.603 0 1 1-2.602 2.604c0-.268.052-.52.126-.76a5.074 5.074 0 0 0-.76 2.655 5.13 5.13 0 0 0 5.134 5.133 5.132 5.132 0 1 0-.002-10.264Z" />
				<path
					fill="#A4A1A1"
					d="M18.351 9.571a8.077 8.077 0 1 1 0 16.155 8.077 8.077 0 0 1 0-16.155Zm0-2.692c-5.938 0-10.769 4.831-10.769 10.77 0 5.937 4.831 10.768 10.77 10.768 5.937 0 10.768-4.831 10.768-10.769s-4.83-10.77-10.769-10.77Z" />
				<path
					fill="#A4A1A1"
					d="M29.29.148H7.413A6.561 6.561 0 0 0 .852 6.711v21.875a6.561 6.561 0 0 0 6.562 6.562H29.29a6.561 6.561 0 0 0 6.563-6.562V6.71A6.561 6.561 0 0 0 29.289.148Zm3.87 12.116h-8.8a8.036 8.036 0 0 1 2.069 5.384 8.077 8.077 0 0 1-16.154 0c0-2.072.786-3.955 2.069-5.385h-8.8V6.712a3.875 3.875 0 0 1 3.87-3.87H29.29a3.874 3.874 0 0 1 3.87 3.87v5.553Z" />
			</svg>
		</a>
		<a href="https://vk.com/juliettes_jewelry" class="flex h-[70px] w-[70px] rounded-full border border-lgray bg-white" target="_blank">
			<svg class="m-auto" xmlns="http://www.w3.org/2000/svg" width="43" height="26" fill="none" viewBox="0 0 43 26">
				<path
					fill="#A4A1A1"
					d="M23.69 26C9.34 26 1.155 16.24.814 0h7.188c.237 11.92 5.536 16.969 9.733 18.01V0h6.768v10.28c4.145-.442 8.5-5.127 9.969-10.28h6.768c-1.128 6.35-5.85 11.035-9.208 12.96C35.39 14.524 40.768 18.61 42.815 26h-7.451c-1.6-4.945-5.588-8.77-10.86-9.291V26h-.814Z" />
			</svg>
		</a>
	</div>
	<a
		href="https://juliettes.ru/"
		class="inline-flex h-[3.44rem] w-[13.19rem] items-center justify-center rounded-xl border border-mred bg-mred font-semibold text-white transition-colors hover:border-lgray hover:bg-white hover:text-mred"
		>Вернуться на сайт <svg style="display:inline-block" xmlns="http://www.w3.org/2000/svg" width="13" height="11" fill="none" viewBox="0 0 13 11">
  <path fill="currentColor" d="M5.32 10.808 9.528 6.6H.824V5.16h8.704L5.32.952h1.936l4.928 4.928-4.928 4.928H5.32Z"/>
</svg></a
	>
	<div class="flex flex-col gap-4 md:flex-row">
		<a href="https://wa.clck.bar/79096795961" class="flex h-[3.44rem] w-[13.19rem] items-center justify-center rounded-xl border border-mgray text-sm font-medium leading-tight text-mgray lg:w-[15.75rem] lg:text-base" target="_blank">Связаться с менеджером<br />по Whats App </a>
		<a href="tel:88002015938" class="flex h-[3.44rem] w-[13.19rem] items-center justify-center rounded-xl border border-mgray text-sm font-medium leading-tight text-mgray lg:w-[15.75rem] lg:text-base"
			>Позвонить нам<br>8 800 201-59-38</a>
	</div>
</div>';
