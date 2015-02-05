/**
 * 
 */

jQuery(function()
{
	jQuery("#importcsv").click(function(event)
	{
		event.preventDefault();
		var data =
        {
			action: 'ImportarCsv',
        };
         
        jQuery.ajax(
        {
            type: 'POST',
            url: flacso_options_scripts_object.ajax_url,
            data: data,
            success: function(response)
            {
            	jQuery('#result').replaceWith(response);
            },
        });
	});
	jQuery("#importcsvgea").click(function(event)
	{
		event.preventDefault();
		var data =
        {
			action: 'ImportarCsvGea',
        };
         
        jQuery.ajax(
        {
            type: 'POST',
            url: flacso_options_scripts_object.ajax_url,
            data: data,
            success: function(response)
            {
            	jQuery('#result').replaceWith(response);
            },
        });
	});
	jQuery("#importcsvgeadocs").click(function(event)
	{
		event.preventDefault();
		var data =
        {
			action: 'ImportarCsvGeaDocs',
        };
         
        jQuery.ajax(
        {
            type: 'POST',
            url: flacso_options_scripts_object.ajax_url,
            data: data,
            success: function(response)
            {
            	jQuery('#result').replaceWith(response);
            },
        });
	});
});