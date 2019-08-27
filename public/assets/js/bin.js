function ucfirst(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function elementExists(selector)
{
	return $(selector).length !== 0;
}

require(['underscore', 'jquery', 'widget/options', 'widget/message', 'widget/click-confirm'], function (_, $, Options, Message, Confirm) {
	$(function(){
		var mode;
		var editor;

		$(".focus-select").on('focus', function(e){
			var input = $(this);
			setTimeout(function(){
				input.select();
			}, 5);
		}).on('keydown', function(e){
			if ( ! e.ctrlKey && ! e.metaKey) {
				e.preventDefault();
			}
		});

		if (elementExists('#search-form')) {
			require(['widget/search'], function(Search) {
				new Search();
			});
		}

		if (elementExists('.rename-group')) {
			$(".rename-group").each(function(){
				console.log(this);
				new Options({
					button: $(this),
					title: 'Set new name',
					placeholder: 'new name...',
					onChange: function(value, button, widget){
						if (value.length === 0) {
							return;
						}
						var group = button.parent().parent().find('.group-name');
						var id = group.data('id');
						$.ajax({
							type: 'POST',
							data: {
								id: id,
								new_name: value
							},
							dataType: 'json',
							url: '/groups'
						}).done(function(){
							group.html(value);
						}).fail(function(){
							console.log(arguments);
							require(['widget/message'], function(Message) {
								new Message({
									message: 'Could not update group name.',
									type: 'error'
								});
							});
						});
					}
				});
			});
		}

		if (elementExists('.delete-group')) {
			$(".delete-group").each(function(){
				var item = $(this).parent().parent();
				var name = item.find('.group-name');
				var id = name.data('id');
				new Confirm({
					button: $(this),
					header: 'Delete: '+name.text(),
					message: 'Realy delete this group?'
				});
				$(this).on('click', function(){
					$.ajax({
						type: 'post',
						url: '/delete',
						data: {
							id: id
						}
					}).done(function(){
						new Message({
							message: 'Group was deleted',
							label: 'Hooray! '
						});
						item.animate({
							opacity: 0
						},250, function(){
							$(this).slideUp(150, function(){
								$(this).remove();
							});
						});
					}).fail(function(){
						new Message({
							message: 'Could not delete the group.',
							type: 'error'
						});
					});
					(new Confirm({
						//button: $(this),
						header: 'Also delete it\'s snippets?',
						label_confirm: 'Yes',
						label_cancel: 'No, thanks!',
						onConfirm: function(){
							alert(1);
							$.ajax({
								type: 'post',
								url: '/account/delete_group_snippets',
								data: {
									id: id
								}
							}).done(function(){
								new Message({
									message: 'We deleted the snippets...',
									type: 'Yay: '
								});
							}).fail(function(){
								new Message({
									message: 'Could not delete the snippets.',
									type: 'error'
								});
							});
						}
					})).show();
				});
			});
		}

		if (window.message) {
			require(['widget/message'], function(Message) {
				new Message(message);
			});
		}

		if (elementExists('#editor')) {
			require(['ace/ace'], function(ace){
				editor = ace.edit("editor");
				editor.setTheme("ace/theme/monokai");
				editor.getSession().setTabSize(4);
				editor.setDisplayIndentGuides(false);
				editor.getSession().setUseSoftTabs(false);
				editor.setShowPrintMargin(false);
				editor.getSession().setUseWorker(false);
				editor.setReadOnly(read_only);
				editor.getSession().on('change', _.debounce(function(){
					$("#snippet-code").val(editor.getValue());
				}, 300));
				$("#snippet-code").val(editor.getValue());

				editor.commands.addCommand({
					name: 'Save',
					bindKey: {
						win: 'Ctrl-s',
						mac: 'Command-s'
					},
					exec: function(editor) {
						$("#snippet-save").click();
					},
					readOnly: false
				});

				if ( ! read_only)
					editor.focus();

				$(window).on('resize', _.debounce(function(){
					editor.resize();
				}, 300));
				$(".oauth-button").on('click', function(){
					$(this).find('i').addClass('icon-spin');
				});


				if (elementExists('#option-group')) {
					new Options({
						button: '#option-group',
						input: '#snippet-group',
						options: groups,
						title: 'Choose Group',
						placeholder: 'new group...',
						showInput: true,
						onChange: function(value, button, widget) {
							var label = button.find('.value');
							if (value.length === 0) {
								return label.html('No group');
							}
							label.html(value);
						}
					});
				}

				if (elementExists('#option-name')) {
					new Options({
						button: '#option-name',
						input: '#snippet-name',
						title: 'Name the snippet',
						showInput: true,
						placeholder: 'snippet name...',
						onChange: function(value, button, widget) {
							var label = button.find('.value');
							if (value.length === 0) {
								return label.html('Not named');
							}
							label.html(value);
						}
					});
				}

				if (elementExists('#option-mode')) {
					mode = new Options({
						button: '#option-mode',
						title: 'Select a language',
						showInput: false,
						showDelete: false,
						options: modes,
						onChange: function(value, button, widget) {
							var label = button.find('.value');
							label.html(modes[value]);
							editor.getSession().setMode("ace/mode/"+value);
							$("#snippet-mode").val(value);
						}
					});
				}

				if (snippetMode && snippetMode.length > 0) {
					if (elementExists('#option-mode')) {
						mode.setOption(snippetMode);
					} else if (elementExists('#editor')) {
						editor.getSession().setMode("ace/mode/"+snippetMode);
					}

				}

				$("#option-private").on('click', function(e){
					e.preventDefault();
					var private = $("#snippet-private");
					var button = $(this);
					var icon = button.find('i');
					var label = button.find('.value');
					icon.removeClass('icon-lock icon-unlock');
					if (private.val() == 1) {
						icon.addClass('icon-unlock');
						label.html('Public');
						private.val(0);
					} else {
						icon.addClass('icon-lock');
						label.html('Private');
						private.val(1);
					}
				});

				$("#editor").on('click', '.ace_gutter-cell', function(e){
					var line = $(e.target).text();
					window.location.hash = '#line-'+line;
				});

				$("#snippet-save").on('click', function(e){
					e.preventDefault();
					if (editor.getValue().length === 0) {
						return;
					}
					$('#snippet-form').submit();
				});

				var hsh = window.location.hash;
				hsh = hsh.replace('#', '');
				if (hsh.substr(0, 2) === 'L_') {
					editor.gotoLine(hsh);
				}

				$("#action-bar").fadeIn(150);
			});
		}


	});
});