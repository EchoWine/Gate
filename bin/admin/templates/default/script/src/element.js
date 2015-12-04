Element.prototype.removeClass = function(name){
	a = this.className.split(" ");
	a = a.remove(name);
	this.className = a.join(" ");
};

Element.prototype.addClass = function(name){
	a = this.className.split(" ");
	if(a.indexOf(name) == -1)a.push(name);
	this.className = a.join(" ");
};

Element.prototype.remove = function(){
	this.parent.removeChild(this);
};


getElementsByAttribute = function(att){
	matchingElements = [];
	allElements = document.getElementsByTagName('*');
	for(i = 0, n = allElements.length; i < n; i++){
		if(allElements[i].hasAttribute(att)){
			matchingElements.push(allElements[i]);
		}
	}
	
	if(isEmpty(matchingElements)) return null;
	return matchingElements;
};

Array.prototype.remove = function() {
	var what, a = arguments, L = a.length, ax;
	while (L && this.length) {
		what = a[--L];
		while ((ax = this.indexOf(what)) !== -1) {
			this.splice(ax, 1);
		}
	}
	return this;
};