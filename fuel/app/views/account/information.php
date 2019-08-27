<h2>Account Information</h2>
<p class="account-details">
	<span class="label">Auth provider:</span>
	<span class="value"><?=ucfirst($user->provider)?></span>
	<span class="label">Number of snippets:</span>
	<span class="value"><?=$user->get_num_snippets()?></span>
	<span class="label">Number of groups:</span>
	<span class="value"><?=$user->get_num_groups()?></span>
	<span class="label">API id:</span>
	<span class="value"><input class="focus-select" type="text" value="<?=$user->api_id?>" /></span>
	<span class="label">API token:</span>
	<span class="value"><input class="focus-select" type="text" value="<?=$user->api_token?>" /></span>
</p>
<h2>Sublime Text Credentials (Plugin released soon...)</h2>
<script>var read_only = true; var snippetMode = 'javascript';</script>
<div id="editor">&quot;fuelphp_bin_id&quot;: &quot;<?=$user->api_id?>&quot;,
&quot;fuelphp_bin_token&quot;: &quot;<?=$user->api_token?>&quot;,
&quot;fuelphp_bin_private&quot;: false,
// ^ set to true to paste only private snippets</div>
<h2>Account Removal</h2>
<a id="delete-account" href="<?=Uri::create('account/delete')?>">Delete My Account</a>
