// TODO - Add initial page loading and handlers
(function($,History){
	History._replaceState = History.replaceState;
	History.replaceState = function(){
		console.log('replaceState'+JSON.stringify(arguments));
		History._replaceState.apply(this,arguments);
	};
	History._pushState = History.pushState;
	History.pushState = function(){
		console.log('pushState'+JSON.stringify(arguments));
		History._pushState.apply(this,arguments);
	};
	var State = History.getState(),
		flag = false,
		exists = function(v){
			return typeof v != 'undefined';
		},
		api = function(data,callback){
			data.get = 'api';
			$.get(location.href,data,function(d){
				if(location.href.substr(location.href.lastIndexOf('/')+1) != d.state.url){
					History.pushState(d.state.data,d.state.title,d.state.url);
				}
				if(exists(callback)){
					callback(d);
				}
			},'json');
		},
		loadState = window.loadState = function(href,callback){
			$.get(href,{get:'state'},function(d){
				History.pushState(d.state.data,document.title,href);
				State = History.getState();
				if(exists(callback)){
					callback();
				}
			},'json');
		},
		apiState = window.apiState = function(href,callback){
			$.get(href,{get:'state'},function(d){
				History.replaceState(d.state.data,document.title,href);
				State = History.getState();
				if(exists(callback)){
					callback();
				}
			},'json');
		};
	$(document).ready(function(){
		$(window).on('statechange',function(){
			var Old = State;
			State = History.getState();
			if(State.data.type != Old.data.type || State.data.id != Old.data.id){
				console.log("State change. "+JSON.stringify(State));
				switch(State.data.type){
					case 'template':
						api(State.data,function(d){
							$('#content').html(Handlebars.compile(d.template)(d.context)).mCustomScrollbar('destroy');
							$('#content,.scroll').mCustomScrollbar({
								theme: 'dark-2',
								scrollInertia: 0
							});
							$('#content').find('a').each(function(){
								var href = this.href;
								if(href.indexOf(location.origin) != -1 && href.indexOf('#') != -1){
									href = href.substr(href.indexOf('#')+1);
									console.log('Setting up link to '+href);
									$(this).click(function(){
										loadState(href);
										return false;
									});
								}
							});
						});
					break;
					default:
						alert("Something went wrong!\nYour current state:\n"+JSON.stringify(State));
				}
			}
		});
		if($.isEmptyObject(State.data)){
			History.replaceState({
				type: 'template',
				id: 'index'
			},'Bugs','page-index');
			console.log('Forcing default state.');
		}else{
			flag = true;
		}
		apiState(location.href,function(){
			if(flag){
				State.data = {
					type: '',
					data: ''
				};
			}
			$(window).trigger('statechange');
		});
	});
})(jQuery,History);