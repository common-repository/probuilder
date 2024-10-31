(function($)
{
	var pac = probuilder_ajax_custom;

	if($('#probuilder-editor').length)
	{
		refreshStorage();		
	}

	$('#probuilder-editor button#save_changes').on('click', function(e)
	{
		e.preventDefault();
		var save_changes = this;
		var dom_path = $('#probuilder-editor input[name="dom-path"]');
		var mq = $('#probuilder-editor select[name="media-query"]') || '';

		if(!$('#probuilder-editor #global-checkbox').is(':checked'))
		{
			dom_path.val();
		}

		$.ajax(
		{
			type: "post",
			url: pac.ajax_url,
			data: { action: 'probuilder_ajax_save_css', nonce: $('#probuilder-editor #save_changes').attr('data-nonce'), path: dom_path.val(), css: codeMirror.getValue(), mq: mq.val() },
			beforeSend: function()
			{
				$('#probuilder-editor .editor-container button#save_changes').addClass('loading');
			},
			complete: function()
			{

			},
			success: function(xml)
			{
				var status = $(xml).find('response_data').text();
				var message = $(xml).find('supplemental message').text();
				if(status === 'error')
				{
					alert(message);
				}
				else
				{
					var button = $('#probuilder-editor .editor-container button#save_changes');
					$(button).addClass('fast');
					refreshStorage();
					window.setTimeout(function()
					{
						$(button).removeClass('loading').removeClass('fast');
						$('#probuilder-editor').removeClass('visible');
					},500);			
				}
				$('#all-probuilds-container #show-all-probuilds').trigger('refreshProbuildList');
			}
		});
	});

	$('body').on('click', '#all-probuilds #all-probuilds-list li a.trash', function()
	{
		var elem = $(this);
		var elemId = elem.attr('data-id');
		var deleteConfirm = window.confirm(probuilder_ajax_custom.remove_confirm_txt);
		if(deleteConfirm === true)
		{
			$.ajax(
			{
				type: "post",
				url: pac.ajax_url,
				data: { action: 'probuilder_ajax_remove_elem', nonce: elem.attr('data-nonce'), id: elem.attr('data-id') },
				success: function(xml)
				{
					var status = $(xml).find('response_data').text();
					var data = $(xml).find('supplemental data').text();
					if(status === 'success')
					{					
						elem.parent().addClass('deleted');
						refreshStorage(true);
					}
					else alert(data);
				}
			});
		}
	});

	$('#all-probuilds-container #show-all-probuilds').on('click refreshProbuildList', function()
	{
		var elem = $(this);
		if(!$('#all-probuilds-container').hasClass('open'))
		{
			$('#all-probuilds').prepend('<div id="all-probuilds-spinner" class="probuilder-spinner"></div>');
		}		
		$.ajax(
		{
			type: "post",
			url: pac.ajax_url,
			data: { action: 'probuilder_ajax_get_all_probuilds', nonce: elem.attr('data-nonce') },
			success: function(xml)
			{
				var status = $(xml).find('response_data').text();
				var data = $(xml).find('supplemental data').text();
				var results = $.parseJSON(data);
				var out = '<ul id="all-probuilds-list">';
				if(status === 'success')
				{				
					$(results).each(function(i,elem)
					{	
						var mq;					
						if(this.mq !== '')
						{
							mq = Probuilder.extractNumbers(this.mq);
							if(mq[0] != undefined && mq[1] != undefined)
							{
								mq = '('+mq[0]+' - '+mq[1]+')';
							}
							else if(mq[0].length)
							{
								mq = '(< '+mq[0]+')';
							}
							else if(mq[1].length)
							{
								mq = '(> '+mq[1]+')';
							}
						}
						else mq = '';
						out+= '<li><i class="fa fa-chevron-circle-right"></i> <a href="javascript:;" data-id="'+this.id+'" data-mq="'+this.mq+'">'+this.path+'</a><a href="javascript:;" class="trash" data-id="'+this.id+'" data-nonce="'+this.nonce+'"><i class="fa fa-trash"></i></a><span> '+mq+'</span></li>';
					});
					out+= '</ul>';
					$('#all-probuilds-container #all-probuilds').html(out);
				}
				$('#all-probuilds-spinner').remove();
			}
		});
	});

	function refreshStorage(replace)
	{
		replace = replace === true ? true : false;

		$.ajax(
		{
			type: "post",
			url: pac.ajax_url,
			data: { action: 'probuilder_ajax_init_storage', nonce: $('#probuilder-editor').attr('data-nonce') },
			success: function(xml)
			{
				var status = $(xml).find('response_data').text();
				var data = $(xml).find('supplemental data').text();
				if(status === 'success')
				{					
					var css = $.parseJSON(data);
					var css_out = [];
					$(css).each(function(i,elem)
					{
						css_out.push(
						{
							path: this.path, css_code: this.css_code, mq: this.mq
						});
					});
					if (typeof(Storage) !== "undefined")
					{
						window.sessionStorage.setItem("css_elem", JSON.stringify(css_out));
						if(replace)
						{
							var code_replacement = '';
							$(css_out).each(function(i,elem)
							{
								code_replacement+= this.css_code;				
							});
							$('style#probuilder-style-tag').html(code_replacement);
						}
					}
					else
					{
						$('#probuilder-old-browser-detected').show();
					}
				}
			}
		});
	}
})(jQuery);