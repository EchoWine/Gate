var item = {};

item.dataTable = function(){

	item.actionByPrimary();

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

window.addEventListener('load',function(){
	item.dataTable();
},false);