{**
 * 2020-2021 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    ARTULANCE.COM <artudevweb@gmail.com>
 * @copyright 2007-2020 ARTULANCE.COM and Contributors
 * @license   free
 *}
<div class="panel col-lg-10 right-panel">
    <h3>
        <i class="fa fa-wrench"></i> {l s='Configuración del cupon' mod='cupon'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
    </h3>
    <form method="post" action="{$moduleAdminLink|escape:'htmlall':'UTF-8'}&page=cuponCantidad" class="form-horizontal">
            <p>{l s='Establece a continuación la cantidad necesaria para que se haga el cupón :' mod='cupon'}</p>
            <article class="alert alert-info" role="alert" data-alert="info">
                {l s='El cupón solo va a crearse la primera vez que se llegue a la cantidad indicada' mod='cupon'} 
            </article>
            <br><br>
            {* Zona de formulario *}
            <div class="form-group">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <input class="form-control" type="number" value="{$cantidaddescuento}" name="cantidad_descuento">
                </div>
                <div style="clear: both;"></div>
                <br><br>
                <p>{l s='Establece a continuación la cantidad de descuento que llevará el cupón :' mod='cupon'}</p>
                <article class="alert alert-info" role="alert" data-alert="info">
                    {l s='Será un porcentaje del total' mod='cupon'} 
                </article>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <input class="form-control" min="1" max="99" type="number" value="{$cantidadcupon}" name="porcentaje_descuento">
                </div>
            </div>
         
        <div class="panel-footer">
            <button type="submit" value="1" id="save" name="save" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> {l s='Save' mod='cupon'}
            </button>
        </div>
    </form>
</div>