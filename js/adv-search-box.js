function flacso_adv_search_click()
{
	jQuery('.icon-spin6.animate-spin.icon--large').removeClass('hidden');
	jQuery("html, body").animate({ scrollTop: jQuery('#main').first().offset().top }, "slow");
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
			fields: texts,
			paged: jQuery('input[name="adv-search-paged"]').val()
		},
		success: function(response) {
			jQuery('.general-list').replaceWith(response);
			flacso_adv_search_result();
		}
	});
	
}

function flacso_adv_search_result()
{
	var checkValues = jQuery("input[name*='adv-search-box-']:checked").map(function()
    {
        return {
        	name:  jQuery(this).attr("name").replace("adv-search-box-","").replace("[]", ""),
        	value: jQuery(this).val(),
        	title: jQuery(this).parent().text().trim(),
        	tax_label: jQuery(this).parent().parent().parent().parent().parent().parent().find('.dropdown-checkbox-header-label').text()
        };
    }).get();
	
	var texts = jQuery("input[name*='adv-search-box-input-']").map(function()
	{
		if(jQuery(this).val().length > 0)
		{
			return { 
				name:jQuery(this).attr("name").replace("adv-search-box-input-",""),
				value:jQuery(this).val(),
				label: jQuery(this).parent().find('label').text()
			};
		}
	}).get();
	
	/*<header class="page-header">
						<h1 class="page-title">Biblioteca</h1>					</header>*/
	
	var html = '<header class="page-header"><h1 class="page-title">Busca por:</h1>';
	
	for (i = 0; i < checkValues.length; i++)
	{
		html+="<h4>" + checkValues[i].tax_label + ": "+ checkValues[i].title + "</h4>";
	}
	for (i = 0; i < texts.length; i++)
	{
		html+="<h4>" + texts[i].label + ": "+ texts[i].value + "</h4>";
	}
	html+="</header>";
	
	jQuery('main article header').first().replaceWith(html);
	jQuery('main article .entry-content').hide();
	jQuery('.icon-spin6.animate-spin.icon--large').addClass('hidden')
	
}

jQuery(document).ready(function () {
	var w = jQuery('.clickable').width();
	jQuery('.mutliSelect ul').width(w);
	
	jQuery('.adv-search-box-button').click(function(){
		flacso_adv_search_click();
	});
	jQuery('.adv-search-box-custom-field input').bind("enterKey",function(e){
		flacso_adv_search_click();
	});
	jQuery('.adv-search-box-custom-field input').keyup(function(e){
	    if(e.keyCode == 13)
	    {
	        jQuery(this).trigger("enterKey");
	    }
	});
});

function flacso_tax_click(name, id)
{
	jQuery('.icon-spin6.animate-spin.icon--large').removeClass('hidden');
	jQuery("html, body").animate({ scrollTop: jQuery('#main').first().offset().top }, "slow");
	
	var checkValues = [{name:name, value:id}];
	
	jQuery("input[name='adv-search-box-"+name+"[]'][value="+id+"]").attr("checked", "checked");
	dropdownCheckboxMark();
	
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
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
			jQuery('.general-list').replaceWith(response);
			flacso_adv_search_result();
		}
	});
}

function flacso_adv_searchget_pagenum(num)
{
	jQuery('input[name="adv-search-paged"]').val(num);
	flacso_adv_search_click();
}