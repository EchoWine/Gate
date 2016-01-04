WindowPrompt = {}


WindowPrompt.load = function(){
	var windowS = document.getElementsByClassName('c-window');
	if(defined(windowS)){
		for(var i = 0;i < windowS.length; i++){

			cl = windowS[i].getElementsByClassName('wClose');
			if(defined(cl[0])){
				for(k = 0; k < cl.length; k++){
					!function o(i){
						cl[k].addEventListener("click",function(e){
							if(this.className !== 'wClose -s'){
								windowS[i].removeAttribute('active');
								document.getElementById('obfuscator').removeAttribute('active');
							}
						},false);
						cl[k].addEventListener("submit",function(e){

							if(this.className === 'wClose -s'){
								windowS[i].removeAttribute('active');
								document.getElementById('obfuscator').removeAttribute('active');
							}
						},false);
					}(i);
				}
			}
		}
	}
	

	// Window and obfuscator
	data_button_window = getAllElementsWithAttribute('data-button-window');
	if(defined(data_button_window)){
		for(i = 0;i < data_button_window.length; i++){
			data_button_window[i].addEventListener("click",function(e){

				WindowPrompt.open(this);
			
			},true);
		}
	}
	
	// Send form from window
	data_window_form = getAllElementsWithAttribute('data-window-form');
	if(defined(data_window_form)){
		for(i = 0;i < data_window_form.length; i++){
			
			submit = data_window_form[i];
			submit.addEventListener("submit",function(e){

				e.preventDefault();
				win = getParentWithTag(this,'FORM');
				console.log(WindowPrompt.e);
				form = getParentWithTag(WindowPrompt.e,'FORM');;
				if(form == null)form = win;

				data = win.getElementsByClassName('wData');
				
				for(h = 0; h < data.length; h++){
					if(data[h].getAttribute('type') == 'file'){
						nxt = data[h].nextSibling;
						ns = document.createElement('INPUT');
						ns.setAttribute('type','file');
						ns.className = data[h].className;
						data[h].parentNode.insertBefore(ns,nxt);
						data[h].style.display = 'none';
						form.appendChild(data[h]);
					}else{
						value = data[h].value;
						if(data[h].getAttribute('type') == 'radio'){
							c = document.getElementsByName(data[h].name);
							f = data[h];
							for(p = 0;p < c.length; p++){
								if(c[p].checked){
									f = c[p];
									break;
								}
							}
							value = f.value;
						}
						n = document.createElement('INPUT');
						n.value = value;
						n.name = data[h].name;
						n.setAttribute('type','hidden');
						form.appendChild(n);
					}
				}

				if(defined(this.dataset.windowBlank)){
					form.setAttribute('target','_blank');
					form.submit();
					setTimeout(function(){location.href=location.href}, 1);

				}else{
					form.submit();
				}
				
			},false);
		}
	}
};

WindowPrompt.open = function(e){
	this.e = e;
	if(defined(document.getElementById(e.dataset.buttonWindow))){
		document.getElementById(e.dataset.buttonWindow).setAttribute('active','');
		v = e.dataset.windowValue;
		if(defined(v)){
			v = v.split('|');
			if(defined(document.getElementById(e.dataset.buttonWindow).getElementsByTagName('SELECT')[0])){
				for(l = 0;l < v.length; l++){
					document.getElementById(e.dataset.buttonWindow).getElementsByTagName('SELECT')[0].getElementsByTagName('OPTION')[l].value = v[l];
				}
			}else{
				for(l = 0;l < v.length; l++){
					el = document.getElementById(e.dataset.buttonWindow).getElementsByClassName('wData')[l];
					if(el.tagName == 'SPAN')
						el.innerHTML = v[l];
					else 
					el.value = v[l];
				}
			}
		}
					
		document.getElementById('obfuscator').setAttribute('active','');
	}
}

window.addEventListener('load',function(){WindowPrompt.load();},false);