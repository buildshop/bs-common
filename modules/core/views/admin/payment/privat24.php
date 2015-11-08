<form action="https://api.privatbank.ua/p24api/ishop" method="POST" accept-charset="UTF-8">
    <input type="hidden" name="amt" value="<?=$price?>"/>
    <input type="hidden" name="ccy" value="UAH" />
    <input type="hidden" name="merchant" value="110541" />
    <input type="hidden" name="order" value="<?= CMS::gen(10) ?>" />
    <input type="hidden" name="details" value="Продление интернет-магазина pro.buildshop.net на 5 месяцов" />
    <input type="hidden" name="ext_details" value="PLAN_PRO" />
    <input type="hidden" name="pay_way" value="privat24" />
    <input type="hidden" name="return_url" value="http://pro.buildshop.net/admin/core/service/success" />
    <input type="hidden" name="server_url" value="http://pro.buildshop.net/admin/core/service/success" />
    <input type="submit" value="Оплатить" class="btn btn-success" />
</form>