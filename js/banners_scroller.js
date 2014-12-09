jQuery(document).ready(function(){
	jQuery(".banners-widget-area").cycle({
	    speed: 600,
	    manualSpeed: 100,
	    slides: ".banners-cycle",
	    fx: "carousel",
	    timeout: 0,
	    
	    carouselFluid: true,
	    next:"#banners-cycle-next",
	    prev:"#banners-cycle-prev"
	});
});