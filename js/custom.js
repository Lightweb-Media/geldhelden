// Onclick Mobile Menu
var mobileOffen = false;

function OpenMobileMenu() {
    mobileOffen = !mobileOffen
  
    if (mobileOffen) {
    	document.getElementById('sidebar-left').classList.add('show-sidebar-left');
    	document.getElementById('mobile-menu-btn').style.display = 'block';
    	document.getElementById('mobile-back-overlayer').style.display = 'block';
    } else {
        document.getElementById('sidebar-left').classList.remove('show-sidebar-left');
    	document.getElementById('mobile-menu-btn').style.display = 'none';
    	document.getElementById('mobile-back-overlayer').style.display = 'none';
    }
} 

jQuery(document).ready(function() {

	// Toggle Arrow Top Bar
	jQuery( ".toggle-collapse-arrow-button" ).on("click", function() {
		if(jQuery("html").hasClass('left-drawer-collapsed')){
			jQuery("html").removeClass("left-drawer-collapsed");
		}else{
			jQuery("html").addClass("left-drawer-collapsed");
		}
	});
	
	// Add "Toggle"-buttons to parent menu items
	jQuery('ul > li.menu-item-has-children > .menu-link-wrapper a').after('<span class="toggle-submenu closed-submenu"></span>');
	
	// Toogle Submenu
    jQuery('nav ul.sub-menu').hide();
	
	// Open all submenus containig active li
	jQuery('nav ul li.current-menu-item').parents('ul').show();

    jQuery('.toggle-submenu').click(function () {

        var checkElement = jQuery(this).parent().next('ul');

        if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
			jQuery(this).removeClass('opened-submenu').addClass('closed-submenu');
            jQuery(this).closest('li').removeClass('active');
            checkElement.slideUp('normal');
        }

        if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
			jQuery(this).removeClass('closed-submenu').addClass('opened-submenu');
            checkElement.slideDown('normal');
        }

        if (checkElement.is('ul')) {
            return false;
        } else {
            return true;
        } 
    });


});

jQuery(document).on('click', '.show-all-tags', function () {
    var ul = jQuery(this).parent().parent();
    ul.find('li.li-hidden').removeClass('li-hidden');
    jQuery(this).remove();
});