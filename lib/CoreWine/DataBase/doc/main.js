
var code = {};
window.addEventListener('load',function(){code.load();},false);

code.load = function(){
	c = document.getElementsByClassName('code');
	for(i = 0;i < c.length;i++){
		if(c[i].className != 'code inline')c[i].innerHTML = this.parse(c[i].innerHTML);
	}
}

code.parse = function(v){
	v = v.replace(/(\t){4}/g,'');
	v = v.split("\n");
	v.splice(0,1);
	v.splice(v.length-1,1);
	v = v.join("\n");
	v = v.replace(/\n/g,"<br>");
	v = v.replace(/\t/g,"&nbsp;&nbsp;&nbsp;&nbsp;");
	v = this.highlight(v);
	return v;
}

code.highlight = function(v){

	v = v.replace(/'([^']*)'/g,"<span class='token string'>'$1'</span>");
	v = v.replace(/"([^"]*)"/g,"<span class='token string'>\"$1\"</span>");

	v = v.replace(
		/(\w*)::/g,
		"<span class='token scope'>$1</span>::"
	);

	v = v.replace(
		/(->)/g,
		"<span class='token symbol'>$1</span>"
	);

	v = v.replace(
		/([\(\)\]\[])/g,
		"<span class='token punctuation'>$1</span>"
	);

	v = v.replace(
		/([0-9]{1,})(?=[^\w])/g,
		"<span class='token number'>$1</span>"
	);

	v = v.replace(/\$(\w*)/g,"<span class='token variable'>&#36;$1</span>");
	v = v.replace(
		/\b(new|if|do|function|while|switch|for|foreach|as|continue|break|echo|return|array)(?=[^\w])/g,
		"<span class='token keyword'>$1</span>"
	);
	return v;
}