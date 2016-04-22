itemBox = {};

itemBox.load = function(){
	el = document.getElementsByAttribute('confirm-event');
	foreach(el,function(i){
		a = i.getAttribute('confirm-event');
		a = a.split(",");

		target = i.getAttribute('confirm-target');
		target = target == 'this' ? i : document.getElementById(target);

		itemBox.addEvent(i,a[0],a[1],target);
	});

};

itemBox.addEvent = function(el,ev1,ev2,target){
	addEventToElement(el,ev1,function(e){
		itemBox.event(e,this,target,ev2);
	},false);
}

itemBox.event = function(e,el,target,ev2){
	if(target.tagName == 'SELECT'){
		target = target.options[target.selectedIndex];
	
	}

	title = target.getAttribute('box-title');
	desc = target.getAttribute('box-desc');


	if(!title)return true;

	box.confirm(title,desc,function(){
		itemBox.callEvent(el,ev2);
	});
	e.preventDefault();
};

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