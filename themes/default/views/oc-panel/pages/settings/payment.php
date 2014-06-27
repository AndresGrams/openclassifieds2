<?php defined('SYSPATH') or die('No direct script access.');?>

	
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Payments Configuration')?></h1>
            <p class=""><?=__('List of payment configuration values. Replace input fields with new desired values.')?></p>
            <?if (Theme::get('premium')!=1):?>
                <p class="well"><span class="label label-info"><?=__('Heads Up!')?></span> 
                    <?=__('Stripe, Paymill and Bitpay are only available with premium themes!').'<br/>'.__('Upgrade your Open Classifieds site to activate this feature.')?>
                    <a class="btn btn-success pull-right" href="<?=Route::url('oc-panel',array('controller'=>'theme'))?>"><?=__('Browse Themes')?></a>
                </p>
            <?endif?>
		</div>


		<div class="well">
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($config as $c):?>
					<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
				<?endforeach?>

                <div class="form-group">
                    <?= FORM::label($forms['paypal_account']['key'], __('Paypal account'), array('class'=>'control-label col-sm-3', 'for'=>$forms['paypal_account']['key']))?>
                    <div class="col-sm-4">
                        <?= FORM::input($forms['paypal_account']['key'], $forms['paypal_account']['value'], array(
                        'placeholder' => "some@email.com", 
                        'class' => 'tips form-control', 
                        'id' => $forms['paypal_account']['key'],
                        'data-original-title'=> __("Paypal mail address"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-content'=>__("The paypal email address where the payments will be sent"), 
                        ))?> 
                        </div>
                </div>

				<div class="form-group">
					<?= FORM::label($forms['sandbox']['key'], __('Sandbox'), array('class'=>'control-label col-sm-3', 'for'=>$forms['sandbox']['key']))?>
					<div class="col-sm-4">
                        <div class="onoffswitch">
                            <?= Form::checkbox($forms['sandbox']['key'], 1, (bool) $forms['sandbox']['value'], array(
                            'placeholder' => "TRUE or FALSE", 
                            'class' => 'onoffswitch-checkbox', 
							'id' => $forms['sandbox']['key'],
							'data-content'=> '',
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>'', 
                            ))?>
                            <?= FORM::label($forms['sandbox']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['sandbox']['key']))?>
                        </div>
					</div>
				</div>
				<div class="form-group">
					<?= FORM::label($forms['paypal_currency']['key'], __('Paypal currency'), array('class'=>'control-label col-sm-3', 'for'=>$forms['paypal_currency']['key']))?>
					<div class="col-sm-4">
						<?= FORM::select($forms['paypal_currency']['key'], $paypal_currency , array_search($forms['paypal_currency']['value'], $paypal_currency), array(
						'placeholder' => "USD", 
						'class' => 'tips form-control', 
						'id' => $forms['paypal_currency']['key'], 
						'data-original-title'=> __("Currency"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-content'=>__("Please be sure you are using a currency that paypal supports."),
						))?> 
					</div>
				</div>
				<div class="form-group">
					<?= FORM::label($forms['to_featured']['key'], __('Featured Ads'), array('class'=>'control-label col-sm-3', 'for'=>$forms['to_featured']['key']))?>
					<div class="col-sm-4">
						<?= FORM::select($forms['to_featured']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE") ,$forms['to_featured']['value'], array(
						'placeholder' => '', 
						'class' => 'tips form-control', 
						'id' => $forms['to_featured']['key'],
						'data-original-title'=> __("Featured ads"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-content'=>__("Featured ads will be highlighted for a defined number of days."), 

						))?> 
					</div>
				</div>
                <div class="form-group">
                    <?= FORM::label($forms['pay_to_go_on_feature']['key'], __('Price for featuring the Ad'), array('class'=>'control-label col-sm-3', 'for'=>$forms['pay_to_go_on_feature']['key']))?>
                    <div class="col-sm-4">
                        <div class="input-group">
                        
                            <?= FORM::input($forms['pay_to_go_on_feature']['key'], $forms['pay_to_go_on_feature']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control col-sm-3', 
                            'id' => $forms['pay_to_go_on_feature']['key'],
                            'data-original-title'=> __("Pricing"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("How much the user needs to pay to feature an Ad"),  
                            ));?> 
                        
                        <span class="input-group-addon"><?=core::config('payment.paypal_currency')?></span></div>
                    </div>
                </div>
                <div class="form-group">
                <?= FORM::label($forms['featured_days']['key'], __('Days Featured'), array('class'=>'control-label col-sm-3', 'for'=>$forms['featured_days']['key']))?>
                <div class="col-sm-4">
                    <div class="input-group">
                        <?= FORM::input($forms['featured_days']['key'], $forms['featured_days']['value'], array(
                        'placeholder' => $forms['featured_days']['value'], 
                        'class' => 'tips form-control col-sm-3', 
                        'id' => $forms['featured_days']['key'], 
                        'data-original-title'=> __("Featured length"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-content'=>__("How many days an ad will be featured after paying."),
                        ));?>
                        <span class="input-group-addon"><?=__("Days")?></span>
                    </div> 
                </div>
            </div>
				<div class="form-group">
					<?= FORM::label($forms['to_top']['key'], __('Bring to top Ad'), array('class'=>'control-label col-sm-3', 'for'=>$forms['to_top']['key']))?>
					<div class="col-sm-4">
						<?= FORM::select($forms['to_top']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE") ,$forms['to_top']['value'], array(
						'placeholder' => "", 
						'class' => 'tips form-control', 
						'id' => $forms['to_top']['key'], 
						'data-original-title'=> __("Bring to top Ad"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-content'=>__("Brings your Ad to the top of the listing."), 
						))?> 
					</div>
				</div>
				<div class="form-group">
					<?= FORM::label($forms['pay_to_go_on_top']['key'], __('To top price'), array('class'=>'control-label col-sm-3', 'for'=>$forms['pay_to_go_on_top']['key']))?>
					<div class="col-sm-4">
						<div class="input-group">
							<?= FORM::input($forms['pay_to_go_on_top']['key'], $forms['pay_to_go_on_top']['value'], array(
							'placeholder' => "", 
							'class' => 'tips form-control col-sm-3', 
							'id' => $forms['pay_to_go_on_top']['key'],
							'data-original-title'=> __("Pricing"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-content'=>__("How much the user needs to pay to top up an Ad"),  
							));?> 
								<span class="input-group-addon"><?=core::config('payment.paypal_currency')?></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= FORM::label($forms['paypal_seller']['key'], "<a target='_blank' href='http://open-classifieds.com/2013/09/02/pay-directly-from-ad/'>".__('User paypal link')."</a>", array('class'=>'control-label col-sm-3', 'for'=>$forms['paypal_seller']['key']))?>
					<div class="col-sm-4">
                        <div class="onoffswitch">
                            <?= Form::checkbox($forms['paypal_seller']['key'], 1, (bool) $forms['paypal_seller']['value'], array(
                            'placeholder' => "TRUE or FALSE", 
                            'class' => 'onoffswitch-checkbox', 
							'id' => $forms['paypal_seller']['key'],
							'data-content'=> '',
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-original-title'=>'', 
                            ))?>
                            <?= FORM::label($forms['paypal_seller']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['paypal_seller']['key']))?>
                        </div>
					</div>
				</div>	
                <div class="form-group">
                    <?= FORM::label($forms['stock']['key'], __('Paypal link stock control'), array('class'=>'control-label col-sm-3', 'for'=>$forms['stock']['key']))?>
                    <div class="col-sm-4">
                        <?= FORM::select($forms['stock']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['stock']['value'], array(
                        'placeholder' => "", 
                        'class' => 'tips form-control', 
                        'id' => $forms['stock']['key'], 
                        'data-original-title'=> __("Paypal link stock control"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-content'=>__("Paypal link stock control"),
                        ))?>  
                    </div>
                </div>	

                <div class="form-group">
                    <?= FORM::label($forms['alternative']['key'], __('Alternative Payment'), array('class'=>'col-md-3 control-label', 'for'=>$forms['alternative']['key']))?>
                    <div class="col-md-5">
                        <?= FORM::select($forms['alternative']['key'], $pages, $forms['alternative']['value'], array( 
                        'class' => 'tips form-control', 
                        'id' => $forms['alternative']['key'], 
                        'data-content'=> __("A button with the page title appears next to other pay button, onclick model opens with description."),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>__("Alternative Payment"),
                        ))?> 
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-5 col-md-offset-3">
                        <p>To get paid via Credit card you need a Paymill account. It's free to register. They charge 2'95% of any sale.</p>
                        <a class="btn btn-success" target="_blank" href="https://app.paymill.com/en-en/auth/register?referrer=openclassifieds">
                            <i class="glyphicon glyphicon-pencil"></i> Register for free at Paymill</a>
                    </label>
                </div>
                <div class="form-group">
                    
                    <?= FORM::label($forms['paymill_private']['key'], __('Paymill private key'), array('class'=>'col-md-3 control-label', 'for'=>$forms['paymill_private']['key']))?>
                    <div class="col-md-5">
                        <?= FORM::input($forms['paymill_private']['key'], $forms['paymill_private']['value'], array(
                        'placeholder' => "", 
                        'class' => 'tips form-control', 
                        'id' => $forms['paymill_private']['key'],
                        'data-content'=> __("Paymill private key"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>'', 
                        ))?> 
                        </div>
                </div>

                <div class="form-group">
                    <?= FORM::label($forms['paymill_public']['key'], __('Paymill public key'), array('class'=>'col-md-3 control-label', 'for'=>$forms['paymill_public']['key']))?>
                    <div class="col-md-5">
                        <?= FORM::input($forms['paymill_public']['key'], $forms['paymill_public']['value'], array(
                        'placeholder' => "", 
                        'class' => 'tips form-control', 
                        'id' => $forms['paymill_public']['key'],
                        'data-content'=> __("Paymill public key"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>'', 
                        ))?> 
                        </div>
                </div>

                <div class="form-group">
                    <label class="col-md-5 col-md-offset-3">
                        <p>To get paid via Credit card you can also use a Stripe account. It's free to register. They charge 2'95% of any sale.</p>
                        <a class="btn btn-success" target="_blank" href="https://stripe.com">
                            <i class="glyphicon glyphicon-pencil"></i> Register for free at Stripe</a>
                    </label>
                </div>
                <div class="form-group">
                    
                    <?= FORM::label($forms['stripe_private']['key'], __('Stripe private key'), array('class'=>'col-md-3 control-label', 'for'=>$forms['stripe_private']['key']))?>
                    <div class="col-md-5">
                        <?= FORM::input($forms['stripe_private']['key'], $forms['stripe_private']['value'], array(
                        'placeholder' => "", 
                        'class' => 'tips form-control', 
                        'id' => $forms['stripe_private']['key'],
                        'data-content'=> __("Stripe private key"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>'', 
                        ))?> 
                        </div>
                </div>

                <div class="form-group">
                    <?= FORM::label($forms['stripe_public']['key'], __('Stripe public key'), array('class'=>'col-md-3 control-label', 'for'=>$forms['stripe_public']['key']))?>
                    <div class="col-md-5">
                        <?= FORM::input($forms['stripe_public']['key'], $forms['stripe_public']['value'], array(
                        'placeholder' => "", 
                        'class' => 'tips form-control', 
                        'id' => $forms['stripe_public']['key'],
                        'data-content'=> __("Stripe public key"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>'', 
                        ))?> 
                        </div>
                </div>

                <div class="form-group">
                    <?= FORM::label($forms['stripe_address']['key'], __('Requires address to pay for extra security'), array('class'=>'col-md-3 control-label', 'for'=>$forms['stripe_address']['key']))?>
                    <div class="col-md-5">
                        <div class="onoffswitch">
                            <?= Form::checkbox($forms['stripe_address']['key'], 1, (bool) $forms['stripe_address']['value'], array(
                            'placeholder' => "TRUE or FALSE", 
                            'class' => 'onoffswitch-checkbox', 
                            'id' => $forms['stripe_address']['key'], 
                            'data-content'=> '',
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-original-title'=>'',                     
                            ))?>
                            <?= FORM::label($forms['stripe_address']['key'], "<span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span>", array('class'=>'onoffswitch-label', 'for'=>$forms['stripe_address']['key']))?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-5 col-md-offset-3">
                        <p>Accept bitcoins using Bitpay</p>
                        <a class="btn btn-success" target="_blank" href="https://bitpay.com">
                            <i class="glyphicon glyphicon-pencil"></i> Register for free at Bitpay</a>
                    </label>
                </div>
                <div class="form-group">
                    
                    <?= FORM::label($forms['bitpay_apikey']['key'], __('Bitpay api key'), array('class'=>'col-md-3 control-label', 'for'=>$forms['bitpay_apikey']['key']))?>
                    <div class="col-md-5">
                        <?= FORM::input($forms['bitpay_apikey']['key'], $forms['bitpay_apikey']['value'], array(
                        'placeholder' => "", 
                        'class' => 'tips form-control', 
                        'id' => $forms['bitpay_apikey']['key'],
                        'data-content'=> __("Bitpay api key"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-original-title'=>'', 
                        ))?> 
                        </div>
                </div>

				
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
				
			</fieldset>	
	</div><!--end col-md-10-->
