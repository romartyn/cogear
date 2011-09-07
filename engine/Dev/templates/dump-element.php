<li class="dump-item">

	<div class="dump-element <?if(isset($dump)):?> dump-element-nested <?endif;?>" <?if(isset($dump)):?>onclick="debug.toggle(this);"<?endif;?>>
			<a class="dump-name dump-inline"  ><?=$elementname;?></a>
			<strong class="dump-type dump-inline">(<?=$type?>)</strong>
			<em class="dump-<?=$type_class?> dump-inline"><?=highlight_string($value,true);?></em>
	</div>

    <?=isset($dump) ? $dump:''?>
        
</li>