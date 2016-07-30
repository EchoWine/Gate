/**
 * Item
 */
var item = {};

item.url;


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
		
		item.updateSortHTML(table,table.list.sort_by_field,table.list.sort_by_direction);
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
 */
item.getListWithParams = function(table){

	var container = item.getContainerByTable(table);

	// Show and pages
	var params = {}

	// Search
	params.search = {};
	$.map(table.search.data,function(value,key){
		if(value != null)
			params.search[key] = value;
	});


	// Show
	params.show = table.list.show;

	// Page
	params.page = table.list.page

	// Sorting
	table.list.sort_by_direction == 'asc' ? params.asc = table.list.sort_by_field : params.desc = table.list.sort_by_field;


	// Send request
	item.request.getList(table,params);
};

/**
 * Convert given rows result into column results
 *
 * @param {array} results
 *
 * @return {array}
 */
item.rowsToColumns = function(results){
	var columns = [];

	$.map(results,function(row){
		$.map(row,function(value,col){

			if(typeof columns[col] == 'undefined'){
				columns[col] = [];
			}

			columns[col].push(value);
		});
	})

	return columns;
}

/**
 * Convert given row result into column result
 *
 * @param {array} row
 *
 * @return {array}
 */
item.rowToColumns = function(row){
	var columns = [];

	$.map(row,function(value,col){
		columns[col] = [value];
	});

	return columns;
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

	template.set(type,{
		message:data.message,
		details:det
	},destination);
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

	// Reset select all
	$("[data-item-select-all][data-item-table='"+table.name+"']").prop('checked', false);
	
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

	var sort = container.find("[data-item-sort-field='"+field+"']");
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
 * Return selected checkbox
 * 
 * @param {object} table
 * 
 * @return {array}
 */
item.getSelectedCheckbox = function(table){

	var container = item.getContainerByTable(table);
	return checkbox = container.find('[data-item-select]:checked');
}

/**
 * Return selected checkbox
 * 
 * @param {object} table
 * 
 * @return {array}
 */
item.getAllCheckbox = function(table){

	var container = item.getContainerByTable(table);
	return checkbox = container.find('[data-item-select]');
}


/**
 * Get IDs of records selected
 *
 * @param {object} table
 *
 * @return {array}
 */
item.getSelectedIds = function(table){
	checkbox = item.getSelectedCheckbox(table);

	var ids = [];

	$.map(checkbox,function(value){
		ids.push($(value).attr('data-item-id'));
	});

	return ids;
}

/**
 * Set animation for no selected rows
 *
 * @param {object} table
 */
item.animationNoSelectedRowMultiple = function(table){
	checkbox = item.getAllCheckbox(table);

	checkbox.addClass('required');

	setTimeout(function(){
		checkbox.removeClass('required');
	},400);

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
	var values = table.search.action(container.find('.table-row-search').first());

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

	if(table.list.sort_by_field == field){
		direction = table.list.sort_by_direction; 
	}else{

	}

	direction = item.getOppositeSort(direction);

	table.list.sort_by_direction = direction;
	table.list.sort_by_field = field;

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

	item.request.add(table,values);

});

/**
 * Remove on click
 */
$('body').on('click','[data-item-remove]',function(){

	var table = item.getTableByElement($(this));
	var id = item.getIdByElement($(this));

	item.request.remove(table,id);
});

/**
 * Copy on click
 */
$('body').on('click','[data-item-copy]',function(){

	var table = item.getTableByElement($(this));
	var id = item.getIdByElement($(this));
	item.request.copy(table,id);
});

/**
 * Edit on submit
 */
$('body').on('submit','[item-data-form-edit]',function(e){

	e.preventDefault();

	var table = item.getTableByElement($(this));
	var id = item.getIdByElement($(this));
	var values = table.edit.action($(this));

	item.request.edit(table,id,values);

});

/**
 * Select all on click
 */
$('body').on('click','[data-item-select-all]',function(){
	var table = item.getTableByElement($(this));

	var container = item.getContainerByTable(table);
	
	container.find('[data-item-select]').prop('checked', $(this).prop('checked'));

});

/**
 * Delete multiple on click
 */
$('body').on('click','[data-item-multiple-delete]',function(){

	var table = item.getTableByElement($(this));
	var container = item.getContainerByTable(table);
	var ids = item.getSelectedIds(table);
	ids = ids.join(";");
	item.request.remove(table,ids);
});

$('body').on('change','[data-item-multiple]',function(){
	var table = item.getTableByElement($(this));
	var ids = item.getSelectedIds(table);
	if(ids.length == 0){
		item.animationNoSelectedRowMultiple(table);
	}else if($(this).val() == 'delete'){
		modal.open(
			'modal-item-delete-multiple',
			{'data-modal-item-table':table.name}
		);
	}else if($(this).val() == 'copy'){
		console.log('a');
		item.request.copy(table,ids.join(";"));
	}

	$(this).val('none');
});


/**
 * Initialize
 */
$(document).ready(function(){
	item.ini();
});
