var item = {};

/**
 * Initialization
 */
item.ini = function(){

	item.action();
	item.selectAll();
	item.operationMultiple();
	item.resetSearch();
	item.multipleSearch();

};

/**
 * For each element that has the attribute 'item-primary-value' 
 * at the event 'click', the value of the the element (action) and 
 * the value of the attribute (primary key) will be copied at a specific area 
 * in order to perform particular actions
 */
item.action = function(){
	t = document.getElementsByAttribute('item-primary-value');

	addEventToElements(t,'click',function(){
		f = this.getParentWithTag('FORM');

		f.getElementsByAttribute('item-primary')[0].value = this.getAttribute('item-primary-value');
		f.getElementsByAttribute('item-action')[0].value = this.value;
	});
	
};

/**
 * Permits to select/unselect all checkbox 'item-check'
 * based on the value of 'item-checkall'
 */
item.selectAll = function(){
	t = document.getElementsByAttribute('item-checkall');
	
	addEventToElements(t,'click',function(){
		f = this.getParentWithTag('FORM');

		f = f.getElementsByAttribute('item-check');
		
		for(i = 0;i < f.length; i++)
			f[i].checked = this.checked;
		

	});
	
};

/**
 * Prevents sending 'multiple operation' if the value is empty
 * or there isn't at least one checkbox checked
 */
item.operationMultiple = function(){


	button = document.getElementById('button_operationMultiple');
	select = document.getElementById('value_operationMultiple');

	// Get name of 

	addEventToElement(button,'click', function(e){

		// Block if the value is empty
		if(select.value == ''){
			e.preventDefault();
			return;
		}

		// Get parent form
		el = this.getParentWithTag('FORM');

		s = el.getElementsByAttribute('item-check');

		// Block if there isn't at least one checkbox checked
		if(!item.preventNullSelected(s)){

			for(y = 0;y < s.length;y++){

				c = s[y];
				c.setAttribute('data-hover','');

				(function(c){
					setTimeout(function(){
						c.removeAttribute('data-hover','');
					},1000)
				}(c));
			}

			e.preventDefault();
			return;

		}



		opt = select.options[select.selectedIndex];

		switch(opt.getAttribute('item-actionMultiple')){
			case 'edit':
				
				// Select all check selected
				r = [];
				foreach(s,function(i){
					if(i.checked)r.push(i.value);
				});

				// Take first element as primary
				url = encodeURIComponent(r[0]);
				r.shift();

				// Get name for url
				name = opt.getAttribute('item-nameget')+"[]";

				// Convert all in url format
				last = [];
				foreach(r,function(i){
					last.push(name+"="+encodeURIComponent(i));
				});

				// Build url
				url = opt.getAttribute('item-baseurl')+url+"&"+last.join("&");
				url = url.replace('&amp;','&');

				e.preventDefault();
				window.location.replace(url);

			break;
		}
	});	
	
	addEventToElement(document.getElementById('item-edit-take'),'change', function(e){

		name = this.getAttribute('item-getname');
		baseurl = this.getAttribute('item-baseurl');

		r = this.options;
		a = [];

		foreach(r,function(i){
			a.push(i.value);
		});
		if(a.length > 0){

			// Rimuovo l'id da prendere
			a.splice(r.selectedIndex, 1);

			// Converto tutti gli id in formato url
			url = [];
			for(i = 0;i < a.length;i++){
				url.push(name+"[]="+encodeURIComponent(a[i]));
			}

			// Effettuo reindirizzamento
			url = baseurl+encodeURIComponent(this.value)+"&"+url.join("&");
			url = url.replace('&amp;','&');
			window.location.replace(url);
		}

	});

};

/**
 * Return true if at least one checkbox is checked
 * @param {array} elements
 * @return {bool}
 */
item.preventNullSelected = function(elements){

	for(i = 0;i < elements.length; i++){
		if(elements[i].checked)
			return true;
		
	}

	return false;
}

/**
 * Reset search when the button is clicked
 */
item.resetSearch = function(){
	

	resetSearch = document.getElementById('resetSearch');

	addEventToElement(resetSearch,'click',function(){

		t = document.getElementById('dataTable_searchBar');
		c = [];
		c = c.concat(t.getElementsByTagName('INPUT'));
		c = c.concat(t.getElementsByTagName('TEXTAREA'));
		c = c.concat(t.getElementsByTagName('SELECT'));

		// Reset select
		for(i = 0;i < c.length;i++){

			for(y = 0;y < c[i].length;y++){
				c[i][y].value = "";
				if(c[i][y].checked)c[i][y].checked = false;
				c[i][y].selectedIndex = 0;
			}
		}

		t = document.getElementsByClassName('container-key-search');
		if(defined(t)){
			for(i=0;i<t.length;i++){
				t[i].innerHTML = '';
			}
		}
	});
};

/**
 * Manage multiple key searching
 */
item.multipleSearch = function(){
	data_search = getAllElementsWithAttribute('data-search-data');

	foreach(data_search,function(l){

		l = l.getElementsByClassName('container-input-search');

		if(l != null && l[0] != null){
			l = l[0];

			l = l.childNodes;

			foreach(l,function(r){
				r.setAttribute('autocomplete','off');

				addEventToElement(r,"keypress",function(e){
						
					// If pressed 'enter key' add the query to the list
					if(this.value != '' && e.keyCode == 13){
						item.msAdd(this.parentNode);
						item.msSetActive(this.parentNode.parentNode);
						e.preventDefault();
						this.value = '';
					}

				});

				addEventToElement(r,"focus",function(){
					item.msSetActive(this.parentNode.parentNode);
				});

				addEventToElement(r,"click",function(){
					item.msSetActive(this.parentNode.parentNode);
				});
			});
		};

	});


	foreach(document.getElementsByClassName('container-delete-key'),function(i){
		item.msAddEventDelete(i);
	});

	addEventToElement(document,'click',function(e){
		l = getParentAttribute(e.target,'data-search-data');
		if(l == null)
			item.msSetInactive();
		
	});
};

/**
 * Add a new element in the query
 * @param {element} e
 */
item.msAdd = function(e){

	n = e.cloneNode(true);
	c = e.parentNode.getElementsByClassName("container-key-search")[0];
	c.appendChild(n);
	t = document.createElement('DIV');
	t.className = 'container-delete-key';
	t.innerHTML = "<button type='button' class='button a i danger'> <span class='fa fa-trash'></span> </button>";
	n.appendChild(t);
	item.msAddEventDelete(t);
};

/**
 * Add event in order to delete itself if a button is clicked
 * @param {element} e
 */
item.msAddEventDelete = function(e){
	e.firstChild.addEventListener('click',function(e){
		l = this.parentNode.parentNode;
		p = l.parentNode;
		p.removeChild(l);

		if(p.childNodes.length == 0)
			item.msSetInactive(p,true);

	},false);
};

/**
 * Set the element as active in order to see all the query linked to that element
 * @param {element} e
 */
item.msSetActive = function(e,t){
	item.msSetInactive();

	if(!t)
		e = e.getElementsByClassName("container-key-search")[0];

	if(e.childNodes.length != 0)
		e.setAttribute('active','');

	item.msActual = e;
	item.msActualParent = getParentAttribute(e,'data-search-data');
};

/**
 * Set the element as inactive in order to hide all the query linked to that element
 * @param {element} e
 */
item.msSetInactive = function(){
	if(item.msActual == null)return;

	item.msActual.removeAttribute('active');
	item.msActual = null;
	item.msActualParent = null;
};

/**
 * Call when page is loaded
 */
window.addEventListener('load',function(){
	item.ini();
},false);