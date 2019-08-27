<h2><a href="<?=Uri::create('groups')?>">Groups</a> / <?=$group->name?></h2>
<div id="search-results">
<?php foreach ($snippets as $snippet) { ?>
	<a class="search-result" href="<?=$snippet->get_url()?>">
		<span class="name"><?=$snippet->get_name()?></span>
		<span class="url"><?=$snippet->get_short_url()?></span>
	</a>
<?php } ?>
</div>