function flacso_adv_search_click()
{
	var checkValues = jQuery('input[name=taxonomy_category\\[\\]]:checked').map(function()
    {
        return jQuery(this).val();
    }).get();
	
	if(jQuery('.category-solution-category-archive-list-itens').length)
	{
		jQuery.ajax({
			type   : 'post',
			url    : 'http://' + window.location.host + '/wp-admin/admin-ajax.php',
			data   : {
				action: 'flacso_search_solutions',
				data: checkValues
			},
			success: function(response) {
				jQuery('.category-solution-category-archive-list-itens').replaceWith(response);
			}
		});
		jQuery(".solution-category-archive-category-header").hide();
	}
	else
	{
		checkValuesStr = '';
		
		for (index = 0; index < checkValues.length; index++)
		{
			checkValuesStr += 'cat=' + checkValues[index] + '&';
		}
		
		window.location.assign(' http://' + window.location.host + '/solution?' + checkValuesStr);
	    return false;
	}
}

jQuery(document).ready(function () {
	var w = jQuery('.clickable').width() + 10;
	jQuery('.mutliSelect ul').width(w);
	
	jQuery('#adv-search-box-button').click(function(){
		flacso_adv_search_click();
	});
});