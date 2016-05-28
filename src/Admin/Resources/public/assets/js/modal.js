var modal = {};

modal.actual = null;
modal.opening = false;
modal.filterData = {};
modal.html = {};

/**
 * Open the modal
 *
 * @param {string} id
 * @param {object} data
 */
modal.open = function(id,data){

	modal.closeActual();
	$('html,body').scrollTop(0);

	modal.opening = true;
	var el = $('#'+id);
	modal.html[id] = el.html();
	el.addClass('modal-active');


	for(info in data){
		el.find('.'+info).html(data[info]);
	}

	for(info in data){
		el.find('['+info+']').attr(info,data[info]);
	}

	modal.exeDataTo(id,el,data);

	modal.actual = id;
};

/**
 * Add a callback to call when the modal is opened
 * 
 * @param {string} id
 * @param {object} el
 */
modal.addDataTo = function(id,callback){
	modal.filterData[id] = callback;
};

/**
 * Execute the callback given the id of modal
 *
 * @param {string} id
 * @param {object} el
 * @param {object} data
 */
modal.exeDataTo = function(id,el,data){

	var closure = modal.filterData[id];
	if(!closure)return null;

	closure(el,data);

};

/**
 * Get data param in modal
 *
 * @param {object} element
 *
 * @return {object} data
 */
modal.getDataModal = function(el){
	var data = el.dataByPrefix('modal');
	delete data['data-modal'];
	return data;
};

/**
 * Close the modal given the id
 * 
 * @param
 */
modal.close = function(id){
	$('#'+id).removeClass('modal-active');

	setTimeout(function(){
		$('#'+id).html(modal.html[id]);
   	},300);

	modal.actual = null;
};

/**
 * Close actual modal
 */
modal.closeActual = function(){
	if(modal.actual != null){
		modal.close(modal.actual);
	}
};

/**
 * Open the modal when a specific element is clicked
 */
$('body').on('click','[data-modal]',function(){
	modal.open(
		$(this).data('modal'),
		modal.getDataModal($(this))
	);
});

/**
 * Close the modal when a specific element is clicked
 */
$('body').on('click','.modal-close',function(){
	var id = $(this).closest('.modal').attr('id');
	modal.close(id);
});

/**
 * Close the modal when outside is clicked
 */
$(document).click(function(event){

	if(!modal.opening){
		if(!$(event.target).closest('.modal-active').length || $(event.target).is('.modal')){
			modal.closeActual();
		}
	}

	modal.opening = false;
	
});

/**
 * Get attribute with prefix
 */
$.fn.dataByPrefix = function( pr ){
	var data = this.get(0).attributes;
	var r = new RegExp("^"+'data-'+pr);
	var ob = {};
	$.each(data,function(k,attr){
		value = attr.nodeValue;
		attr = attr.nodeName;
		if(r.test(attr)) ob[attr] = value;
	});
	return ob;
};