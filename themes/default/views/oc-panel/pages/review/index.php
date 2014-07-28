<?php defined('SYSPATH') or die('No direct script access.');?>

<form class="form-inline" method="get" action="<?=URL::current();?>">
  	<div class="form-group pull-right">
  		<div class="">
	      	<input type="text" class="form-control search-query" name="email" placeholder="<?=__('email')?>" value="<?=core::get('email')?>">
		</div>
	</div>
</form>

<div class="page-header">
	<h1><?=__('Reviews')?></h1>
</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
	            <th>#</th>
	            <th><?=__('User') ?></th>
	            <th><?=__('Ad') ?></th>
	            <th><?=__('Rate') ?></th>
	            <th><?=__('Date') ?></th>
				<th><?=__('Edit') ?></th>
			</tr>
		</thead>
		<tbody>
			<?foreach($reviews as $review):?>
				<tr id="tr<?=$review->pk()?>">
	                <td><?=$review->pk()?></td>
	                <td><a href="<?=Route::url('profile', array('seoname'=> $review->user->seoname, 'action'=>'reviews')) ?>">
	                    <?=$review->user->name?></a> - <?=$review->user->email?>
	                </td>
	                <td><a href="<?=Route::url('ad-review', array('seoname'=> $review->ad->seoname)) ?>">
	                    <?=$review->ad->title?></a></td>
	                <td><?=$review->rate?></td>
	                <td><?=$review->created?></td>
					<td width="80px">
						<?if ($controller->allowed_crud_action('update')):?>
						<a title="<?=__('Edit')?>" class="btn btn-primary" href="<?=Route::url('oc-panel', array('controller'=> Request::current()->controller(), 'action'=>'update','id'=>$review->pk()))?>">
							<i class="glyphicon glyphicon-edit"></i>
						</a>
						<?endif?>
					</td>
				</tr>
			<?endforeach?>
		</tbody>
	</table>
</div>
<?=$pagination?>