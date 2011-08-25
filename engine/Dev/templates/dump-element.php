<li class="dump-item">

	<div class="dump-element">
			<a class="dump-name dump-inline"><?=$elementname;?></a>
			(<strong class="dump-type dump-inline"><?=$type?></strong>)
			<em class="dump-<?=$type_class?> dump-inline"><?=$value;?></em>
	</div>

    <?=isset($dump) ? $dump:''?>
        
</li>