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
	http.get(table,filter,callback);
}

/**
 * Send a request get
 *
 * @param {string} table
 * @param {int} id
 * @param {closure} callback
 */
api.get = function(table,id,callback){
	http.get(table+"/"+id,{filter:'get'},callback);
};

/**
 * Send a request post
 *
 * @param {string} table
 * @param {array} values
 * @param {closure} callback
 */
api.add = function(table,values,callback){
	http.post(table,values,callback);
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
	http.put(table+"/"+id,values,callback);
};

/**
 * Send a request delete
 *
 * @param {string} table
 * @param {int} id
 * @param {closure} callback
 */
api.delete = function(table,id,callback){
	http.delete(table+"/"+id,{},callback);
};

/**
 * Send a request post
 *
 * @param {string} table
 * @param {int} id
 * @param {closure} callback
 */
api.copy = function(table,id,callback){
	http.post(table+"/"+id,{},callback);
};