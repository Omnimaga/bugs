// TODO - Add initial page loading and handlers
(function($,History){
	var State = History.getState(),
		flag = false,
		exists = function(v){
			return typeof v != 'undefined';
		},
		api = window.apiCall = function(data,callback){
			data.get = 'api';
			data.timestamp = +new Date;
			if(exists(State.data.key)){
				data.key = State.data.key;
			}
			$.get(location.href,data,function(d){
				if(exists(d['error'])){
					alert(d.error);
				}else{
					if(location.href.substr(location.href.lastIndexOf('/')+1) != d.state.url){
						History.pushState(d.state.data,d.state.title,d.state.url);
					}
					if(exists(callback)){
						callback(d);
					}
				}
			},'json');
		},
		loadState = window.loadState = function(href,callback){
			$.get(href,{get:'state',timestamp:+new Date},function(d){
				History.pushState(d.state.data,document.title,href);
				State = History.getState();
				if(exists(callback)){
					callback();
				}
			},'json');
		},
		apiState = window.apiState = function(href,callback){
			$.get(href,{get:'state',timestamp:+new Date},function(d){
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
	$.fn.serializeObject = function(){
		var o = {},
			a = this.serializeArray();
		$.each(a,function(){
			if(o[this.name] !== undefined){
				if(!o[this.name].push){
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			}else{
				o[this.name] = this.value || '';
			}
		});
		return o;
	};
})(jQuery,History);