
itemBox = {};

itemBox.load = function(){
	el = document.getElementsByAttribute('confirm-event');
	for(i = 0;i < el.length;i++){
		a = el[i].getAttribute('confirm-event');
		a = a.split(",");
		itemBox.addEvent(el[i],a[0],a[1]);
	}

};

itemBox.addEvent = function(el,ev1,ev2){
	el.addEventListener(ev1,function(e){
		box.confirm("Conferma azione","Sei sicuro di voler eliminare?",function(){
			itemBox.callEvent(el,ev2);
		});
		e.preventDefault();
	},false);
}

itemBox.callEvent = function(el,ev){
	switch(ev){
		case 'submit':
			el.getParentWithTag('FORM').submit();
		break;
	}
}

window.addEventListener('load',function(){
	itemBox.load();
},false);