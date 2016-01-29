window.onload = function(){

	//Anchor
	a = document.getElementsByTagName('A');
	for(i = 0;i < a.length; i++){
		if (typeof a[i].dataset.anchor !== 'undefined'){
			a[i].setAttribute('href',a[i].getAttribute('href') + "#" + window.location.hash.substring(1));
		}
	}
	
	
};

function getParentElement(node,element){
	if(node == null)return null;

	while(node != null && node.nodeType === 1 && node != element){
		node = node.parentNode;
	}

	return node.nodeType !== 1 ? null : node;
};

function getParentAttribute(node,attribute){
	if(node == null)return null;

	while(node != null && node.nodeType === 1 && !node.hasAttribute(attribute)){
		node = node.parentNode;
	}

	return node == null || node.nodeType !== 1 ? null : node;
};


function getParentClassName(node,classs){
	while(node.className != classs){
		node = node.parentNode;
	}
	return node;
};

function getParentWithTag(node,tagName){
	while(node != null &&  node.tagName != tagName){
		node = node.parentNode;
	}
	return node;
};

function getAllElementsWithAttribute(attribute,value){
	matchingElements = [];
	allElements = document.getElementsByTagName('*');
	for(i = 0, n = allElements.length; i < n; i++){
		if((allElements[i].hasAttribute(attribute) && !value) || (value && allElements[i].getAttribute(attribute) == value)){
			matchingElements.push(allElements[i]);
		}
	}
	
	if(isEmpty(matchingElements)) return null;
	return matchingElements;
};

function defined(v){
	return ((typeof(v) != 'undefined') && (v != null)) ? true : false;
};

function isEmpty(obj) {
	if (obj == null) return true;
	if (obj.length === 0)  return true;
	return false;
};

/**
 * Add an event to all elements
 * @param {array} elements 
 * @param {string} event
 * @param {function} callback
 */
function addEventToElements(elements,event,callback){
	if(!defined(elements))return;
	for(var i = 0;i < elements.length; i++){
		elements[i].addEventListener(event,callback,false);
	}
}

/**
 * Add an event to element
 * @param {element} element 
 * @param {string} event
 * @param {function} callback
 */
function addEventToElement(element,event,callback){
	if(!defined(element))return;

	element.addEventListener(event,callback,false);
}


/**
 * foreach
 * @param {array} elements
 * @param {function} callback
 */
function foreach(array,callback){
	if(!defined(array))return;

	for(var i = 0;i < array.length;i++)
		callback(array[i]);
	
}