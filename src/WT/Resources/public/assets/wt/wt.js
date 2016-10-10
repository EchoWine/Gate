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

WT.get = function(source_type,source_name,source_id,callback){

	http.get(WT.url+source_type+"/"+source_name+"/"+source_id,{token:WT.token},callback);
};


WT.all = function(callback){
	http.get(WT.url+"all",{token:WT.token},callback);
};


WT.sync = function(source_type,source_id,callback){

	http.post(WT.url+source_type+"/"+source_id,{token:WT.token},callback);
};


WT.random = function(min,max){
    return Math.floor(Math.random()*(max-min+1)+min);
};

WT.search = function(){

};

WT.stopSync = true;

WT.syncAll = function(){

	modal.open('modal-wt-sync-all',{},{"close":function(){
		console.log("Stopping...");
		WT.stopSync = true;
	}});
	
	var status = $('.wt-sync-current-status');
	var progress = $('.wt-sync-current-progress');
	var bar = $('.wt-sync-current-bar');

	var manager = function(results,i,attempt,length){

		if(WT.stopSync)
			return;

		if(i >= length){
			status.html("Completed");
			progress.html("100%");
			bar.find('span').css('width',"100%");
			return;
		}

		resource = results[i];

		attempt_text = attempt == 0 ? '' : ' #'+(attempt)+'';
		status.html(resource.name+attempt_text);
		p = (i + 1) * (length / 100);
		p = parseFloat(p).toFixed(2);
		progress.html(p+"%");
		bar.find('span').css('width',p+"%");

		WT.sync(resource.type,resource.id,function(response){
			if(response.status == 'success'){
				manager(results,i+1,1,length);
			}else if(response.status == 'error'){
				manager(results,i,attempt + 1,length);
			}
		});
			
	};

	// Retrieve all resources
	WT.all(function(response){
		length = 0;
		for(i in response){
			length++;
		}

		WT.stopSync = false;

		manager(response,0,1,length);
	});
};

WT.searching = function(state){

	clearTimeout(WT.interval);

	if(state){

		$('.wt-section-container').attr('data-status',0);

		var waiting = WT.waiting[WT.random(0,WT.waiting.length - 1)];
		var html = template.get("wt-search-waiting",{waiting:waiting});
		$('.wt-search-waiting').html(html);

		WT.interval = setTimeout(function(){
			WT.searching(true);

		},5000);

	}else{
		$('.wt-section-container').attr('data-status',1);
	}
};

WT.discovery = function(value){
	// Set the searching mode to true
	WT.searching(true);

	// Set spinner
	$('.wt-search-spinner-container').html(template.get('wt-search-spinner'));

	// Send the request to "discovery"

	http.get(WT.url+"all/discovery/"+encodeURIComponent(val),{token:WT.token},function(response){

		html = {library:'',discovery:''};

		// The response has sent, so set the "searching mode" to false
		WT.searching(false);

		$.map(response,function(service){
			$.map(service,function(resource){

				var part = (resource.user == 1) ? 'library' : 'discovery';

				html[part] += template.get('wt-search-result',{
					source:resource.source,
					id:resource.id,
					title:resource.name,
					banner:resource.banner,
					user:resource.user ? 1 : 0,
					library:resource.library ? 1 : 0
				});

			});
		});

		WT.addResultSearch('.wt-search-library',html['library']);

		WT.addResultSearch('.wt-search-discovery',html['discovery']);
	});
};

WT.addResultSearch = function(classname,html){

	html = $(html);
	
	html.find('img').on('error',function(){
		$(this).hide();
	});

	$(classname).html(html);
};

$('.wt-search-form').on('submit',function(e){
	e.preventDefault();

	// Retrieve key searched
	val = $(this).find('.wt-search-key').val();

	WT.discovery(val);
});


$('body').on('click','[wt-add]',function(e){
	WT.searching(true);
	var element = $(this);
	info = $(this).attr('wt-add').split(",");

	http.post(WT.url+"series/add",{token:WT.token,source:info[0],id:info[1]},function(response){
		WT.searching(false);
		item.addAlert('alert-'+response.status,'.alert-global',response);
		res = element.closest('.wt-search-result');
		res.attr('wt-status-user',1);
		res.appendTo($('.wt-search-library'));

	});

});

$('body').on('click','[wt-remove]',function(e){
	WT.searching(true);
	var element = $(this);
	info = $(this).attr('wt-remove').split(",");

	http.post(WT.url+"series/remove",{token:WT.token,source:info[0],id:info[1]},function(response){
		WT.searching(false);
		item.addAlert('alert-'+response.status,'.alert-global',response);
		res = element.closest('.wt-search-result');
		res.attr('wt-status-user',0);
		res.appendTo($('.wt-search-discovery'));
	
	});

});

$('body').on('click','[wt-sync]',function(e){
	
	info = $(this).attr('wt-sync').split(",");

	WT.sync(info[0],info[1],function(response){
		console.log(response);
		item.addAlert('alert-'+response.status,'.alert-global',response);
	});

});



$('body').on('click','[wt-info]',function(e){

	info = $(this).attr('wt-info').split(",");

	WT.get(info[0],info[1],info[2],function(response){

		// Group episode in season
		var seasons = [];
		for(var i in response.episodes){
			episode = response.episodes[i];

			if(typeof seasons[episode.season_n] == 'undefined')
				seasons[episode.season_n] = [];

			seasons[episode.season_n].push(episode);
		}


		// Templating seasons
		html_seasons = '';
		for(var i in seasons){
			var season = seasons[i];
			html_episodes = '';
			
			// Templating episodes
			for(e = 0; e < season.length; e++){
				episode = season[e];
				html_episodes += template.get('wt-get-episode',{
					number: episode.number,
					name: episode.name,
					season: episode.season_n,
					aired_at : episode.aired_at
				});

			}

			html_seasons += template.get('wt-get-season',{
				'number': i,
				'episodes': html_episodes,
			});

		}

		switch(response.status){
			case 'continuing':
				status_type = 'primary';
			break;
			case 'ended':
				status_type = 'danger';
			break;
			default:
				status_type = 'default';
			break;
		}

		content = template.get('wt-get-serie',{
			id:response.id,
			name:response.name,
			banner:response.banner,
			overview:response.overview,
			updated_at:response.updated_at,
			status:response.status,
			status_type:status_type,
			seasons: html_seasons,
			resource_id: response.resource.source_id,
			resource_name: response.resource.source_name
		});


		modal.open('modal-wt-get',{"modal-wt-get-body":content});
	});

});

$(document).ready(function(){});

$('body').on('click','[wt-sync-all]',function(e){
	
	WT.syncAll();

});


// ----------------------------------------------------------------
// 
// 	DASHBOARD
//
// ----------------------------------------------------------------

$('body').on('click','.wt-get-season',function(){
	var status = $(this).closest('.wt-get-season-container').attr('data-active') == "1";
	$(this).closest('.wt-get-season-container').attr('data-active',status == "1" ? "0" : "1");
});