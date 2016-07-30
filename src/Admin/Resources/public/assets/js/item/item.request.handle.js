item.request.handle = {};

/**
 * Handle get response
 *
 * @param {object} table
 * @param {array} response
 */
item.request.handle.get = function(table,response){
	var container = item.getContainerByTable(table);

	var data = response.data;

	var columns = item.rowToColumns(data.resource);

	item.request.handle.relations(container,table,columns,function(){


		table.get.get(container,table,data.resource);

	});


};

item.request.handle.getForEdit = function(table,response){
	var container = item.getContainerByTable(table);
	table.edit.get(container,response.data.resource);
}

/**
 * Handle list response
 *
 * @param {object} table
 * @param {array} response
 */
item.request.handle.list = function(table,response){

	var container = item.getContainerByTable(table);

	if(response.status == 'success'){
		var data = response.data;
		table.list.page = data.pagination.page;
		table.list.pages = data.pagination.pages;
		table.list.count = data.pagination.count;
		table.list.from = data.pagination.from;
		table.list.to = data.pagination.to;
		table.list.show = data.pagination.show;

		var columns = item.rowsToColumns(data.results);

		item.request.handle.relations(container,table,columns,function(){

			// Report result only when all relations call will be made
			table.list.get(container,table,data.results,columns);

			item.updateListHTML(table);
		});



	}

	if(response.status == 'error'){
		item.addAlert('alert-danger','.alert-global',response);
	}

}


/**
 * Handle the relations in list
 *
 * @param {container} DOM
 * @param {object} table
 * @param {array} columns
 * @param {closure} end
 */
item.request.handle.relations = function(container,table,columns,end){

	table.list.relations.columns[table.name] = columns;
	//table.list.relations.ids[table.name] = columns['id'];

	// Handle relations

	// This vars are used to count each end of relations request
	// in order to know when the end must be called
	table.list.relations.count = 0;
	table.list.relations.total = 0;

	for(field in table.list.relations.schema){
		relations = table.list.relations.schema[field];

		// Set a pointer "next" that point to the next object
		prev = null;
		var keys = Object.keys(relations);
		for(i = keys.length - 2; i >= 0;i--){
			relation = relations[i];
			relation.next = relations[i+1];
		}

		// Make first call 
		table.list.relations.total++;
		item.request.handle.nextRelation(table,relations[0],end);
	}

	if(table.list.relations.total == 0){
		end();
	}


};

/**
 * Handle next relation with another call api
 *
 * @param {object} table
 * @param {object} relation
 * @param {closure} end
 */
item.request.handle.nextRelation = function(table,relation,end){

	var params = [];
	var columns_relation = [];

	// Initalize relations ids
	if(typeof table.list.relations.ids[relation.url] == 'undefined'){
		table.list.relations.ids[relation.url] = [];
	}

	// Initalize relations columns
	if(typeof table.list.relations.columns[table.name] == 'undefined'){
		table.list.relations.columns[table.name] = [];
	}

	// Initalize relations columns
	if(typeof table.list.relations.columns[table.name][relation.column] == 'undefined'){
		table.list.relations.columns[table.name][relation.column] = [];
	}

	// Remove null value and prevent double request for the same resource
	$.map(table.list.relations.columns[table.name][relation.column],function(val){
		if(val != null && table.list.relations.ids[relation.url].indexOf(val) == -1){
			columns_relation.push(val);
		}
	});

	// Merge ids of new request with ids of previous one
	table.list.relations.ids[relation.url] = $.merge(
		table.list.relations.ids[relation.url],
		columns_relation
	);
	params['search[id]'] = columns_relation.join(";");

	if(columns_relation.length == 0){

		item.request.handle.endRelation(table,relation,end);
		return;
	}

	// Make the request
	api.all(table.basic_url+relation.url,params,function(response){

		// Merge the result with previous one
		table.list.relations.values[relation.url] = $.extend(
			table.list.relations.values[relation.url],
			response.data.results
		);

		// Merge ids of new request with ids of previous one
		table.list.relations.columns[relation.url] = $.extend(
			table.list.relations.columns[relation.url],
			item.rowsToColumns(response.data.results)
		);

		item.request.handle.endRelation(table,relation,end);


	});

}

/**
 * Go to next query
 *
 * @param {object} table
 * @param {object} relation
 * @param {closure} end
 */
item.request.handle.endRelation = function(table,relation,end){
	if(relation.next)
		item.request.handle.nextRelation(table,relation.next,end);
	else{
		table.list.relations.count++;
	}

	if(table.list.relations.total == table.list.relations.count){
		end();
	}
};


/**
 * Handle basic response
 *
 * @param {object} table
 * @param {array} response
 * @param {string} container_modal
 */
item.request.handle.basic = function(table,response,container_modal){

	if(response.status == 'success' || !container){
		item.getListWithParams(table);
		modal.closeActual();
		item.addAlert('alert-success','.alert-global',response);
	}

	if(response.status == 'error'){
		item.addAlert('alert-danger',container_modal,response);
	}
};