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
	jQuery( ".toggle-collapse-arrow-button" ).on("click", function() {
		if(jQuery("html").hasClass('left-drawer-collapsed')){
			jQuery("html").removeClass("left-drawer-collapsed");
		}else{
			jQuery("html").addClass("left-drawer-collapsed");
		}
	});
});