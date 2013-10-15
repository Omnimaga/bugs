// TODO - Add initial page loading and handlers
(function($,History){
	var State = History.getState(),
		Old = {},
		Key = null,
		flags = [],
		flag = window.flag = function(name,value){
			if(exists(value)){
				flags[name] = value;
			}else{
				return exists(flags[name])?flags[name]:false;
			}
		},
		settings = {},
		exists = function(v){
			return typeof v != 'undefined';
		},
		get = window.get = function(s){
			return settings[s];
		},
		set = window.set = function(s,v){
			settings[s] = v;
			return v;
		},
		setKey = window.setKey = function(key){
			if(key !== null){
				console.log('Key change to '+key);
				Key = key;
				var d = new Date();
				d.setTime(d.getTime()+get('timeout'));
				$.cookie('key',key,{
					expires: d
				});
			}else{
				console.log('Key deleted');
				Key = null;
				$.removeCookie('key');
			}
		},
		getKey = window.getKey = function(){
			return Key;
		},
		apiCall = window.apiCall = function(data,callback){
			console.log('apiCall('+data.type+'-'+data.id+')');
			$('#loading').show();
			data.get = 'api';
			data.timestamp = +new Date;
			$.get(location.href,data,function(d){
				if(exists(d['error'])){
					error(d);
				}else{
					if(location.href.substr(location.href.lastIndexOf('/')+1) != d.state.url){
						console.log('Forced redirection to '+d.state.url);
						History.replaceState(d.state.data,d.state.title,d.state.url);
					}
				}
				if(exists(callback)){
					callback(d);
				}
			},'json');
		},
		loadState = window.loadState = function(href,callback){
			console.log('loadState('+href+')');
			$('#loading').show();
			var data = {
				get:'state',
				timestamp: +new Date
			};
			$.get(href,data,function(d){
				if(exists(d['error'])){
					error(d);
				}else{
					History.pushState(d.state.data,d.state.title,href);
					getNewState();
				}
				if(exists(callback)){
					callback(d);
				}
			},'json');
		},
		apiState = window.apiState = function(href,callback){
			console.log('apiState('+href+')');
			$('#loading').show();
			var data = {
				get:'state',
				timestamp: +new Date
			};
			$.get(href,data,function(d){
				if(exists(d['error'])){
					error(d);
				}else{
					History.replaceState(d.state.data,d.state.title,href);
					getNewState();
				}
				if(exists(callback)){
					callback(d);
				}
			},'json');
		},
		error = function(e){
			e = '['+State.url+']'+e.error;
			console.error(e+"\n"+(exists(e.state)?JSON.stringify(e.state):''));
			alert(e);
		},
		getNewState = function(){
			State = History.getState();
			console.log("State change. "+JSON.stringify(State.data));
		},
		equal = function(o1,o2){
			for(var i in o1){
				if(!exists(o2[i])||o2[i]!=o1[i]){
					return false;
				}
			}
			for(i in o2){
				if(!exists(o1[i])||o2[i]!=o1[i]){
					return false;
				}
			}
			return true;
		},
		render = {
			topbar: function(t,c){
				$('#topbar').html(Handlebars.compile(t)(c));
				render.links('#topbar');
			},
			content: function(t,c){
				$('#content').html(Handlebars.compile(t)(c)).mCustomScrollbar('destroy');
				$('#content,.scroll').mCustomScrollbar({
					theme: 'dark-2',
					scrollInertia: 0
				});
				render.links('#content');
			},
			links: function(selector){
				$(selector).find('a').each(function(){
					var href = this.href;
					if(href.indexOf(location.origin) != -1 && href.indexOf('#') != -1){
						href = href.substr(href.indexOf('#')+1);
						$(this).click(function(){
							if(($(this).hasClass('topbar-home') || $(this).hasClass('topbar-back'))&&$(window).width()<767){
								$('#topbar').children('div.topbar-right,div.topbar-left').toggle();
							}else{
								loadState(href);
							}
							return false;
						});
					}
				});
			}
		};
	if(exists($.cookie('key'))){
		setKey($.cookie('key'));
	}else{
		setKey(null);
	}
	$(document).ready(function(){
		$(window).on('statechange',function(){
			getNewState();
			if(!equal(State.data,Old)){
				document.title = State.title;
				switch(State.data.type){
					case 'page':case 'user':
						apiCall(State.data,function(d){
							if(exists(d.context)){
								if(!exists(d.context.key)&&Key!==null){
									console.log('Context detected logout');
									setKey(null);
								}
								render.topbar(d.topbar.template,d.topbar.context);
								render.content(d.template,d.context);
								$('#loading').hide();
							}else{
								console.error('No context given');
								History.back();
							}
						});
					break;
					case 'action':break;
					default:
						error({
							url: State.url,
							error: "Something went wrong!"
						});
				}
				Old = State.data;
			}else{
				console.log(State.data,Old);
				console.warn('Stopped double load of '+Old.type+'-'+Old.id);
				$('#loading').hide();
			}
		});
		if($.isEmptyObject(State.data)){
			History.replaceState({
				type: 'page',
				id: 'index'
			},'Bugs','page-index');
			console.log('Forcing default state.');
		}else{
			flag('load',true);
		}
		var data = {
			get: 'settings',
			timestamp: +new Date
		};
		$.get(location.href,data,function(d){
			settings = d;
			apiState(location.href,function(){
				if(flag('load')){
					State.data = {
						type: '',
						data: ''
					};
				}
				flag('load',false);
			});
		},'json');
	});
	$(window).resize(function(){
		if($(window).width()>767){
			$('#topbar div.topbar-right, #topbar div.topbar-left').css({
				'display': ''
			});
		}
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
	$.ajaxSetup({
		async: false
	});
})(jQuery,History);