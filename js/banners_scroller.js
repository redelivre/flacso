jQuery(document).ready(function(){
	jQuery(".banners-widget-area").cycle({
	    allowWrap: true,
	    carouselFluid: true,
	    fx: "carousel",
	    manualSpeed: 400,
	    slides: ".banners-cycle",
	    speed: 600,
	    timeout: 0,
	    
	    next:"#banners-cycle-next",
	    prev:"#banners-cycle-prev"
	});
});