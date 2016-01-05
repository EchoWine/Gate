itemBox = {};

itemBox.load = function(){
	el = document.getElementsByAttribute('confirm-event');
	for(i = 0;i < el.length;i++){
		a = el[i].getAttribute('confirm-event');
		a = a.split(",");

		target = el[i].getAttribute('confirm-target');
		target = target == 'this' ? el[i] : document.getElementById(target);

		itemBox.addEvent(el[i],a[0],a[1],target);
	}

};

itemBox.addEvent = function(el,ev1,ev2,target){
	el.addEventListener(ev1,function(e){

		if(target.tagName == 'SELECT'){
			target = target.options[target.selectedIndex];
		
		}

		title = target.getAttribute('box-title');
		desc = target.getAttribute('box-desc');

		box.confirm(title,desc,function(){
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