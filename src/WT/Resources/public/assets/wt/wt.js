var WT = {};

WT.urls = {};

WT.interval = false;

WT.waiting = [
	"This is taking too long",
	"I'm searching the answer to the ultimate question of life, the universe, and everything",
    "The latest episode of Game of Thrones has just been released. A moment please",
    "I'm playing chess right now, wanna join me? <a href='https/it.lichess.org/'></a>",
    "OH YEAH! I WON 1,000,000$ !!! NOW I CAN WASTE ALL MY MONEY ON STEAM !!! Oh... I see.. it's just a scam...",
    "Oh, a free Ipad, guess I just need to download this exe file. OH GOD WHAT IS HAPPENING? A VIRUS? WHERE IS MY MLG ANTIVIRUS?"
];

WT.random = function(min,max){
    return Math.floor(Math.random()*(max-min+1)+min);
};

WT.search = function(){

};

WT.searching = function(state){

	clearTimeout(WT.interval);

	if(state){

		var waiting = WT.waiting[WT.random(0,WT.waiting.length - 1)];
		var html = template.get("wt-search-waiting",{waiting:waiting});
		$('.wt-search-waiting').html(html);

		WT.interval = setTimeout(function(){
			WT.searching(true);

		},5000);
	}else{
		
	}
};

$('.wt-search-form').on('submit',function(e){
	e.preventDefault();

	// Retrieve key searched
	val = $(this).find('.wt-search-key').val();

	// Set the searching mode to true
	WT.searching(true);

	// Set spinner
	$('.wt-search-results').html(template.get('wt-search-spinner'));

	// Send the request to "discovery"
	http.get(WT.url+"all/discovery/"+val,{token:WT.token},function(response){

		html = '';

		// The response has sent, so set the "searching mode" to false
		WT.searching(false);

		$.map(response,function(service){
			$.map(service,function(resource){
				
				html += template.get('wt-search-result',{
					source:resource.source,
					id:resource.id,
					title:resource.name,
					banner:resource.banner
				});

			});
		});

		html = $(html);

		html.find('img').on('error',function(){
			$(this).hide();
		});
		
		$('.wt-search-results').html(html);
	});
});


$('body').on('click','[wt-add]',function(e){

	var info = $(this).attr('wt-add');
	info = info.split(",");
	info[0]; // Service name
	info[1]; // ID resource

	console.log('awdw');
	http.post(WT.url+"series/add",{token:WT.token,source:info[0],id:info[1]},function(response){

		console.log(response);
	
	});

});

$(document).ready(function(){

});