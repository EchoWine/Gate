var Serie = {};

Serie.urls = {};

Serie.interval = false;

Serie.waiting = [
	"This is taking too long",
	"I'm searching the answer to the ultimate question of life, the universe, and everything",
    "The latest episode of Game of Thrones has just been released. A moment please",
    "I'm playing chess right now, wanna join me? <a href='https/it.lichess.org/'></a>",
    "OH YEAH! I WON 1,000,000$ !!! NOW I CAN WASTE ALL MY MONEY ON STEAM !!! Oh... I see.. it's just a scam...",
    "Oh, a free Ipad, guess I just need to download this exe file. OH GOD WHAT IS HAPPENING? A VIRUS? WHERE IS MY MLG ANTIVIRUS?"
];

Serie.random = function(min,max){
    return Math.floor(Math.random()*(max-min+1)+min);
};

Serie.search = function(){

};

Serie.searching = function(state){

	clearTimeout(Serie.interval);

	if(state){

		var waiting = Serie.waiting[Serie.random(0,Serie.waiting.length - 1)];
		var html = template.get("serie-search-waiting",{waiting:waiting});
		$('.serie-search-waiting').html(html);

		Serie.interval = setTimeout(function(){
			Serie.searching(true);

		},5000);
	}else{
		
	}
};

$('.serie-search-form').on('submit',function(e){
	e.preventDefault();
	val = $(this).find('.serie-search-key').val();

	Serie.searching(true);

	$('.serie-search-results').html(template.get('serie-search-spinner'));

	http.get(Serie.url+"all/discovery/"+val,{},function(response){

		html = '';

		Serie.searching(false);
		$.map(response,function(service){
			$.map(service,function(resource){

				resource.id;
				resource.banner;
				resource.name;
				html += template.get('serie-search-result',{title:resource.name,banner:resource.banner});
			});
		});

		html = $(html);

		html.find('img').on('error',function(){
			$(this).hide();
		});
		
		$('.serie-search-results').html(html);
	});
});

$(document).ready(function(){

});