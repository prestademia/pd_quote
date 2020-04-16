<style>
	.btn-quote {
        color 		: {$pd_quote_button_color|escape:'html':'UTF-8'};
		background	: {$pd_quote_button|escape:'html':'UTF-8'};
        width		: {$pd_quote_width|escape:'html':'UTF-8'}px;

	}
	.btn-quote:hover {
		background	: {$pd_quote_button_hover|escape:'html':'UTF-8'};
	}
</style>
<a href="#" data-toggle="modal" data-target="#modal-quote" class="btn btn-default btn-quote">
    <i class="material-icons">description</i> {l s='Request quote' mod='pd_quote'}
</a>

