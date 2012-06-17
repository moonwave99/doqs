/**
*	Part of doqs - simple Markdown Documents Repository.
*	@author Diego Caponera <diego.caponera@gmail.com>
*	@link https://github.com/moonwave99/doqs
*	@copyright Copyright 2012 Diego Caponera
*	@license http://www.opensource.org/licenses/mit-license.php MIT License
*/

FRONTEND = {

	csrf : null,

	common : {

		init : function(){

			FRONTEND.csrf = $('meta[name="csrf"]').attr('content');

			// Bind buttons	to controllers
			$(document).on('click', 'button[data-controller], input[type="button"][data-controller]', function(){

				return BASE.exec(
					$(this).attr('data-controller'),
					$(this).attr('data-action'),
					this
				);

			});

			// Bind forms to controllers
			$(document).on('submit', '.homepage-form', function(){

				BASE.exec(
					$(this).attr('data-controller'),
					$(this).attr('data-action'),
					this
				);

				return false;

			});

			// Bind links to controllers
			$(document).on('click', 'a[data-controller], .clickable[data-controller]', function(){

				BASE.exec(
					$(this).attr('data-controller'),
					$(this).attr('data-action'),
					this
				);

				return false;

			});

		}

	},

	folder : {

		table : $('#folder-table'),

		newFileButton : null,

		newFolderButton : null,

		templates : {

			doc : $('#folder-doc').length > 0 ? _.template($('#folder-doc').html()) : null ,
			folder : $('#folder-folder').length > 0 ? _.template($('#folder-folder').html()) : null ,
			parentFolder : $('#folder-folder-parent').length > 0 ? _.template($('#folder-folder-parent').html()) : null ,
			newDoc : $('#folder-new-doc').length > 0 ? _.template($('#folder-new-doc').html()) : null ,
			newFolder : $('#folder-new-folder').length > 0 ? _.template($('#folder-new-folder').html()) : null ,
			editDoc : $('#folder-edit-doc').length > 0 ? _.template($('#folder-edit-doc').html()) : null ,
			editFolder : $('#folder-edit-folder').length > 0 ? _.template($('#folder-edit-folder').html()) : null ,

		},

		fetch : function(table){

			$.getJSON(window.location.url, { format : 'json' }, function(data){

				_.each(data.folders, function(f, i){

					$('tbody', table).append(
						i == 0 && !data.root ?
							FRONTEND.folder.templates.parentFolder({ folder : f })
						: 	FRONTEND.folder.templates.folder({ folder : f })
					);

				});

				_.each(data.files, function(f, i){

					$('tbody', table).append(FRONTEND.folder.templates.doc({ doc : f }));

				});

			});

		},

		newDoc : function(element){

			if($(element).attr('disabled'))
				return;

			this.newFileButton = $(element).attr('disabled','');

			var row = this.templates.newDoc({ });

			$('tbody', this.table).append(row).find('input').focus();

		},

		newFolder : function(element){

			if($(element).attr('disabled'))
				return;

			this.newFolderButton = $(element).attr('disabled','');

			var row = this.templates.newFolder({ });

			$('tbody', this.table).append(row).find('input').focus();

		},

		createDoc : function(form){

			$.post(
				window.location.href,
				{
					csrf : FRONTEND.csrf,
					fileName : $('input[name="resName"]', form).val()
				},
				function(data){

					$(form).closest('tr').replaceWith(FRONTEND.folder.templates.doc({ doc : data }));

				}

			).error(function(){

				$('.label', form).html('File already exists.').show();

			}).complete(function(){

				$(FRONTEND.folder.newFileButton).removeAttr('disabled');

			});

		},

		createFolder : function(form){

			$.post(
				window.location.href,
				{
					csrf : FRONTEND.csrf,
					folderName : $('input[name="resName"]', form).val()
				},
				function(data){

					$(form).closest('tr').replaceWith(FRONTEND.folder.templates.folder({ folder : data }));

				}

			).error(function(){

				$('.label', form).html('File already exists.').show();

			}).complete(function(){

				$(FRONTEND.folder.newFolderButton).removeAttr('disabled');

			});

		},

		editDoc : function(element){

			var path = $(element).closest('tr').attr('data-path').split('/');

			var row = this.templates.editDoc({
				name : path.pop(),
				path : path.join('/')
			});

			$(element).closest('tr').hide().after(row).next().find('input').focus();

		},

		editFolder : function(element){

			var path = $(element).closest('tr').attr('data-path').split('/');

			var row = this.templates.editFolder({
				name : path.pop(),
				path : path.join('/')
			});

			$(element).closest('tr').hide().after(row).next().find('input').focus();

		},

		saveDoc : function(form){

			$.ajax({
				url : basePath + $(form).closest('tr').prev().attr('data-path'),
				type : 'PUT',
				data : { csrf : FRONTEND.csrf, resName : $(form).closest('tr').attr('data-path')+"/"+$('input[name="resName"]', form).val() },
				success : function(data){

					$(form).closest('tr').prev().replaceWith(FRONTEND.folder.templates.doc({ doc : data }));
					$(form).closest('tr').remove();

				},
				error : function(){

					$('.label', form).html('File already exists.').show();

				},

				complete : function(){

				}
			});

		},

		saveFolder : function(form){

			$.ajax({
				url : basePath + $(form).closest('tr').prev().attr('data-path'),
				type : 'PUT',
				data : { csrf : FRONTEND.csrf, resName : $(form).closest('tr').attr('data-path')+"/"+$('input[name="resName"]', form).val() },
				success : function(data){

					$(form).closest('tr').prev().replaceWith(FRONTEND.folder.templates.folder({ folder : data }));
					$(form).closest('tr').remove();

				},
				error : function(){

					$('.label', form).html('Folder already exists.').show();

				},

				complete : function(){

				}
			});

		},

		deleteDoc : function(element){

			if(!confirm('You cannot undo this, are you sure?'))
				return;

			$.ajax({
				url : basePath + $(element).closest('tr').attr('data-path'),
				type : 'DELETE',
				data : { csrf : FRONTEND.csrf },
				success : function(data){

					$(element).closest('tr').hide('slow', function(){ $(this).remove(); });

				},
				error : function(){



				},

				complete : function(){

				}
			});

		},

		deleteFolder : function(element){

			if(!confirm('You cannot undo this, are you sure?'))
				return;

			$.ajax({
				url : basePath + $(element).closest('tr').attr('data-path'),
				type : 'DELETE',
				data : { csrf : FRONTEND.csrf },
				success : function(data){

					$(element).closest('tr').hide('slow', function(){ $(this).remove(); });

				},
				error : function(){



				},

				complete : function(){

				}
			});

		},

		editCancel : function(element){

			$(element).closest('tr').hide().prev().show().next().remove();

		},

		createCancel : function(element){

			$(element).closest('tr').remove();

			$(this.newButton).removeAttr('disabled');

		}

	},

	docs : {

		editor : null,

		isEditing : false,

		editButton : null,

		showEditPane : function(element){

			if(this.isEditing)
				return;

			this.isEditing = true;
			this.editButton = $(element).attr('disabled','');

			$.get(
				window.location.href,
				{ csrf : FRONTEND.csrf, raw : 1 },
				function(data){

					if(!FRONTEND.docs.editor){

						FRONTEND.docs.editor = ace.edit("editor");
						FRONTEND.docs.editor.setTheme("ace/theme/twilight");
						FRONTEND.docs.editor.setShowPrintMargin(false);
						FRONTEND.docs.editor.getSession().setUseWrapMode(true);
						FRONTEND.docs.editor.getSession().setWrapLimitRange();

					}

					FRONTEND.docs.editor.getSession().getDocument().setValue(data);

					$('#box').show();

				}
			);

		},

		hideEditPane : function(element){

			$('#box').hide();

			$(this.editButton).removeAttr('disabled');
			this.isEditing = false;

		},

		save : function(element){

			$(element).attr('disabled', '');

			$.ajax({
				url : window.location.href,
				type : 'PUT',
				data : { csrf : FRONTEND.csrf, data : this.editor.getSession().getDocument().getValue() },
				success : function(data){

					$('#original').html(data);
					$(element).removeAttr('disabled').addClass('btn-success');

				},
				error : function(){

					alert('There has been an error saving.');
					$(element).removeAttr('disabled').addClass('btn-danger');

				},

				complete : function(){

					setTimeout(function(){

						$(element).removeClass('btn-danger').removeClass('btn-success');

					}, 2000);

				}
			});

		}

	}

};

BASE = {

	exec: function(controller, action, elements) {
    	var ns = FRONTEND,
			action = ( action === undefined ) ? "init" : action;
		if ( controller !== "" && ns[controller] && typeof ns[controller][action] == "function" ) {
      		return ns[controller][action](elements);
		}
	},

	init: function() {
		// Run common setup
    	BASE.exec("common");
		// Run startup actions [if any]
		$.each($('[data-startup]'), function(){
			BASE.exec(
				$(this).attr('data-controller'),
				$(this).attr('data-action'),
				this
			);
		});
  	}

}

$(document).ready(BASE.init);