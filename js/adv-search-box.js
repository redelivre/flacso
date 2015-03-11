function flacso_adv_search_click()
{
	var checkValues = jQuery("input[name*='adv-search-box-']:checked").map(function()
    {
        return {name: jQuery(this).attr("name").replace("adv-search-box-","").replace("[]", ""), value:jQuery(this).val()};
    }).get();
	
	var texts = jQuery("input[name*='adv-search-box-input-']").map(function()
    {
		return { name:jQuery(this).attr("name").replace("adv-search-box-input-",""), value:jQuery(this).val()};
    }).get();
	
	jQuery.ajax({
		type   : 'post',
		url    : 'http://' + window.location.host + '/wp-admin/admin-ajax.php',
		data   : {
			action: 'flacso_adv_search',
			checked: checkValues,
			fields: texts
		},
		success: function(response) {
			jQuery('.general-list').replaceWith(response);
		}
	});
}

jQuery(document).ready(function () {
	var w = jQuery('.clickable').width() + 10;
	jQuery('.mutliSelect ul').width(w);
	
	jQuery('.adv-search-box-button').click(function(){
		flacso_adv_search_click();
	});
});

function flacso_tax_click(name, id)
{
	var checkValues = [{name:name, value:id}];
	
	if(adv_search_box.gea != '')
	{
		checkValues[1] = {name:'gea', value:adv_search_box.gea} 
	}
	
	if(adv_search_box.library != 1)
	{
		window.location.href = adv_search_box.library_url + "&"+name+"="+id;
		return false;
	}
	
	var texts = [];
	
	jQuery.ajax({
		type   : 'post',
		url    : 'http://' + window.location.host + '/wp-admin/admin-ajax.php',
		data   : {
			action: 'flacso_adv_search',
			checked: checkValues,
			fields: texts
		},
		success: function(response) {
			jQuery('.general-list').replaceWith(response);
		}
	});
}