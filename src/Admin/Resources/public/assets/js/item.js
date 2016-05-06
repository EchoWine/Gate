var item = {};

item.tablesURL = [];

item.get = function(url,params,callback){

	var call = $.get(url, function(data){
		callback(data);
	},'json')
	.fail(function(){
		console.log('Error during call');
	})
	.always(function(){
	});

};

item.addTableURL = function(url){
	item.tablesURL.push(url);
};

item.updateList = function(){
	$.map(item.tablesURL,function(table,i){
		item.get(table.url,[],function(data){
				
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
	});
};

$(document).ready(function(){
	item.updateList();
});