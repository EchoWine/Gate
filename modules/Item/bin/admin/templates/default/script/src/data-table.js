var item = {};

item.dataTable = function(){

	item.actionByPrimary();
	item.checkAll();

};

item.actionByPrimary = function(){
	t = document.getElementsByAttribute('data-item-table-action');

	for(i=0;i<t.length;i++){
		t[i].addEventListener('click',function(){
			f = this.getParentWithTag('FORM');

			f.getElementsByAttribute('data-item-table-primary')[0].value = this.dataset.itemTableAction;
		},false);
	}
};

item.checkAll = function(){
	t = document.getElementsByAttribute('data-item-table-checkAll');

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