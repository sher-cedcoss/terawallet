jQuery(document).ready( function($) {
	// Add selected class to parent when sub menu is selected.
	$('.uap-ap-menu-item-selected').parents('.uap-ap-submenu-item').addClass('uap-ap-menu-item-selected');
	var x = $("p").position();
	if ($(window).width() < 767) {
		$(document).on('click', '.uap-ap-submenu-item', function(){
			$(this).find('.uap-public-ap-menu-subtabs').slideToggle();
		});
	}
});