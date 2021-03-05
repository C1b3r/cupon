{*
* 2021 ARTUROKI.TK
*
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement
*
* @author    ARTUROKI.TK <artudevweb@gmail.com>
* @copyright 2021 ARTUROKI.TK
* @license   Free license 
*
*}


{block name='cupon'}
<div id="cupon_modulo" class="container">
    <div class="py-5 text-center zonacupon">
         <h2>{l s='Rasca para ver el código de su cupón de descuento' mod='cupon'}</h2>
         <div class="base">{$texto_variable|escape:'html':'UTF-8'}</div>
         <canvas id="scratch" width="150" height="150"></canvas>
      </div>
</div>
{/block}
