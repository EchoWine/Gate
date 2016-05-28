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
item.ajax = function(type,url,params = {},callback){
	console.log('Call to: '+url+'');
	console.log(params);

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
			console.log(jqXHR);
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
 * Initalization
 */
item.ini = function(){
	for(i in item.tables){
		table = item.tables[i];
		
		item.updateIconSort(table,table.list.sortByField,table.list.sortByDirection);
		item.getListWithParams(table);

	};
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
 * Get a table
 *
 * @param {string} name
 * @return{object}
 */
item.getTable = function(name){
	return item.tables[name];
};

/**
 * Update the list of all records
 *
 * @param {object} table
 */
item.getListWithParams = function(table){

	var container = $('[data-item-table-container='+table.name+']');

	// Show and pages
	var params = {}

	// Show
	params.show = table.list.show;

	// Page
	params.page = table.list.page

	// Sorting
	table.list.sortByDirection == 'asc' ? params.asc = table.list.sortByField : params.desc = table.list.sortByField;

	// Search
	// ...

	// Send request
	item.getList(table,params);
};

/** 
 * Update show result
 */
$('[data-item-show]').on('change',function(){
	var table = item.getTable($(this).attr('data-item-table'));
	table.list.show = $(this).val();
	item.getListWithParams(table);
});

/**
 * Update the list of all records
 *
 * @param {object} table
 * @param {array} params
 */
item.getList = function(table,params = {}){
		
	template.setBySource('spinner-table','item-row',{});


	item.get(table.list.url,params,function(data){

		if(data.status == 'success'){
			var rows = '';

			data = data.data;
			$.map(data,function(row){
				row.table = table.name;
				rows += template.get(table.template.row,row);
			});


			template.setByHtml(rows,table.template.row);
		}

		if(data.status == 'error'){
			item.addAlert('alert-danger','alert-global',data);
		}



	});
};

/** 
 * Add a row
 */
item.add = function(table,values){

	item.post(table.add.url,values,function(data){

		if(data.status == 'success'){
			item.getListWithParams(table);
			modal.closeActual();
			item.addAlert('alert-success','alert-global',data);
		}

		if(data.status == 'error'){
			item.addAlert('alert-danger','alert-modal',data);
		}
	});
}

/** 
 * Edit a row
 */
item.edit = function(table,id,values){

	item.put(table.edit.url+"/"+id,values,function(data){

		if(data.status == 'success'){
			item.getListWithParams(table);
			modal.closeActual();
			item.addAlert('alert-success','alert-global',data);
		}

		if(data.status == 'error'){
			item.addAlert('alert-danger','alert-modal',data);
		}
	});
}

/**
 * Remove a row
 *
 * @param {object} table
 * @param {string} id
 */
item.remove = function(table,id){

	item.delete(table.delete.url+"/"+id,{},function(data){

		if(data.status == 'success'){
			item.getListWithParams(table);
			modal.closeActual();
			item.addAlert('alert-success','alert-global',data);
		}

		if(data.status == 'error'){
			item.addAlert('alert-danger','alert-global',data);
		}
	});
};

/**
 * Set event sort
 */
$('body').on('click','[data-item-sort-field]',function(){

	var table = item.getTable($(this).attr('data-item-table'));
	var field = $(this).attr('data-item-sort-field');

	var direction = 'asc';

	if(table.list.sortByField == field){
		direction = table.list.sortByDirection; 
	}else{

	}

	direction = item.getOppositeSort(direction);

	table.list.sortByDirection = direction;
	table.list.sortByField = field;

	item.getListWithParams(table);

	item.updateIconSort(table,field,direction);

});

/**
 * Update icon sort
 */
item.updateIconSort = function(table,field,direction){
	var container = $('[data-item-table-container='+table.name+']');
	container.find('[data-item-sort-none]').removeClass('hide');
	container.find('[data-item-sort-asc]').addClass('hide');
	container.find('[data-item-sort-desc]').addClass('hide');

	var sort = container.find('[data-item-sort-field='+field+']');
	var sort_direction = direction == 'asc' ? '[data-item-sort-asc]' : '[data-item-sort-desc]';

	sort.find('[data-item-sort-none]').addClass('hide');
	sort.find(sort_direction).removeClass('hide');
};

item.getOppositeSort = function(sort){
	return sort == 'asc' ? 'desc' : 'asc';
};

/**
 * Set event add
 */
$('[item-data-form-add]').on('submit',function(e){

	e.preventDefault();

	var table = item.getTable(table = $(this).attr('data-item-table'));
	var values = table.add.action($(this));

	item.add(table,values);

});

/**
 * Set event remove
 */
$('body').on('click','[data-item-remove]',function(){

	var table = item.getTable($(this).attr('data-item-table'));
	var id = $(this).attr('data-item-id');

	item.remove(table,id);
});

/**
 * Set event edit
 */
$('[item-data-form-edit]').on('submit',function(e){

	e.preventDefault();

	var table = item.getTable($(this).attr('data-item-table'));
	var id = $(this).attr('data-item-id');
	var values = table.edit.action($(this));

	item.edit(table,id,values);

});

modal.addDataTo('modal-item-edit',function(container,data){
	var el = container.find('[item-data-form-edit]');
	var id = data['data-modal-item-id'];

	el.attr('data-item-table',data['data-modal-item-table']);
	el.attr('data-item-id',id);

	item.get(table.get.url+"/"+id,{filter:'edit'},function(data){
		
		table.edit.get(container,data);

	});
});


modal.addDataTo('modal-item-get',function(container,data){
	var el = container.find('[item-data-form-get]');
	var id = data['data-modal-item-id'];

	el.attr('data-item-table',data['data-modal-item-table']);
	el.attr('data-item-id',id);

	item.get(table.get.url+"/"+id,{filter:'get'},function(data){
		table.get.get(container,data);

	});
});

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
 * Data in modal delete
 */
modal.addDataTo('modal-item-delete',function(el,data){
	var del = el.find('[data-item-remove]');
	del.attr('data-item-table',data['data-modal-item-table']);
	del.attr('data-item-id',data['data-modal-item-id']);
});

/**
 * Data in modal add
 */
modal.addDataTo('modal-item-add',function(el,data){
	var el = el.find('[item-data-form-add]');
	el.attr('data-item-table',data['data-modal-item-table']);
});

/**
 * Initialize
 */
$(document).ready(function(){
	item.ini();
});
