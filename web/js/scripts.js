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

		newButton : null,

		templates : {

			newDoc : $('#folder-row-new').length > 0 ? _.template($('#folder-row-new').html()) : null

		},

		newDoc : function(element){

			if($(element).attr('disabled'))
				return;

			this.newButton = $(element).attr('disabled','');

			var row = this.templates.newDoc({ });

			$('tbody', this.table).append(row).find('input').focus();

		},

		create : function(form){

			$.post(
				window.location.href,
				{
					csrf : FRONTEND.csrf,
					fileName : $('input[name="fileName"]', form).val()
				},
				function(data){

					data = $.parseJSON(data);

					$(form).parent().append($('<a></a>').addClass('folder').html(data.relativePath).attr('href', data.url));
					$(form).remove();

				}

			).error(function(){

				$('.label', form).html('File already exists.').show();

			}).complete(function(){

				$(FRONTEND.folder.newButton).removeAttr('disabled');

			});

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

			$.post(
				window.location.href,
				{ csrf : FRONTEND.csrf, data : this.editor.getSession().getDocument().getValue() },
				function(data){

					$('#original').html(data);
					$(element).removeAttr('disabled').addClass('btn-success');

				}
			).error(function(){

				alert('There has been an error saving.');
				$(element).removeAttr('disabled').addClass('btn-danger');

			}).complete(function(){

				setTimeout(function(){

					$(element).removeClass('btn-danger').removeClass('btn-success');

				}, 2000);

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