var subMenu = {};

subMenu.compact = 1;

subMenu.search = function(){
	st_s = document.getElementsByClassName('status_search');
	if(defined(st_s[0]) && defined(st_s[1])){
		st_s[0].addEventListener("click",function(e){
			st_s[0].removeClass('visible');
			st_s[1].addClass('visible');
		},false);
		
		st_s[1].addEventListener("click",function(e){
			st_s[1].removeClass('visible');
			st_s[0].addClass('visible');
		},false);
	}
}


subMenu.status = function(){

	cl_st = document.getElementsByClassName('cl_st');

	if(getCookie('menuCompact') == 1)
		subMenu.changeStatus();

	for(i = 0;i < cl_st.length; i++){

		cl_st[i].addEventListener("click",function(e){
			setCookie('menuCompact',subMenu.compact,3600);
			subMenu.changeStatus();
		},false);


	}
	 
};

subMenu.changeStatus = function(){
	cl = document.getElementById('container-content-left');
	subMenu.compact ? cl.addClass('compact') : cl.removeClass('compact');
	subMenu.compact = subMenu.compact ? 0 : 1;
}

subMenu.check = function(){

	this.status();
	this.search();

	//Menu
	data_menu = getAllElementsWithAttribute('data-menu');
	

	if(defined(data_menu)){
		for(i = 0;i < data_menu.length; i++){

			/*if(data_menu[i].dataset.menu == info['cat']){
				this.active(data_menu[i]);
				data_menu[i].className = 'active';
				data_menu[i].addClass('pre-active');
				if(defined(document.getElementById(info['page'])))
					document.getElementById(info['page']).className = 'act';
			}*/

			data_menu[i].addEventListener("click",function(e){
				if(!subMenu.compact)return;
				v = document.getElementsByClassName('pre-active');
				if(defined(v[0])){
					v[0].removeClass('pre-active');
				}

				actual = getAllElementsWithAttribute('data-menu-actual');
				if(defined(actual)){
					if(actual[0] != this){
						subMenu.inactive(actual[0]);
					}
				}
				
				subMenu.active(this);
				this.setAttribute('data-menu-actual','');
			},false);
		}
	}
	/*
	data_n = getAllElementsWithAttribute('data-n');
	console.log(data_n);
	for(i = 0;i < data_n.length; i++){
		data_n[i].setAttribute('data-n',data_n[i].getElementsByTagName('li').length);
	}*/

};

subMenu.active = function(el){
	el.setAttribute('data-menu-actual','');
	if(defined(el.getElementsByClassName('active')[0])){
		el.getElementsByClassName('active')[0].addClass('visible');
		el.getElementsByClassName('inactive')[0].removeClass('visible');
	}			
};

subMenu.inactive = function(el){
	el.removeAttribute('data-menu-actual');
	if(defined(el.getElementsByClassName('active')[0])){
		el.getElementsByClassName('active')[0].removeClass('visible');
		el.getElementsByClassName('inactive')[0].addClass('visible');
	}			
};


window.addEventListener('load',function(){subMenu.check();},false);