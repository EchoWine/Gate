/**
 * Item
 */
var item = {};

/**
 * List of all tables
 */
item.tables = [];


/**
 * Initalization
 */
item.ini = function(){
	for(i in item.tables){
		table = item.tables[i];
		
		item.updateSortHTML(table,table.list.sortByField,table.list.sortByDirection);
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
 *
 * @return {object}
 */
item.getTable = function(name){
	return item.tables[name];
};

/**
 * Update the list of all records
 *
 * @param {object} table
 * @param {array} params
 */
item.getList = function(table,params = {}){
		
	//template.setBySource('spinner-table','item-row',{});

	api.all(table.url,params,function(response){

		item.handleList(table,response);

	});
};


/**
 * Get
 * 
 * @param {object} table
 * @param {int} id
 */
item.get = function(table,id){

	api.get(table.url,id,function(response){

		item.handleGet(table,response);
	});
}

/**
 * Get for edit
 * 
 * @param {object} table
 * @param {int} id
 */
item.getForEdit = function(table,id){

	api.get(table.url,id,function(response){

		item.handleGetForEdit(table,response);
	});
}

	
/** 
 * Add a row
 *
 * @param {object} table
 * @param {array} values
 */
item.add = function(table,values){

	api.add(table.url,values,function(data){

		item.handleBasic(table,data,'alert-modal-add');
	});
}

/** 
 * Edit a row
 *
 * @param {object} table
 * @param {int} id
 * @param {array} values
 */
item.edit = function(table,id,values){
	api.edit(table.url,id,values,function(data){

		item.handleBasic(table,data,'alert-modal-edit');
	});
}

/**
 * Remove a row
 *
 * @param {object} table
 * @param {int} id
 */
item.remove = function(table,id){

	api.delete(table.url,id,function(data){

		item.handleBasic(table,data);
	});
};

/**
 * Copy a row
 *
 * @param {object} table
 * @param {int} id
 */
item.copy = function(table,id){

	api.copy(table.url,id,function(data){

		item.handleBasic(table,data);
	});
};

/**
 * Update the list of all records
 *
 * @param {object} table
 */
item.getListWithParams = function(table){

	var container = item.getContainerByTable(table);

	// Show and pages
	var params = {}

	// Show
	params.show = table.list.show;

	// Page
	params.page = table.list.page

	// Sorting
	table.list.sortByDirection == 'asc' ? params.asc = table.list.sortByField : params.desc = table.list.sortByField;

	// Search
	params.search = table.search.data;

	// Send request
	item.getList(table,params);
};



/**
 * Handle get response
 *
 * @param {object} table
 * @param {array} response
 */
item.handleGet = function(table,response){
	var container = item.getContainerByTable(table);
	table.get.get(container,response.data.resource);
};

item.handleGetForEdit = function(table,response){
	var container = item.getContainerByTable(table);
	table.edit.get(container,response.data.resource);
}

/**
 * Handle list response
 *
 * @param {object} table
 * @param {array} response
 */
item.handleList = function(table,response){

	var container = item.getContainerByTable(table);

	if(response.status == 'success'){

		var rows = '';

		// Get data response
		var data = response.data;

		// Get results data
		var results = data.results;

		// Build the rows
		$.map(results,function(row){
			row.table = table.name;
			rows += template.get(table.template.row,row);
		});

		template.setByHtml(rows,table.template.row);

		table.list.page = data.pagination.page;
		table.list.pages = data.pagination.pages;
		table.list.count = data.pagination.count;
		table.list.from = data.pagination.from;
		table.list.to = data.pagination.to;
		table.list.show = data.pagination.show;
		item.updateListHTML(table);

	}

	if(response.status == 'error'){
		item.addAlert('alert-danger','alert-global',response);
	}

}
/**
 * Handle basic response
 *
 * @param {object} table
 * @param {array} response
 * @param {string} container_modal
 */
item.handleBasic = function(table,response,container_modal){

	if(response.status == 'success' || !container){
		item.getListWithParams(table);
		modal.closeActual();
		item.addAlert('alert-success','alert-global',response);
	}

	if(response.status == 'error'){
		item.addAlert('alert-danger',container_modal,response);
	}
};

/**
 * Add Alert
 *
 * @param {string} type
 * @param {string} destination
 * @param {array} data
 */
item.addAlert = function(type,destination,data){

	det = '';

	for(index in data.details){
		detail = data.details[index];
		det += template.get('alert-details',{
			message:index+": "+detail
		});
	}

	template.setBySource(type,destination,{
		message:data.message,
		details:det
	});
};

/**
 * Update HTML list
 *
 * @param {object} table
 */
item.updateListHTML = function(table){

	var container = item.getContainerByTable(table);

	container.find('[data-item-list-page]').html(table.list.page);
	container.find('[data-item-list-pages]').html(table.list.pages);
	container.find('[data-item-list-count]').html(table.list.count);
	container.find('[data-item-list-start]').html(table.list.from);
	container.find('[data-item-list-end]').html(table.list.to);
	container.find('[data-item-show]').val(table.list.show);

	if(table.list.page == 1)
		container.find('[data-item-list-prev]').addClass('disable');
	else
		container.find('[data-item-list-prev]').removeClass('disable');
	

	if(table.list.page == table.list.pages)
		container.find('[data-item-list-next]').addClass('disable');
	else
		container.find('[data-item-list-next]').removeClass('disable');
	
};

/**
 * Go to prev page
 *
 * @param {object} table
 */
item.listPrev = function(table){
	if(table.list.page > 1){
		table.list.page--;
		item.getListWithParams(table);
	}

};

/**
 * Go to next page
 *
 * @param {object} table
 */
item.listNext = function(table){
	if(table.list.page < table.list.pages){
		table.list.page++;
		item.getListWithParams(table);
	}
};

/**
 * Update icon sort
 *
 * @param {object} table
 * @param {string} field
 * @param {string} direction
 */
item.updateSortHTML = function(table,field,direction){
	var container = item.getContainerByTable(table);

	container.find('[data-item-sort-none]').removeClass('hide');
	container.find('[data-item-sort-asc]').addClass('hide');
	container.find('[data-item-sort-desc]').addClass('hide');

	var sort = container.find('[data-item-sort-field='+field+']');
	var sort_direction = direction == 'asc' ? '[data-item-sort-asc]' : '[data-item-sort-desc]';

	sort.find('[data-item-sort-none]').addClass('hide');
	sort.find(sort_direction).removeClass('hide');
};

/**
 * Get opposite direction
 *
 * @param {string} sort
 *
 * @return {string}
 */
item.getOppositeSort = function(sort){
	return sort == 'asc' ? 'desc' : 'asc';
};

/**
 * Get table object by dom element
 *
 * @param {DOM} el
 *
 * @return {object}
 */
item.getTableByElement = function(el){
	return item.getTable(el.attr('data-item-table'));
};

/**
 * Get value of id by dom element
 *
 * @param {DOM} el
 *
 * @return {int}
 */
item.getIdByElement = function(el){
	return el.attr('data-item-id');
};

/**
 * Get DOM element container by table object
 *
 * @param {object} table
 *
 * @return {DOM}
 */
item.getContainerByTable = function(table){
	return $('[data-item-table-container='+table.name+']');
};


/**
 * Get IDs of records selected
 *
 * @param {object} table
 *
 * @return {array}
 */
item.getSelectedRecords = function(table){
	var container = item.getContainerByTable(table);
	var checkbox = container.find('[data-item-select]:checked');

	var ids = [];
	$.map(checkbox,function(value){
		ids.push($(value).attr('data-item-id'));
	});

	return ids;
}


/** 
 * Show on change
 */
$('[data-item-show]').on('change',function(){
	var table = item.getTableByElement($(this));
	table.list.show = $(this).val();
	item.getListWithParams(table);
});

/** 
 * Search on click
 */
$('[data-item-search]').on('click',function(){
	var table = item.getTableByElement($(this));

	var container = item.getContainerByTable(table);	
	var values = table.search.action(container.find('.table-row-search').first(9));

	table.search.data = values;

	item.getListWithParams(table);
});

/**
 * Sort on click
 */
$('body').on('click','[data-item-sort-field]',function(){

	var table = item.getTableByElement($(this));
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

	item.updateSortHTML(table,field,direction);

});

/**
 * Prev page on click
 */
$('body').on('click','[data-item-list-prev]',function(){
	var table = item.getTableByElement($(this));
	item.listPrev(table);
});

/**
 * next page on click
 */
$('body').on('click','[data-item-list-next]',function(){

	var table = item.getTableByElement($(this));
	item.listNext(table);
});

/**
 * Add on submit
 */
$('body').on('submit','[item-data-form-add]',function(e){

	e.preventDefault();

	var table = item.getTableByElement($(this));
	var values = table.add.action($(this));

	item.add(table,values);

});

/**
 * Remove on click
 */
$('body').on('click','[data-item-remove]',function(){

	var table = item.getTableByElement($(this));
	var id = item.getIdByElement($(this));

	item.remove(table,id);
});

/**
 * Copy on click
 */
$('body').on('click','[data-item-copy]',function(){

	var table = item.getTableByElement($(this));
	var id = item.getIdByElement($(this));

	item.copy(table,id);
});

/**
 * Edit on submit
 */
$('body').on('submit','[item-data-form-edit]',function(e){

	e.preventDefault();

	var table = item.getTableByElement($(this));
	var id = item.getIdByElement($(this));
	var values = table.edit.action($(this));

	item.edit(table,id,values);

});

/**
 * Select all on click
 */
$('body').on('click','[data-item-select-all]',function(){
	var table = item.getTable($(this).attr('data-item-table'));

	var container = item.getContainerByTable(table);
	
	container.find('[data-item-select]').prop('checked', $(this).prop('checked'));

});

/**
 * Delete multiple on click
 */
$('body').on('click','[data-item-multiple-delete]',function(){

	var table = item.getTable($(this).attr('data-item-table'));
	var container = item.getContainerByTable(table);
	var ids = item.getSelectedRecords(table);
	$.map(ids,function(id){
		item.remove(table,id);
	});
});

modal.on('modal-item-edit',function(container,data){
	var el = container.find('[item-data-form-edit]');
	var id = data['data-modal-item-id'];

	el.attr('data-item-table',data['data-modal-item-table']);
	el.attr('data-item-id',id);

	item.getForEdit(table,id);
});


modal.on('modal-item-get',function(container,data){
	var el = container.find('[item-data-form-get]');
	var id = data['data-modal-item-id'];

	el.attr('data-item-table',data['data-modal-item-table']);
	el.attr('data-item-id',id);

	item.get(table,id);
});


/**
 * Data in modal delete
 */
modal.on('modal-item-delete',function(el,data){
	var del = el.find('[data-item-remove]');
	del.attr('data-item-table',data['data-modal-item-table']);
	del.attr('data-item-id',data['data-modal-item-id']);
});

/**
 * Data in modal add
 */
modal.on('modal-item-add',function(el,data){
	var el = el.find('[item-data-form-add]');
	el.attr('data-item-table',data['data-modal-item-table']);
});


/**
 * Initialize
 */
$(document).ready(function(){
	item.ini();
});
