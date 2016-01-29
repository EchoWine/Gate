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


Element.prototype.getElementsByAttribute = function(att){
	r = [];
	a = this.getElementsByTagName('*');
	for(i = 0, n = a.length; i < n; i++){
		if(a[i].hasAttribute(att)){
			r.push(a[i]);
		}
	}
	
	if(isEmpty(r)) return null;
	return r;
};

Document.prototype.getElementsByAttribute = Element.prototype.getElementsByAttribute;

Element.prototype.getParentWithTag = function(tagName){
	node = this;
	while(node != null &&  node.tagName != tagName){
		node = node.parentNode;
	}
	return node;
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