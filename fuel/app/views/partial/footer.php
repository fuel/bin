<?php if ($message = Session::get_flash('message')){ ?>
<script>
	var message = <?=json_encode($message)?>
</script>
<?php } ?>
	</body>
</html>