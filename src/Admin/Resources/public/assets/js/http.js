/**
 * Http Request
 */
var http = {};

/**
 * Make ajax call
 *
 * @param {string} type
 * @param {string} url
 * @param {object} params
 * @param {function} callback
 */
http.ajax = function(type,url,params = {},callback){
	
	console.log('Call to: '+url+'');
	console.log(params);

	$.ajax({
		type: type,
		url: url, 
		data : params,
		contentType: "application/x-www-form-urlencoded; charsetBySource=UTF-8",
		success: function(data) {

		console.log(data);
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
http.get = function(url,params,callback){
	http.ajax('GET',url,params,callback);
};

/**
 * Make post call
 *
 * @param {string} url
 * @param {object} params
 * @param {function} callback
 */
http.post = function(url,params,callback){
	http.ajax('POST',url,params,callback);
};

/**
 * Make put call
 *
 * @param {string} url
 * @param {object} params
 * @param {function} callback
 */
http.put = function(url,params,callback){
	http.ajax('PUT',url,params,callback);
};

/**
 * Make delete call
 *
 * @param {string} url
 * @param {object} params
 * @param {function} callback
 */
http.delete = function(url,params,callback){
	http.ajax('DELETE',url,params,callback);
};