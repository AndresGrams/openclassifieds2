<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="well">
    <h3><?=__('Latest Ads')?></h3>
    <ul class="thumbnails">

        <?foreach($ads as $ad):?>
        <li class="span3">
            <div class="thumbnail latest_ads" style="height: 300px; overflow: hidden;">
                
                <?if($img_path[$ad->seotitle] != NULL):?>
                <?foreach($img_path[$ad->seotitle] as $key => $value):?>
                    <?if(strstr($value, 'thumb') && strstr($value, '_1')):?>
                        <?$thumb = $value;?>
                    <?endif?>
                <?endforeach?>
                <img src="/<?=$thumb?>" class="img-polaroid">
                <?endif?>
                <div class="caption">
                    <h5><a href="<?=Route::url('ad', array('controller'=>'ad','category'=>$ad->category->seoname,'seotitle'=>$ad->seotitle))?>"><?=$ad->title?></a></h5>

                    <p ><?=substr(Text::removebbcode($ad->description), 0, 30)?></p>

                </div>
            </div>
        </li>     
        <?endforeach?>
    </ul>
</div>
<div class='well'>
    <h3><?=__("Categories")?></h3>
    <ul class="thumbnails">
        <?foreach($categ as $c):?>
        <?if($c->id_category_parent == 1 && $c->id_category != 1):?>
        <div class="span4">
            <div class="category_box_title">
                <p><a title="<?=$c->name?>" href="<?=Route::url('list', array('category'=>$c->seoname))?>"><?=strtoupper($c->name);?></a></p>
            </div>  
            <div class="well custom_box_content" style="padding: 8px 0;">
                <ul class="nav nav-list">
                    <?foreach($children_categ as $chi):?>
                        <?if($chi['parent'] == $c->id_category):?>
                        <li><a title="<?=$chi['name']?>" href="<?=Route::url('list', array('category'=>$chi['name']))?>"><?=$chi['name'];?> <span class="count_ads"><span class="badge badge-success"><?=$chi['count']?></span></span></a></li>
                        <?endif?>
                     <?endforeach?>
                </ul>
            </div>
        </div>
        <?endif?>
        <?endforeach?>
    </ul>
</div>


