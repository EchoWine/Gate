/**
 * Item
 */
var item = {};

/**
 * List of all tables
 */
item.tables = [];

/**
 * Make ajax call
 *
 * @param {string} type
 * @param {string} url
 * @param {object} params
 * @param {function} callback
 */
item.ajax = function(type,url,params,callback){
	$.ajax({
		type: type,
		url: url, 
		data : params,
		contentType: "application/x-www-form-urlencoded; charset=UTF-8",
		success: function(data) {
			callback(data);
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log('Error during call: '+url);
			console.log(errorThrown);
		},
		dataType:'json'
	});
};

/**
 * Make get call
 *
 * @param {string} url
 * @param {object} params
 * @param {function} callback
 */
item.get = function(url,params,callback){
	item.ajax('GET',url,params,callback);
};

/**
 * Make post call
 *
 * @param {string} url
 * @param {object} params
 * @param {function} callback
 */
item.post = function(url,params,callback){
	item.ajax('POST',url,params,callback);
};

/**
 * Make put call
 *
 * @param {string} url
 * @param {object} params
 * @param {function} callback
 */
item.put = function(url,params,callback){
	item.ajax('PUT',url,params,callback);
};

/**
 * Make delete call
 *
 * @param {string} url
 * @param {object} params
 * @param {function} callback
 */
item.delete = function(url,params,callback){
	item.ajax('DELETE',url,params,callback);
};

/**
 * Add a table
 *
 * @param {object} table
 */
item.addTable = function(table){
	item.tables.push(table);
};


/**
 * Initalization
 */
item.ini = function(){
	$.map(item.tables,function(table,i){

		item.setEventAdd(table);

		item.getList(table);

	});
};

/**
 * Add event submit to add 
 *
 * @param {object} table
 */
item.setEventAdd = function(table){

	$(table.add.form).on('submit',function(e){

		e.preventDefault();


		var values = table.add.action($(this));

		item.post(table.add.url,values,function(data){

			if(data.status == 'success'){
				item.getList(table);
				modal.closeActual();
			}

			if(data.status == 'error'){
				item.addAlert('alert-danger','alert-modal',data);
			}
		});

	});
};

/**
 * Add Status
 *
 * @param {object} alert
 */
item.addAlert = function(type,destination,data){

	det = '';

	for(detail in data.details){
		detail = data.details[detail];
		det += template.get('alert-details',{
			message:detail.message
		});
	}

	template.set(type,destination,{
		message:data.message,
		details:det
	});
};

/**
 * Remove Status
 *
 * @param {object} alert
 */
item.removeAlert = function(alert){
	
};

/**
 * Update the list of all records
 *
 * @param {object} table
 */
item.getList = function(table){
	item.get(table.list.url,[],function(data){
		

		var container = $('[data-use-template='+table.template.row+']').first();

		// Get template row
		var tmpl = $('[data-template='+table.template.row+']').first().clone();

		container.html('');
		$.map(data,function(row){

			var html = tmpl.html();

			$.map(row,function(val,col){
				html = html.replace('{'+col+'}',val);
			});

			container.append(html);
		});

	});
};

/**
 * Remove an item
 */
item.remove = function(){
	$('data-item-remove').on('click',function(){
		id = $(this).data('item-remove');

		item.delete(table.delete.url,values,function(data){

			if(data.status == 'success'){
				item.getList(table);
				modal.closeActual();
			}

			if(data.status == 'error'){
				item.addAlert('alert-danger','alert-global',data);
			}
		});
	});
};

$(document).ready(function(){
	item.ini();
});