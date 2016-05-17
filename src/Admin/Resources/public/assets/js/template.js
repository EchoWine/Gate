var template = {};


template.setByHtml = function(html,destination,callback){

	var destination = $('[data-use-template='+destination+']');

	destination.html('');
	destination.append(html);

}

template.setBySource = function(source,destination,vars,callback){

	var destination = $('[data-use-template='+destination+']');
	var source = $('[data-template='+source+']').first().clone();
	source.children().addClass('template-new');
	destination.html('');

	var html = source.html();

	for(col in vars){
		html = html.replace(new RegExp('{'+col+'}', 'g'),vars[col]);

	};

	destination.append(html);

	setTimeout(function(){
		$('.template-new').removeClass('template-new');
   	},50);

}


template.get = function(source,vars,callback){

	var source = $('[data-template='+source+']').first().clone();


	var html = source.html();

	for(col in vars){
		html = html.replace(new RegExp('{'+col+'}', 'g'),vars[col]);
	};

	return html;

}
