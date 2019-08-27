<ul>
	<li><a href="<?=Uri::create('account')?>"><?=$user->name?></a></li>
	<li><a class="signout-button" href="<?=Uri::create('auth/logout')?>"><i class="icon-signout"></i></a>
</ul>