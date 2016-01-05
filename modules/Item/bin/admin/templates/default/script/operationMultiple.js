item.operationMultiple = function(){

	//block submit search if empty
	multipleButton = document.getElementById('button_operationMultiple');
	valueButton = document.getElementById('value_operationMultiple');

	if(defined(multipleButton)){
		


		multipleButton.addEventListener('click', function(e){


			// Get parent form
			el = this.getParentWithTag('FORM');


			s = el.getElementsByAttribute('data-item-table-check');

			// Block if there isn't a checkbox enabled
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

			// Block if the value is empty
			if(valueButton.value == ''){

				e.preventDefault();
				return;
			}

		},false);	
	}


};

item.preventNullSelected = function(s){


	for(i = 0;i < s.length; i++){
		if(s[i].checked){
			return true;
		}
	}

	return false;
}
window.addEventListener('load',function(){item.operationMultiple();},false);