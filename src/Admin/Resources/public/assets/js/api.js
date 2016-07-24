/**
 * Api
 */
var api = {};

/**
 * Send a request get
 *
 * @param {string} table
 * @param {array} filter
 * @param {closure} callback
 */
api.all = function(table,filter,callback){
	return http.get(table,filter,callback);
}

/**
 * Send a request get
 *
 * @param {string} table
 * @param {int} id
 * @param {closure} callback
 */
api.get = function(table,id,callback){
	return http.get(table+"/"+id,{filter:'get'},callback);
};

/**
 * Send a request post
 *
 * @param {string} table
 * @param {array} values
 * @param {closure} callback
 */
api.add = function(table,values,callback){
	return http.post(table,values,callback);
};

/**
 * Send a request put
 *
 * @param {string} table
 * @param {int} id
 * @param {array} values
 * @param {closure} callback
 */
api.edit = function(table,id,values,callback){
	return http.put(table+"/"+id,values,callback);
};

/**
 * Send a request delete
 *
 * @param {string} table
 * @param {int} id
 * @param {closure} callback
 */
api.delete = function(table,id,callback){
	return http.delete(table+"/"+id,{},callback);
};

/**
 * Send a request post
 *
 * @param {string} table
 * @param {int} id
 * @param {closure} callback
 */
api.copy = function(table,id,callback){
	return http.post(table+"/"+id,{},callback);
};

/**
 * Call callback when all request are sent
 *
 * @param {array} calls
 * @param {closure} callback
 */
api.group = function(calls,callback){

	$.when.apply($,calls).then(callback,function(){

	});

};