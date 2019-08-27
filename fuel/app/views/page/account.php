<?=$header?>

<div id="account" class="column">
	<h1><?=$user->name?></h1>

	<ul id="sidebar">
		<li><a <?=Uri::segment(2) === 'me' ? 'class="active"' : ''?> href="<?=Uri::create('account/me')?>">You</a></li>
		<li><a <?=Uri::segment(2) === 'search' ? 'class="active"' : ''?> href="<?=Uri::create('account/search')?>">Search Snippets</a></li>
		<li><a <?=Uri::segment(2) === 'groups' ? 'class="active"' : ''?> href="<?=Uri::create('account/groups')?>">Groups</a></li>
	</ul>

	<div id="information">
		<?=$information?>
	</div>
</div>

<?=$footer?>