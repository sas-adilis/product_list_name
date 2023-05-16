<input type="hidden" name="product_list_name_update" value="1">
<fieldset class="form-group">
    <label class="form-control-label">{l s='Titre alternatif dans les listes produits' mod='product_list_name'}</label>
    <div class="translations tabbable">
        <div class="translationsFields tab-content">
            {foreach from=$languages item=language }
                <div class="tab-pane translation-field translation-label-{$language.iso_code} {if $default_language == $language.id_lang}show active{/if}">
                    <input type="text" name="product_list_name_lang_{$language.id_lang}" class="form-control" {if isset({$product_list_name[$language.id_lang]}) && {$product_list_name[$language.id_lang]} != ''}value="{$product_list_name[$language.id_lang]}"{/if}/>
                </div>
            {/foreach}
        </div>
    </div>
</fieldset>