var template = {};


template.set = function(source,destination,vars,callback){

	var destination = $('[data-use-template='+destination+']');
	var source = $('[data-template='+source+']').first().clone();
	source.children().addClass('template-new');
	destination.html('');

	var html = source.html();

	$.map(vars,function(val,col){
		html = html.replace('{'+col+'}',val);
	});

	destination.append(html);

	setTimeout(function(){
		$('.template-new').removeClass('template-new');
   	},50);

}


template.get = function(source,vars,callback){

	var source = $('[data-template='+source+']').first().clone();


	var html = source.html();

	$.map(vars,function(val,col){
		html = html.replace('{'+col+'}',val);
	});

	return html;

}
