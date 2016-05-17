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
		contentType: "application/x-www-form-urlencoded; charsetBySource=UTF-8",
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
	item.tables[table.name] = table;
};


/**
 * Initalization
 */
item.ini = function(){
	for(i in item.tables){
		table = item.tables[i];

		item.setBySourceEventAdd(table);
		item.getList(table);

	};
};

/**
 * Add event submit to add 
 *
 * @param {object} table
 */
item.setBySourceEventAdd = function(table){

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

	template.setBySource(type,destination,{
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

		var rows = '';

		$.map(data,function(row){
			row.table = table.name;
			rows += template.get(table.template.row,row);
		});


		template.setByHtml(rows,table.template.row);


	});
};

/**
 * Remove an item
 */


$('body').on('click','[data-item-remove]',function(){
	var id = $(this).data('item-id');

	var table = item.tables[$(this).data('item-table')];

	item.delete(table.delete.url+"/"+id,{},function(data){

		if(data.status == 'success'){
			item.getList(table);
			modal.closeActual();
		}

		if(data.status == 'error'){
			item.addAlert('alert-danger','alert-global',data);
		}
	});
});

$(document).ready(function(){
	item.ini();
});