<li class="dump-item">

	<div class="dump-element">
		
			<a class="dump-name dump-inline"><?=$name;?></a>
			(<em class="dump-type dump-inline"><?=$type?></em>)
			<strong class="dump-<?=$type_class?> dump-inline"><?=$element;?></strong>
	</div>

    <?=isset($dump) ? $dump:''?>
        
</li>