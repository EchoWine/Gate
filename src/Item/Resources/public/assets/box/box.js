box = {};

/**
 * Create a confirm box
 * @param {string} title 
 * @param {string} message
 * @param {function} callback
*/
box.confirm = function(title,message,callback){
	box.setWindowOpen('box-confirm');
	box.setWindowInformation('box-confirm',title,message);
	box.setWindowSend('box-confirm',callback);
	box.setWindowClose('box-confirm',close);
};

/**
 * Activate/Deactivate obfuscator
 * @param {int} status
*/
box.setContainerVisible = function(status){
	box.setWindowVisible('box-container',status);
};

/**
 * Activate/Deactivate obfuscator
 * @param {string} id
 * @param {int} status
*/
box.setWindowVisible = function(id,status){
	box.setAttributeById(id,'active',status);
};

/**
 * Add/Remove attribute by id
 * @param {string} id
 * @param {string} attribute
 * @param {int} status
*/
box.setAttributeById = function(id,attribute,status){
	box.setAttribute(document.getElementById(id),attribute,status);
};

/**
 * Add/Remove attribute
 * @param {document} element
 * @param {string} attribute
 * @param {int} status
*/
box.setAttribute = function(el,attribute,status){
	status 
		? el.setAttribute(attribute,'')
		: el.removeAttribute(attribute);
};

/**
 * Set information in window
 * @param {string} id
 * @param {string} title
 * @param {string} message
*/
box.setWindowInformation = function(id,title,message){
	el = document.getElementById(id);
	el.getElementsByAttribute('box-data-title')[0].innerHTML = title;
	el.getElementsByAttribute('box-data-message')[0].innerHTML = message;
};

/**
 * Open window
 * @param {string} id
*/
box.setWindowOpen = function(id){
	box.setContainerVisible(1);
	box.setWindowVisible(id,1)
}

/**
 * Set callback function when confirm is clicked
 * @param {string} id
 * @param {function} callback
*/
box.setWindowSend = function(id,callback){
	el = document.getElementById(id);
	el.getElementsByAttribute('box-send')[0].addEventListener('click',function(){

		box.setContainerVisible(0);
		box.setWindowVisible(id,0);
		callback();

	},false);
};

/**
 * Set close
 * @param {string} id
 * @param {function} callback
*/
box.setWindowClose = function(id){
	el = document.getElementById(id);
	el = el.getElementsByAttribute('box-close');
	for(i = 0; i < el.length;i++){
		el[i].addEventListener('click',function(){

			
			box.setContainerVisible(0);
			box.setWindowVisible(id,0);

		},false);
	}
};