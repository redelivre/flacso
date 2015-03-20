/*
	Dropdown with Multiple checkbox select with jQuery - May 27, 2013
	(c) 2013 @ElmahdiMahmoud
	license: http://www.opensource.org/licenses/mit-license.php
 */

function getSelectedValue(id) {
	return jQuery("#" + id).find("dt a span.value").html();
}

function dropdownCheckboxMark(dropdowncheckbox)
{
	jQuery('.dropdown-checkbox').each(function()
	{
		if(jQuery(this).find('input:checked').length > 0)
		{
			jQuery(this).find('.clickable').addClass( 'toggled' );
		}
		else
		{
			jQuery(this).find('.clickable').removeClass( 'toggled' );
		}
	});
}

jQuery(document).ready(function() {
	jQuery(".dropdown-checkbox dt .clickable").on('click', function() {
		jQuery(this).parent().parent().find('dd ul').slideToggle('fast');
		dropdownCheckboxMark();
	});
	
	jQuery(".dropdown-checkbox dd ul li a").on('click', function() {
		jQuery(".dropdown-checkbox dd ul").hide();
		dropdownCheckboxMark();
	});
	
	jQuery(document).bind('click', function(e) {
		var jQueryclicked = jQuery(e.target);
		if (!jQueryclicked.parents().hasClass("dropdown-checkbox"))
		{
			jQuery(".dropdown-checkbox dd ul").hide();
			dropdownCheckboxMark();
		}
	});
	
	jQuery('.mutliSelect input[type="checkbox"]').on(
			'click',
			function() {
				dropdownCheckboxMark();
				/*
				var title = jQuery(this).parent().text().trim() + ",";
	
				if (jQuery(this).is(':checked')) {
					var html = '<span title="' + title + '">' + title + '</span>';
					jQuery(this).parent().parent().parent().parent().parent().parent().find('.multiSel').append(html);
					jQuery(this).parent().parent().parent().parent().parent().parent().find(".hida").hide();
				} else {
					jQuery(this).parent().parent().parent().parent().parent().parent().find('span[title="' + title + '"]').remove();
					if(jQuery(this).parent().parent().parent().parent().parent().parent().find('.multiSel').children().length <= 0)
					{
						jQuery(this).parent().parent().parent().parent().parent().parent().find(".hida").show();
					}
				}*/
			}
	);
});