var Serie = {};

Serie.urls = {};

Serie.interval = false;

Serie.waiting = [
	"I'm searching the answer to the ultimate question of life, the universe, and everything",
	"The last episode of Game of Thrones has just released. A moment please",
	"I'm playing chess right now, do you want join me? <a href='https://it.lichess.org/'></a>",
	"OH YEAH! I WON ! I WON 1,000,000$ !!! NOW I CAN WASTE ALL MY MONEY IN STEAM !!! Oh... I see... it's another scam",
	"Oh, a free ipad, i guess i can just click and download this 'free ipad'. Oh, what's this shit?? A virus? A need an mlg antivirus FAST!!!"
];

Serie.random = function(min,max){
    return Math.floor(Math.random()*(max-min+1)+min);
};

Serie.search = function(){

};

Serie.searching = function(state){

	if(state){

		var waiting = Serie.waiting[Serie.random(0,Serie.waiting.length - 1)];
		var html = template.get("serie-search-waiting",{waiting:waiting});
		$('.serie-search-waiting').html(html);

		Serie.interval = setTimeout(function(){
			Serie.searching(true);

		},2000);
	}else{
		clearTimeout(Serie.interval)
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
			$.map(response,function(resource){

				resource.id;
				resource.banner;
				resource.name;
				html += template.get('serie-search-result',{title:resource.name,banner:resource.banner});
			});
		});
		
		$('.serie-search-results').html(html);
	});
});

$(document).ready(function(){

});