(function($)
{
	var AdminAdapts = 
	{
		addPreproToHelp: function()
		{
			var prepro = $('select[name="css-preprocessor"]').val();
			$('.predefined-vars-type').text(prepro+' ');
		},
		toggleInfo: function(elem)
		{
			elem.toggle();
		}
	};
	if($('.probuilder_page_probuilder-settings .probuilder-settings-container').length)
	{
		var highlight_color = probuilder_admin_custom.highlight_color;

		AdminAdapts.addPreproToHelp();
		$('select[name="css-preprocessor"]').on('change', function()
		{
			AdminAdapts.addPreproToHelp();
		});
		$('#media-query-explain-link').on('click', function()
		{
			AdminAdapts.toggleInfo($('#media-query-explain'));
		});
		$('#colorpicker').minicolors({
			opacity: true,
			format: 'rgb',
			defaultValue: highlight_color
		});
	}	
})(jQuery);