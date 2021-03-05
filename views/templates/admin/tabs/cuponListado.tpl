{**
 * 2007-2020 PrestaShop and Contributors
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
        <i class="fa fa-list"></i> {l s='Listado de cupones' mod='cupon'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small>
    </h3>
    <p>
        {l s='Este es el listado de los cupones que han sido creados para los usuarios' mod='cupon'}
    </p>
    <br>
    <div>
        <table id="customercupon" class="table table-striped table-bordered">
            <thead>
                <tr class="table-header">
                    <th class="text-center"><b>{l s='Id Usuario' mod='cupon'}</b></th>
                    <th class="text-center"><b>{l s='Nombre' mod='cupon'}</b></th>
                    <th class="text-center"><b>{l s='Apellidos' mod='cupon'}</b></th>
                    <th class="text-center"><b>{l s='Email' mod='cupon'}</b></th>
                    <th class="text-center"><b>{l s='Codigo' mod='cupon'}</b></th>
                    <th class="text-center"><b>{l s='Fecha' mod='cupon'}</b></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$cupones item=cupon}
                  <tr>
                    <td class="text-center">{$cupon.id_usuario|escape:'htmlall':'UTF-8'}</td>
                    <td class="text-center">{$cupon.nombre|escape:'htmlall':'UTF-8'}</td>
                    <td class="text-center">{$cupon.apellidos|escape:'htmlall':'UTF-8'}</td>
                    <td class="text-center">{$cupon.email|escape:'htmlall':'UTF-8'}</td>
                    <td class="text-center">{$cupon.code|escape:'htmlall':'UTF-8'}</td>
                    <td class="text-center">{$cupon.date_add|escape:'htmlall':'UTF-8'}</td>
                   
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
