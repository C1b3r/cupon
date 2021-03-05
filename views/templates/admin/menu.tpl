<div id="modulecontent" class="clearfix">
    <div id="cupon-menu">
        <div class="col-lg-2">

                <div class="list-group" v-on:click.prevent>
                    <a href="#" id="link-config" data-attribute="config" onclick="cambiamenu(this)" class="list-group-item active"><i class="fa fa-check-square"></i> {l s='Configuración de cantidades' mod='cupon'}</a>
                    <a href="#" id="link-listado" data-attribute="listado" onclick="cambiamenu(this)" class="list-group-item"><i class="fa fa-user-circle"></i> {l s='Listado de cupones' mod='cupon'}</a>

                </div>

                  

            <div class="list-group" v-on:click.prevent>
                <a class="list-group-item" style="text-align:center"><i class="icon-info"></i> {l s='Version' mod='cupon'} {$module_version|escape:'htmlall':'UTF-8'} | <i class="icon-info"></i> PrestaShop {$ps_version|escape:'htmlall':'UTF-8'}</a>
            </div>
        </div>
    </div>

    {* list your admin tpl *}


    <div id="config"  class="tab-pane container">
        {include file="./tabs/cuponCantidades.tpl"}
    </div>

    <div id="listado" style="display: none;" class="tab-pane container">
        {include file="./tabs/cuponListado.tpl"}
    </div>



</div>


