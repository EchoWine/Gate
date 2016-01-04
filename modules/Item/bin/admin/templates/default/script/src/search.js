item.search = function(){
	
	// Reset
	
	//block submit search if empty
	resetSearch = document.getElementById('resetSearch');


	if(defined(resetSearch)){
		resetSearch.addEventListener("click",function(){

			t = document.getElementById('dataTable_searchBar');
			c = [];
			c = c.concat(t.getElementsByTagName('INPUT'));
			c = c.concat(t.getElementsByTagName('TEXTAREA'));
			c = c.concat(t.getElementsByTagName('SELECT'));

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
		},false);
	}

	item.multipleSearch();
};

item.multipleSearch = function(){
	data_search = getAllElementsWithAttribute('data-search-data');
	if(defined(data_search)){
		for(i = 0;i < data_search.length; i++){

			l = data_search[i];
			l = l.getElementsByClassName('container-input-search');
			if(l != null && l[0] != null){
				l = l[0];
				for(y = 0;y < l.childNodes.length;y++){

					r = l.childNodes[y];
					r.setAttribute('autocomplete','off');

					r.addEventListener("keypress",function(e){
							
						// Controllo se ha premuto invio
						if(this.value != '' && e.keyCode == 13){
							item.msAdd(this.parentNode);
							item.msSetActive(this.parentNode.parentNode);
							e.preventDefault();
							this.value = '';
						}

					},false);

					r.addEventListener("focus",function(e){
						item.msSetActive(this.parentNode.parentNode);
					},false);

					r.addEventListener("click",function(e){
						item.msSetActive(this.parentNode.parentNode);
					},false);
				}
			}

		}

		t = document.getElementsByClassName('container-delete-key');
		if(defined(t)){
			for(i=0;i<t.length;i++){
				item.msAddEventDelete(t[i]);
			}
		}

		document.addEventListener('click',function(e){
			l = getParentAttribute(e.target,'data-search-data');
			if(l == null)
				item.msSetInactive();
			
		},false);
	}
};

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

item.msAddEventDelete = function(e){
	e.firstChild.addEventListener('click',function(e){
		l = this.parentNode.parentNode;
		p = l.parentNode;
		p.removeChild(l);

		if(p.childNodes.length == 0)
			item.msSetInactive(p,true);

	},false);
};

item.msSetActive = function(e,t){
	item.msSetInactive();

	if(!t)
		e = e.getElementsByClassName("container-key-search")[0];

	if(e.childNodes.length != 0)
		e.setAttribute('active','');

	item.msActual = e;
	item.msActualParent = getParentAttribute(e,'data-search-data');
};

item.msSetInactive = function(){
	if(item.msActual == null)return;

	item.msActual.removeAttribute('active');
	item.msActual = null;
	item.msActualParent = null;
};

window.addEventListener('load',function(){item.search();},false);