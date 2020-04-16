<div class="modal fade" id="modal-quote">
    <div class="modal-dialog">
        <form method="post" action="" class="pd_quote_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{l s='Request quote' mod='pd_quote'}</h4>
                </div>
                <div class="modal-body">
                    <div class="msg">
                        <p class="alert alert-success">
                        {l s='Thank you. We will communicate soon.' mod='pd_quote'}
                        </p>
                    </div>
                    <div class="container-fluid form">
                        <div class="form-group row ">
                            <label class="col-md-3 form-control-label required">
                                {l s='Your email' mod='pd_quote'}: <sup>*</sup>
                            </label>
                            <div class="col-md-6">
                                <input class="form-control" id="pd_quote_customer_email" name="pd_quote_customer_email" type="email" value="{$pd_quote_customer_email|escape:'html':'UTF-8'}" required>  
                            </div>
                            <div class="col-md-3 form-control-comment">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-md-3 form-control-label required">
                                {l s='Your phone' mod='pd_quote'}: <sup>*</sup>
                            </label>
                            <div class="col-md-6">
                                <input class="form-control" id="pd_quote_customer_phone" name="pd_quote_customer_phone" type="number" value="{$pd_quote_customer_phone|escape:'html':'UTF-8'}" required>  
                            </div>
                            <div class="col-md-3 form-control-comment">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-md-3 form-control-label required">
                                {l s='Your message' mod='pd_quote'}: <sup>*</sup>
                            </label>
                            <div class="col-md-6">
                                <textarea class="form-control" id="pd_quote_customer_description" name="pd_quote_customer_description" type="text" value="{$pd_quote_customer_description|escape:'html':'UTF-8'}" style="height: 120px;"> 
                                </textarea> 
                            </div>
                            <div class="col-md-3 form-control-comment">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="primero"> 
                        <input type="hidden" id="pd_quote_id_product" name="pd_quote_id_product" value="{$pd_quote_id_product|escape:'html':'UTF-8'}" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Cancel' mod='pd_quote'}</button>
                        <button type="submit" class="btn btn-primary btn-lg" style="border-radius:0px;">{l s='Request' mod='pd_quote'}</button>
      
                    </div>
                    <div class="segundo"> 
                        <button type="button" class="btn btn-primary btn-lg" style="border-radius:0px;" disabled>
                            {l s='Sending' mod='pd_quote'}...
                        </button>                    
                    </div>
                    <div class="tercero"> 
                        <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='pd_quote'}</button>
                        <button type="button" class="btn btn-default" id="new-quote">{l s='New' mod='pd_quote'}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

