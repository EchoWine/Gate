function setCookie(name,value,expire){
	exp = new Date();
	now = new Date();
	exp.setTime(now.getTime() + (parseInt(expire) * 60000));
	document.cookie = name + '=' + escape(value) + '; expires=' + exp.toGMTString() + '; path=/';
}

function getCookie(name){
	if(document.cookie.length > 0){
		s = document.cookie.indexOf(name + "=");
		if(s != -1){
			s = s + name.length + 1;
			e = document.cookie.indexOf(";",s);
			if(e == -1) e = document.cookie.length;
			return unescape(document.cookie.substring(s,e));
		}
	}

	return null;
}

function removeCookie(name){
	setCookie(name,'',-1);
}