
item.request = {};

/**
 * Update the list of all records
 *
 * @param {object} table
 * @param {array} params
 */
item.request.getList = function(table,params = {}){
	
	api.all(table.url,params,function(response){
		
		item.request.handle.list(table,response);

	});
};


/**
 * Get
 * 
 * @param {object} table
 * @param {int} id
 */
item.request.get = function(table,id){

	api.get(table.url,id,function(response){

		item.request.handle.get(table,response);
	});
}

/**
 * Get for edit
 * 
 * @param {object} table
 * @param {int} id
 */
item.request.getForEdit = function(table,id){

	api.get(table.url,id,function(response){

		item.request.handle.getForEdit(table,response);
	});
}

	
/** 
 * Add a row
 *
 * @param {object} table
 * @param {array} values
 */
item.request.add = function(table,values){

	api.add(table.url,values,function(data){

		item.request.handle.basic(table,data,'alert-modal-add');
	});
}

/** 
 * Edit a row
 *
 * @param {object} table
 * @param {int} id
 * @param {array} values
 */
item.request.edit = function(table,id,values){
	api.edit(table.url,id,values,function(data){

		item.request.handle.basic(table,data,'alert-modal-edit');
	});
}

/**
 * Remove a row
 *
 * @param {object} table
 * @param {int} id
 */
item.request.remove = function(table,id){

	api.delete(table.url,id,function(data){

		item.request.handle.basic(table,data);
	});
};

/**
 * Copy a row
 *
 * @param {object} table
 * @param {int} id
 */
item.request.copy = function(table,id){

	api.copy(table.url,id,function(data){

		item.request.handle.basic(table,data);
	});
};


