var modal = {};

modal.actual = null;
modal.opening = false;

modal.open = function(id,data){
	modal.opening = true;
	$('#'+id).addClass('modal-active');
	modal.actual = id;
};

modal.close = function(id){
	$('#'+id).removeClass('modal-active');
	modal.actual = null;
};
modal.closeActual = function(){
	if(modal.actual != null){
		modal.close(modal.actual);
	}
};

$('body').on('click','[data-modal]',function(){
	modal.open(
		$(this).data('modal'),
		$(this).dataByPrefix('modal')
	);
});

$('body').on('click','.modal-close',function(){
	var id = $(this).closest('.modal').attr('id');
	modal.close(id);
});

$(document).click(function(event){

	if(!modal.opening){
		if(!$(event.target).closest('.modal-active').length || $(event.target).is('.modal')){
			modal.closeActual();
		}
	}

	modal.opening = false;
	
});

$.fn.dataByPrefix = function( pr ){
	var d=this.data(), r=new RegExp("^"+pr), ob={};
	for(var k in d) if(r.test(k)) ob[k]=d[k];
	return ob;
};