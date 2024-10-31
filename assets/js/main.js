var Probuilder = function($)
{
	var plugin_url = probuilder_custom.plugin_url;
	var probuilder_nonce = probuilder_custom.probuilder_nonce;
	var highlight_color = probuilder_custom.highlight_color;
	var current_value = probuilder_custom.current_value_txt;

	var startSelection = function()
	{
		var elem = elem !== undefined ? elem : null;
		var elemPath = elemPath !== undefined ? elemPath : null;
		$('body').addClass('probuilder-edit-mode');

		$(window).on('hover.probuilderEvent',function(e)
		{			
			$('#probuilder-overlay').remove();     	

		    var x = e.clientX,
		    	y = e.clientY,		    	
		    	elem = DomManipulation.getElement(x, y);
		    if(!elem.length) return;
		    var elemX = elem.offset().left,
		    	elemY = elem.offset().top,
		    	elemWidth = elem.outerWidth(),
		    	elemHeight = elem.outerHeight();
		    

		    if(!elem.parents('#probuilder-editor').length && elem.attr('id') !== 'probuilder-editor' && !elem.parents('#all-probuilds-container').length && elem.attr('id') !== 'all-probuilds-container')
		    {
		    	$('#probuilder-toggle-dom-path').remove();

			    $('body').prepend('<div id="probuilder-overlay"></div>');
			    $('#probuilder-overlay').width(elemWidth).height(elemHeight).offset({ left: elemX, top: elemY }).css('background-color', highlight_color);
	    		elemPath = DomManipulation.getPath(elem);

	    		$('#probuilder-toggle').after('<div id="probuilder-toggle-dom-path">'+elemPath+'</div>');
	    	}
		});
		$('body').on('click.probuilderEvent', '*', function(e)
		{	
			var x = e.clientX,
				y = e.clientY,
		    	elem = DomManipulation.getElement(x, y);
		    if(!elem.length) return;

			if(!elem.parents('#probuilder-toggle').length && !elem.parents('#probuilder-editor').length && elem.attr('id') !== 'probuilder-editor' && !elem.parents('#all-probuilds-container').length && elem.attr('id') !== 'all-probuilds-container')
		    {
		    	e.preventDefault();
		    	var elemPath = DomManipulation.getPath(elem);
				$('#probuilder-editor').addClass('visible');
				$('#probuilder-editor input[name="dom-path"]').val(elemPath);
				$('#probuilder-editor select[name="selectors"],#probuilder-editor select[name="media-query"]').prop('selectedIndex',0);
				$('#probuilder-editor #global-checkbox').prop('checked',false);
				
				DomManipulation.selectClickElement(elemPath);			
			}
		});
		$('#probuilder-editor #probuilder-go-parent-element').on('click', function(e)
		{
			e.stopImmediatePropagation();
			var parentElement = $('body').find($('#probuilder-editor input[name="dom-path"]').val()).parent();
			$(parentElement).pulsate(
			{
				color: highlight_color,
				reach: 35,
				speed: 500,
				glow: false,
				pause: 150,
				repeat: 3
			});
			$('#probuilder-editor input[name="dom-path"]').val(DomManipulation.getPath(parentElement));
		});
	};
	var endSelection = function()
	{
		$(window).unbind('hover.probuilderEvent');
		$('*').unbind('click.probuilderEvent');
		$('body').removeClass('probuilder-edit-mode');
	};
	var initDraggable = function(elem)
	{
		return elem.on("mousedown", function(e)
		{
        	var dragElem = $(this).addClass('active-handle').parent().addClass('draggable');
            var dragHeight = dragElem.outerHeight(),
                dragWidth = dragElem.outerWidth(),
                dragPosX = dragElem.offset().left + dragWidth - e.pageX,
                dragPosY = dragElem.offset().top + dragHeight - e.pageY;
            dragElem.parents().on("mousemove", function(e)
            {
                $('.draggable').offset(
                {
                    top:e.pageY + dragPosY - dragHeight,
                    left:e.pageX + dragPosX - dragWidth
                }).on("mouseup", function()
                {
                    $(this).removeClass('draggable');
                });
            });
            e.preventDefault();
        }).on("mouseup", function()
        {
        	$(this).removeClass('active-handle').parent().removeClass('draggable');
        });
	};
	var initTabs = function()
	{
		$('#probuilder-editor ul.probuilder-tab-links li').on('click', function()
		{
			var tabID = $(this).attr('data-tab');
			$('#probuilder-editor ul.probuilder-tab-links li,#probuilder-editor .probuilder-tab').removeClass('current');
			$('#' + tabID).addClass('current');
			$(this).addClass('current');
			codeMirror.refresh();
		});
		$('#probuilder-editor ul.probuilder-vertical-tab-links li').on('click', function()
		{
			if(!$(this).hasClass('probuilder-pro-badge'))
			{
				var tabID = $(this).attr('data-tab');
				$('#probuilder-editor ul.probuilder-vertical-tab-links li,#probuilder-editor .probuilder-vertical-tabs').removeClass('current');
				$('#' + tabID).addClass('current');
				$(this).addClass('current');
			}			
		});
		$('#show-all-probuilds').on('click', function()
		{
			$(this).parent().toggleClass('open');
		});
		$('body').on('hover', '#all-probuilds-container #all-probuilds #all-probuilds-list > li', function()
		{
			$('#probuilder-overlay-sec').remove();
			var path = $(this).children('a:not(.trash)')[0].innerText;
		    if(!$(path).length) return;
		    var elemX = $(path).offset().left,
		    	elemY = $(path).offset().top,
		    	elemWidth = $(path).outerWidth(),
		    	elemHeight = $(path).outerHeight();
			
			$('body').prepend('<div id="probuilder-overlay-sec"></div>');
			$('#probuilder-overlay-sec').width(elemWidth).height(elemHeight).offset({ left: elemX, top: elemY }).css('background-color', highlight_color);
			window.setTimeout(function()
			{
				$('#probuilder-overlay-sec').fadeOut(500);
			},2500);
		});
	};
	var selectElement = function()
	{
		$('#probuilder-editor #probuilder-set-selectors').on('click', function()
		{
			var selectors = $('#probuilder-editor select[name="selectors"]');
			var domPath = window.sessionStorage.getItem('probuilder-dom-path');
			var domPathInput = $('#probuilder-editor input[name="dom-path"]');
			var separator = selectors.val() == '' ? '' : ':';
			domPathInput.val(domPath + separator + $('#probuilder-editor select[name="selectors"]').val());
			domPathInput.focus();
			domPathInput[0].setSelectionRange(domPathInput[0].value.length, domPathInput[0].value.length);
		});
	}
	var initCodemirror = function()
	{
		codeMirror = CodeMirror(function(elem)
		{
			$('#probuilder-css-output').parent().replaceWith(elem);
		},
		{
			value: $('#probuilder-css-output').val(),
			autoRefresh:true,
			lineWrapping: true,
			readOnly: 'nocursor'
		});

		codeMirror.on('change', function(codeMirror,obj)
		{
			DomManipulation.updateCode(codeMirror.getValue(), $('#probuilder-editor .editor-container select[name="media-query"]').val());
			window.sessionStorage.setItem('probuilder-current-css-code',codeMirror.getValue());
		});

		codeMirror_input = CodeMirror.fromTextArea(document.getElementById('probuilder-css-input'),
		{
			value: $('#probuilder-css-input').val(),
			autoRefresh:true,
			lineWrapping: true,
			mode: 'css'
		});

		codeMirror_input.on('change', function(codeMirror,obj)
		{
			DomManipulation.changingInput($('#probuilder-editor #probuilder-css-input'));
		});
	};
	var initAllProbuilds = function()
	{
		var dom_path = $('#probuilder-editor input[name="dom-path"]');

		$('body').on('click', '#all-probuilds #all-probuilds-list li > a:not(.trash)', function()
		{
			var list_val = $(this).text();
			var mq = $(this).attr('data-mq');
			$('#probuilder-editor').addClass('visible');
			$('#probuilder-editor select[name="selectors"]').val('');
			DomManipulation.selectClickElement(list_val,mq);
		});

	};
	var handleInput = function()
	{
		$('#probuilder-editor .probuilder-vertical-tabs-container-right input,#probuilder-editor .probuilder-vertical-tabs-container-right select').on('mouseover', function()
		{
			var inputName = $(this).attr('name');
			$('#probuilder-editor .probuilder-vertical-tabs-container-right #current-info-text').remove();
			$('#probuilder-editor .probuilder-vertical-tabs-container-right .current-info-text-visible').removeClass('current-info-text-visible');
			$('#probuilder-editor .probuilder-vertical-tabs-container-right label[for="'+inputName+'"]').addClass('current-info-text-visible').append('<small id="current-info-text">'+current_value+': '+$($('#probuilder-editor input[name="dom-path"]').val()).css(inputName)+'</small>');
		});
		$('#probuilder-editor .probuilder-vertical-tabs-container-right input,#probuilder-editor .probuilder-vertical-tabs-container-right select,#probuilder-editor input[name="dom-path"],#probuilder-editor select[name="media-query"], #probuilder-editor select[name="selectors"],#probuilder-editor #probuilder-css-input').on('change input', function(elem)
		{
			DomManipulation.changingInput(elem);
		});
		$('#probuilder-editor input[name="dom-path"],#probuilder-editor select[name="media-query"], #probuilder-editor select[name="selectors"]').on('change input', function(elem)
		{
			DomManipulation.selectClickElement($('#probuilder-editor input[name="dom-path"]').val(),$('#probuilder-editor select[name="media-query"]').val());
		});
	};
	var DomManipulation = 
	{
		getPath: function(elem)
		{
			var path, node = elem;
		    while (node.length)
		    {
		        var realNode = node[0];
		        var name = (realNode.localName || realNode.tagName || realNode.nodeName);
		        if (!name || name == '#document') break;
		        name = name.toLowerCase();
		        if (realNode.id)
		        {
		            pathOut = name + '#' + realNode.id + (path ? '>' + path : '');
		            return pathOut;
		        }
		        else if (realNode.className)
		        {
		            name += '.' + realNode.className.split(/\s+/).join('.');
		        }

		        var parent = node.parent(), siblings = parent.children(name);
		        if (siblings.length > 1) name += ':nth-of-type(' + (siblings.index(node)+1) + ')';
		        path = name + (path ? '>' + path : '');

		        node = parent;
		    }
		    return path;
		},
		selectClickElement: function(elemPath,mq)
		{
			mq = mq || '';
			$('#probuilder-editor input[name="dom-path"]').val(elemPath);
			window.sessionStorage.setItem('probuilder-dom-path',elemPath);
			var css_elem = $.parseJSON(window.sessionStorage.getItem('css_elem'));
			codeMirror.setValue('');
			$('#probuilder-editor .probuilder-vertical-tabs-container-right input,#probuilder-editor .probuilder-vertical-tabs-container-right select,#probuilder-editor .probuilder-vertical-tabs-container-right textarea, #probuilder-editor #probuilder-css-input').val('');
			
			$(css_elem).each(function(i,elem)
			{
				if(elemPath === this.path && mq === this.mq)
				{
					var beautified = cssbeautify(this.css_code, {
					    indent: '	',
					    openbrace: 'separate-line',
					    autosemicolon: true
					});
					codeMirror.setValue(beautified);
					DomManipulation.setDefaultValues(this.css_code, this.mq);
				}					
			});
			window.setTimeout(function()
			{
				var code_replacement = '';
				var css_elem = $.parseJSON(window.sessionStorage.getItem('css_elem'));
				$(css_elem).each(function(i,elem)
				{
					if(this.mq)
					{
						mq = DomManipulation.convertMQ(this.mq);
						this.css_code = mq + '{' + this.css_code + '}';
					}
					code_replacement+= this.css_code;				
				});
				$('style#probuilder-style-tag').html(code_replacement);
			},500);
		},
		changingInput: function(elem)
		{
			var code = '';
			var css_input = codeMirror_input.getValue();
			var extracted_css_input = DomManipulation.extractCssAttributes(css_input);
			var code_arr = [];
			var code_arr_unique = [];

			$('#probuilder-editor .probuilder-vertical-tabs-container-right input,#probuilder-editor .probuilder-vertical-tabs-container-right select,#probuilder-editor .probuilder-vertical-tabs-container-right textarea').each(function(i,elem)
			{
				if(elem.value)
				{
					//code+= $(elem).attr('data-val')+':'+elem.value+';\n';
					code_arr.push(
					{
						elem_val: elem.value, elem_key: $(elem).attr('data-val')
					});
				}
			});
			
			$(extracted_css_input).each(function(i,elem)
			{
				var splitted = this.split(':');
				code_arr.push(
				{
					elem_val: splitted[1], elem_key: splitted[0]
				});
			});

			code_arr_unique = code_arr.reduce(function(memo, e1)
			{
				var matches = memo.filter(function(e2)
				{
			    	return e1.elem_val == e2.elem_val && e1.elem_key == e2.elem_key
				})
			  	if (matches.length == 0)
			  	{
			    	memo.push(e1);
			  	}
			    return memo;
			},[]);

			$.each(code_arr_unique, function(i,elem)
			{
				if(elem.elem_val != '')
				{
					code+= elem.elem_key+':'+elem.elem_val+';\n';
				}
			});
			
			selector = DomManipulation.createSelector($('#probuilder-editor input[name="dom-path"]').val(),code);
			var beautifiedSelector = cssbeautify(selector, {
			    indent: '	',
			    openbrace: 'separate-line',
			    autosemicolon: true
			});
			codeMirror.setValue(beautifiedSelector);
			if(elem.selector === '#probuilder-editor #probuilder-css-input')
			{
				DomManipulation.setDefaultValues(code);
			}
		},
		getElement: function(x,y)
		{
			var elem = document.elementFromPoint(x, y);
			return $(elem);
		},
		createSelector: function(selector,code)
		{
			return selector+'{'+code.replace(/(\r\n|\n|\r)/gm,"")+'}';
		},
		updateCode: function(code,mq)
		{
			mq = mq || '';
			var code_replacement = '';
			var css_elem = $.parseJSON(window.sessionStorage.getItem('css_elem'));
			$(css_elem).each(function(i,elem)
			{
				if(this.mq)
				{
					mq2 = DomManipulation.convertMQ(this.mq);
					this.css_code = mq2 + '{' + this.css_code + '}';
				}
				code_replacement+= this.css_code;				
			});
			if(mq)
			{
				mq = DomManipulation.convertMQ(mq);
				code_replacement = code_replacement + mq + '{' + code + '}';
			}
			else code_replacement = code_replacement + code;
			$('style#probuilder-style-tag').html(code_replacement);
		},
		convertMQ: function(mq)
		{
			var out = '@media(';			
			if(mq.match(/bt-(.*)-(.*)/g))
			{
				var mq_exec = /bt-(.+)-(.+)/g.exec(mq);
				out+= 'min-width: ' + mq_exec[1] + ') and ';
				out+= '(max-width: ' + mq_exec[2];
			}
			else out+= mq.replace(/\s/g,'').replace(/st-(.*)/g,'max-width: $1').replace(/lt-(.*)/g,'min-width: $1');
			out+= ')';
			return out;
		},
		extractNumbers: function(mq)
		{
			var out = [];			
			if(mq.match(/bt-(.*)-(.*)/g))
			{
				var mq_exec = /bt-(.+)-(.+)/g.exec(mq);
				out[0] = mq_exec[1];
				out[1] = mq_exec[2];
			}
			else
			{
				if(mq.match(/st-(.*)/g))
				{
					var mq_exec = /st-(.+)/g.exec(mq);
					out[0] = mq_exec[1];
				}
				else if(mq.match(/st-(.*)/g))
				{
					var mq_exec = /lt-(.+)/g.exec(mq);
					out[1] = mq_exec[1];
				}
			}
			return out;
		},
		extractCssAttributes: function(css_code)
		{
			return css_code.replace(/\s/g,'').replace(/^.*{([^}]+)}.*/,'$1').match(/([^;]+)/g);
		},
		setDefaultValues: function(css_code, mq)
		{
			mq = mq || '';
			var matches = DomManipulation.extractCssAttributes(css_code);
			$(matches).each(function(i,elem)
			{
				var splitted = this.split(':');
				if($('#probuilder-editor .probuilder-vertical-tabs-container-right input[name="'+splitted[0]+'"]').length && splitted[1] != 'undefined')
				{
					$('#probuilder-editor .probuilder-vertical-tabs-container-right input[name="'+splitted[0]+'"]').val(splitted[1]);
				}
				else if($('#probuilder-editor .probuilder-vertical-tabs-container-right select[name="'+splitted[0]+'"]').length && splitted[1] != 'undefined')
				{
					$('#probuilder-editor .probuilder-vertical-tabs-container-right select[name="'+splitted[0]+'"]').val(splitted[1]);
				}
				else if($('#probuilder-editor .probuilder-vertical-tabs-container-right textarea[name="'+splitted[0]+'"]').length && splitted[1] != 'undefined')
				{
					$('#probuilder-editor .probuilder-vertical-tabs-container-right textarea[name="'+splitted[0]+'"]').val(splitted[1]);
				}
			});
			if(mq)
			{
				$('#probuilder-editor .editor-container select[name="media-query"]').val(mq);
			}
		}
	};
	return {
		init: function(elem)
		{
			startSelection();
			initDraggable(elem);
			initTabs();
			initCodemirror();
			selectElement();
			handleInput();
			initAllProbuilds();
		},
		end: function()
		{
			endSelection();
			$('#probuilder-toggle').removeClass('toggle-active');
			$('#probuilder-toggle-dom-path').remove();
			$('#probuilder-editor').removeClass('visible');
		},
		convertMQ: function(elem)
		{
			return DomManipulation.convertMQ(elem);
		},
		extractNumbers: function(elem)
		{
			return DomManipulation.extractNumbers(elem);
		},		
	}
}(jQuery);


(function($)
{
	var codeMirror;
	$('body').on('click','#probuilder-toggle',function(e)
	{
		if(!$(this).hasClass('toggle-active'))
		{
			$('#probuilder-toggle').addClass('toggle-active');
			$('#show-all-probuilds').addClass('visible');
			Probuilder.init($('#probuilder-editor-handle'));
			$('body').on('click','#probuilder-close-editor',function(e)
			{
				$('#probuilder-editor').removeClass('visible');
				window.setTimeout(function()
				{
					var code_replacement = '';
					var css_elem = $.parseJSON(window.sessionStorage.getItem('css_elem'));
					$(css_elem).each(function(i,elem)
					{
						if(this.mq)
						{
							mq = Probuilder.convertMQ(this.mq);
							this.css_code = mq + '{' + this.css_code + '}';
						}
						code_replacement+= this.css_code;				
					});
					$('style#probuilder-style-tag').html(code_replacement);
				},500);			
				e.stopImmediatePropagation();
			});			
		}
		else
		{
			$('#show-all-probuilds').removeClass('visible');
			Probuilder.end();
			window.location.reload();
		}
	});	
})(jQuery);