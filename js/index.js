// TODO - Add initial page loading and handlers
(function($,History){
	var State = History.getState(),
		api = function(data,callback){
			$.get('api.php',data,function(d){
				if(location.href.substr(location.href.lastInstanceOf('/')) != d.state.url){
					History.pushState(d.state.data,d.state.title,d.state.url);
				}
				callback(d);
			},'json');
		};
	$(document).ready(function(){
		if($.isEmptyObject(State.data)){
			History.pushState({
				type: 'template',
				id: 'index'
			},'Bugs','page-index');
		}
		$(window).on('statechange',function(){
			State = History.getState();
			switch(State.data.type){
				case 'template':
					$.get('api.php',State.data,function(d){
						$('#content').html(Handlebars.compile(d.template)(d.context)).mCustomScrollbar('destroy');
						$('#content,.scroll').mCustomScrollbar({
							theme: 'dark-2',
							scrollInertia: 0
						});
						$('#content').find('a').each(function(){
							if(this.href[0] == '#'){
								
							}
						});
					},'json');
				break;
				default:
					alert("Something went wrong!\nYour current state:\n"+JSON.stringify(State));
			}
		}).trigger('statechange');
	});
})(jQuery,History);