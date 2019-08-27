<?=$header?>

<div id="account" class="column">
	<h1><?=$user->name?></h1>

	<ul id="sidebar">
		<li><a <?=Uri::segment(1) === 'account' ? 'class="active"' : ''?> href="<?=Uri::create('account')?>">You</a></li>
		<li><a <?=Uri::segment(1) === 'search' ? 'class="active"' : ''?> href="<?=Uri::create('search')?>">Search Snippets</a></li>
		<li><a <?=Uri::segment(1) === 'groups' ? 'class="active"' : ''?> href="<?=Uri::create('groups')?>">Groups</a></li>
	</ul>

	<div id="information">
		<?=$information?>
	</div>
</div>

<?=$footer?>