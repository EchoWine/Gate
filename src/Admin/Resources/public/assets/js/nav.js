/**
 * Navigation
 */
var nav = {};

nav.set = function(url){
	var link = $("[href='"+url+"']").closest('.nav-link-container');
	link.addClass('active');
};

$('.nav-link-container').on('click',function(){
	if($(this).hasClass('active')){
		$(this).removeClass('active');
	}else{
		$('.nav-link-container.active').removeClass('active');
		$(this).addClass('active');
	}
});