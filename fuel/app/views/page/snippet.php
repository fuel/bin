<?=$header?>
<form id="snippet-form" method="post" action="<?=$form_action?>">
	<div id="action-bar">
		<div id="snippet-info">
<?php if ( ! $read_only) { ?>
			<a href="#" id="option-name"><i class="icon-pencil"></i> <span class="value"><?=$name?></span></a>
			<a href="#" id="option-private"><i class="icon-<?=$private ? 'lock' : 'unlock'?>"></i> <span class="value"><?=$private ? 'Private' : 'Public'?></span></a>
			<a href="#" id="option-mode"><i class="icon-magic"></i> <span class="value"><?=$modes[$mode]?></span></a>
<?php if ($user) { ?>
			<a href="#" id="option-group"><i class="icon-tag"></i> <span class="value">No group</span></a>
<?php } } else { ?>
<?php if ($name and $url) { ?>
			<label>Name </label><a href="<?=$url?>"><?=$name?></a>
<?php } ?>
			<label for="snippet-url">Url: </label><input type="text" class="focus-select" value="<?=$url?>" />
<?php } ?>
		</div>
		<div id="snippet-actions">
<?php if ($read_only and $is_forkable){ ?>
			<a href="<?=$snippet->get_fork_url()?>">Fork</a>
<?php } ?>
<?php if ($read_only and $parent){ ?>
			<a href="<?=$parent->get_url()?>">Parent</a>
<?php } ?>
<?php if ( ! $read_only){ ?>
			<a href="#" id="snippet-save"><i class="icon-save"></i> Save</a>
<?php } ?>
		</div>
	</div>
	<div id="editor"><?=htmlentities($code)?></div>
	<input type="hidden" id="snippet-code" name="code" value="" />
	<input type="hidden" id="snippet-name" name="name" value="<?=$name?>" />
	<input type="hidden" id="snippet-group" name="group" value="<?=$group?>" />
	<input type="hidden" id="snippet-private" name="private" value="<?=$private?>" />
	<input type="hidden" id="snippet-mode" name="mode" value="<?=$mode?>" />
<?php if ($is_fork) { ?>
	<input type="hidden" id="snippet-parent_id" name="parent_id" value="<?=$id?>" />
<?php } ?>
</form>
<script>
	var modes = <?=json_encode($modes)?>;
	var snippetMode = '<?=$mode?>';
	var groups = <?=json_encode($groups)?>;
	var read_only = <?=json_encode($read_only)?>;
</script>
<?=$footer?>