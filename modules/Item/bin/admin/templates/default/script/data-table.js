var item = {};

item.dataTable = function(){

	item.actionByPrimary();
	item.checkAll();

};

item.actionByPrimary = function(){
	t = document.getElementsByAttribute('item-primary-value');

	if(!defined(t))return;
	for(i=0;i<t.length;i++){
		t[i].addEventListener('click',function(){
			f = this.getParentWithTag('FORM');

			f.getElementsByAttribute('item-primary')[0].value = this.getAttribute('item-primary-value');
			f.getElementsByAttribute('item-action')[0].value = this.value;
		},false);
	}
};

item.checkAll = function(){
	t = document.getElementsByAttribute('data-item-table-checkAll');
	
	if(!defined(t))return;
	for(i=0;i<t.length;i++){
		t[i].addEventListener('click',function(){
			f = this.getParentWithTag('FORM');

			f = f.getElementsByAttribute('data-item-table-check');
			
			for(i=0;i<f.length;i++){
				f[i].checked = this.checked;
			}

		},false);
	}
};

window.addEventListener('load',function(){
	item.dataTable();
},false);