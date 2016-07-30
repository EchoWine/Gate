var template = {};

template.source = {};

template.setByHtml = function(html,destination,callback){

	
	var destination = $('[data-use-template='+destination+']');
	destination.html('');
	destination.append(html);

	setTimeout(function(){
		$('.template-new').removeClass('template-new');
   	},50);

}

template.setBySource = function(source,destination,vars,callback){

	var source = template.getSourceByTemplate(source);
	var html = template.vars(source.html(),vars);

	template.setByHtml(html,destination,callback);

}


template.get = function(source,vars,callback){

	var source = template.getSourceByTemplate(source);


	var html = source.html();

	for(col in vars){
		html = html.replace(new RegExp('{'+col+'}', 'g'),vars[col]);
	};

	return html;

}

template.getSourceByTemplate = function(source){

	var source = $.parseHTML("<div>"+template.source[source]+"</div>");
	source = $(source);
	source.children().addClass('template-new');
	return source.clone();
};

template.vars = function(html,vars){
	for(col in vars){
		html = html.replace(new RegExp('{'+col+'}', 'g'),vars[col]);
	};
	return html;
};

$(document).ready(function(){
	$.map($('.templates'),function(tmpl){
		tmpl = $(tmpl);
		var name = tmpl.attr('data-template');
		template.source[name] = tmpl.html();
		tmpl.html('');
	});
});