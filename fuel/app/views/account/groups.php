<?php if ( ! empty($groups)){ ?>
<h2>Groups</h2>
<ul class="account-details">
<?php foreach($groups as $group){ ?>
	<li class="snippet-group">
		<a data-id="<?=$group->id?>" class="group-name" href="<?=Uri::create('groups/view/'.$group->slug)?>"><?=$group->name?></a>
		<span class="group-actions">
			<a class="group-action rename-group" href="#"><i class="icon-pencil"></i></a>
			<a class="group-action delete-group" href="#"><i class="icon-trash"></i></a>
		</span>
	</li>
<?php } ?>
</ul>
<?php } else { ?>
<h2>Oh dear lord, you have no groups!</h2>
<?php } ?>