<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{$title}</title>    
</head>
<body style="font-family: Arial, sans-serif;margin: 0;padding: 0;font-weight: 400;font-size: 1rem;line-height: 1.2;color: #000;">
    <div style="max-width: 600px; background: #ffffff; margin: 20px auto; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <div class="logo-block" style="padding: 4.22rem 0 1.3rem 3.8rem;">
            <img src="cid:em_logo" alt="Juliette's Jewelry">
        </div>
        <div class="main-block" style="padding: 1.2rem 3.8rem;background-color: #f7f7f7;">
            <h2 class="block-title" style="font-weight: 700;font-size: 1.19rem;text-transform: uppercase;margin-bottom: 1.3rem;">Здравствуйте, вас приветствует ювелирная студия Juliette’s jewelry!</h2>            
            <p>Получили ваш заказ и уже начали его обработку. Мы свяжемся с вами в ближайшее время по WhatsApp или по телефону.</p>
            
            <div class="section" style="margin: 20px 0;">
                <h3 class="block-title" style="font-weight: 700;font-size: 1.19rem;text-transform: uppercase;margin-bottom: 1.3rem;">ИНФОРМАЦИЯ ПО ВАШЕМУ ЗАКАЗУ:</h3>
                <ul class="list" style="padding: 0;list-style-position: inside;">
                {if $name?}<li>ФИО: {$name}</li>{/if}
                {if $phone?}<li>Телефон: {$phone}</li>{/if}
                {if $email?}<li>E-mail: {$email}</li>{/if}
                {if $address?}<li>Адрес доставки: {$address}</li>{/if}
                {if $text?}<li>Комментарий: {$text}</li>{/if}
                </ul>
            </div>
            
            <div class="section" style="margin: 20px 0;">
                <h3 class="block-title" style="font-weight: 700;font-size: 1.19rem;text-transform: uppercase;margin-bottom: 1.3rem;">СОСТАВ ЗАКАЗА:</h3>
                <ol class="list" style="padding: 0;list-style-position: inside;">
                    {set $bracelet = 0}
                    {set $stoneprice = 0}
                    {set $engraving = 0}                    
                    {foreach $items as $it}                        
                        <li>{$it.tabname} {$it.name}, {$chain.tabname}</li>
                        {set $bracelet = $bracelet + $it.price}                        
                        {if $it.stoneName?}
                            {set $stonename = $it.stoneName}
                            {set $stoneprice = $stoneprice+$it.stonePrice-$it.price}                                 
                        {/if}
                        {if $it.text?}
                            {set $engraving = $engraving+$cost_engraving}
                        {/if}                        
                    {/foreach}       
                </ol>               
            </div>
            
            <div class="section" style="margin: 20px 0;">
                <p>{if $stonename?}Тип камней: {$stonename}<br>{/if}               
                Металл цепи: {$chain.tabname}<br>Плетение цепи: {$chain.name}<br>Длина цепи: {$chain.length} см.<br></p>             
            </div>
            {if $engraving > 0}
            <div class="section" style="margin: 20px 0;">
                <h3 class="block-title" style="font-weight: 700;font-size: 1.19rem;text-transform: uppercase;margin-bottom: 1.3rem;">Гравировка:</h3>
                <ol class="list" style="padding: 0;list-style-position: inside;">                    
                    {foreach $items as $it}                        
                    <li>{if $it.text?}{$it.text}{else}Без гравировки{/if} </li>
                    {/foreach}
                </ol>               
            </div>
            {/if}
            <div class="section" style="margin: 20px 0;">
                <h3 class="block-title" style="font-weight: 700;font-size: 1.19rem;text-transform: uppercase;margin-bottom: 1.3rem;">ИНФОРМАЦИЯ ПО ВАШЕМУ ЗАКАЗУ:</h3>
                <ul class="list" style="padding: 0;list-style-position: inside;">
                    {set $fullCost = $bracelet + $chain.price}
                    <li>Браслет: {$fullCost | number:0:'':' '}р.</li>
                    {if $stoneprice > 0}
                        <li>Камни: {$stoneprice | number:0:'':' '}р.</li>
                    {/if}
                    {if $engraving > 0}
                        <li>Гравировка {$engraving/$cost_engraving}шт.: {$engraving | number:0:'':' '}р.</li>
                    {/if}
                    <li>Итого: {$cost | number:0:'':' '}р.</li>
                </ul>
            </div>

            <p>Стоимость доставки рассчитает менеджер при оформлении заказа.</p>
            
            <img src="cid:order_image" alt="" style="width:100%;">            
            
            <h3 class="block-title" style="text-align: center;font-weight: 700;font-size: 1.19rem;text-transform: uppercase;margin-bottom: 1.3rem;">Благодарим, что выбрали <br>Juliette`s jewelry!</h3>
            <p style="font-size: 0.81rem;"><i>Если мы не связались в течение 12 часов, пожалуйста, позвоните по <br>номеру <strong>8 800 201-59-38</strong> или напишите на Whats app <strong>+7 909 679 59 61</strong>.</i></p>
            
            <div class="section" style="margin: 20px 0;">                
                <p><a class="pink" href="tel:88002015938" style="color: #f8937a;">8 800 201-59-38</a> (бесплатный звонок по России)</p>
                <p><a class="pink" href="tel:89096795961" style="color: #f8937a;">8 909 679 59 61</a></p>
                <p><a class="pink" href="mailto:info@juliettes.ru" style="color: #f8937a;">info@juliettes.ru</a></p>
                <p><a class="pink" href="https://juliettes.ru" style="color: #f8937a;">https://juliettes.ru</a></p>
            </div>            
            <table border="0" cellpadding="0" cellspacing="0" id="templateContainer" style="-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse !important;margin-top: 20px;">
                <tr>
                    <td class="pr-7" style="padding-right: 0.7rem;">
                        <a href="https://wa.clck.bar/79096795961">
                            <img src="cid:whatsapp" alt="WhatsApp" style="max-width:100%;">
                        </a>
                    </td>
                    <td class="pr-7" style="padding-right: 0.7rem;">
                        <a href="viber://add?number=79096795961">
                            <img src="cid:viber" alt="Viber" style="max-width:100%;">
                        </a>
                    </td>
                    <td class="pr-7" style="padding-right: 0.7rem;">
                        <a href="https://t.me/Juliettes_jewelry">
                            <img src="cid:telegram" alt="Telegram" style="max-width:100%;">
                        </a>
                    </td>
                    <td class="pr-7" style="padding-right: 0.7rem;">
                        <a href="https://www.instagram.com/mamy_braslets/">
                            <img src="cid:instagram" alt="Instagram" style="max-width:100%;">
                        </a>
                    </td>
                    <td>
                        <a href="https://vk.com/juliettes_jewelry">
                            <img src="cid:vk" alt="ВКонтакте" style="max-width:100%;">
                        </a>
                    </td>
                </tr>                
            </table>
            <p style="font-size: 10px;">Вы получили это письмо, так как сделали заказа на сайте Juliettes.ru и оставили согласие на уведомления. Если произошла ошибка и вы не делали заказ, пожалуйста, просто игнорируйте это письмо или напишите ответным письмом, что заказ не делали.</p>
        </div>
    </div>
</body>
</html>
