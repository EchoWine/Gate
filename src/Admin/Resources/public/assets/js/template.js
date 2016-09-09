var template = {};

/**
 * List of all source template
 *
 * @var {array}
 */
template.source = {};

/**
 * Set html using a template
 *
 * @param {string} source template
 * @param {object} vars
 * @param {string} destination
 */
template.set = function(source,vars,destination){

	var html = template.get(source,vars);

	template.html(html,destination);
}

/**
 * Set html using a string
 *
 * @param {string} html
 * @param {string} destination
 */
template.html = function(html,destination){

	$(destination).html(html);
	
	setTimeout(function(){
		$('.template-new').removeClass('template-new');
   	},50);
}

/**
 * Get html using template source and vars
 *
 * @param {string} source template
 * @param {object} vars
 *
 * @return {string} html
 */
template.get = function(source,vars){

	var source = template.getSource(source);

	var html = template.vars(source.html(),vars);

	return html;

}

/**
 * Set all vars in html 
 *
 * @param {string} html
 * @param {object} vars
 *
 * @return {string}
 */
template.vars = function(html,vars){
	for(col in vars){
		html = html.replace(new RegExp('{'+col+'}', 'g'),vars[col]);
	};
	return html;
};

/**
 * Get source DOM given name
 *
 * @param {string} source name template
 *
 * @return {DOM}
 */
template.getSource = function(source){

	var source = $.parseHTML("<div>"+template.source[source]+"</div>");
	source = $(source);
	source.children().addClass('template-new');
	return source.clone();
};

/**
 * Initalize
 *
 * Search for all templates, save them and delete from html
 */
$(document).ready(function(){
	$.map($('[data-template]'),function(tmpl){
		tmpl = $(tmpl);
		var name = tmpl.attr('data-template');
		template.source[name] = tmpl.html();
		tmpl.remove();
	});
});