// TODO - Add initial page loading and handlers
(function($,History){
	var State = History.getState(),
		Old = {},
		Key = null,
		flag = false,
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
			$('#loading').show();
			data.get = 'api';
			data.timestamp = +new Date;
			$.get(location.href,data,function(d){
				if(exists(d['error'])){
					error(d);
				}else{
					if(location.href.substr(location.href.lastIndexOf('/')+1) != d.state.url){
						History.pushState(d.state.data,d.state.title,d.state.url);
					}
				}
				if(exists(callback)){
					callback(d);
				}
			},'json');
		},
		loadState = window.loadState = function(href,callback){
			$('#loading').show();
			var data = {
				get:'state',
				timestamp: +new Date
			};
			$.get(href,data,function(d){
				if(exists(d['error'])){
					error(d);
				}else{
					History.pushState(d.state.data,document.title,href);
					getNewState();
				}
				if(exists(callback)){
					callback(d);
				}
			},'json');
		},
		apiState = window.apiState = function(href,callback){
			$('#loading').show();
			var data = {
				get:'state',
				timestamp: +new Date
			};
			$.get(href,data,function(d){
				if(exists(d['error'])){
					error(d);
				}else{
					History.replaceState(d.state.data,document.title,href);
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
				switch(State.data.type){
					case 'template':case 'user':
						apiCall(State.data,function(d){
							if(!exists(d.context.key)&&Key!==null){
								console.log('Context detected logout');
								setKey(null);
							}
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
							$('#loading').hide();
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
				type: 'template',
				id: 'index'
			},'Bugs','page-index');
			console.log('Forcing default state.');
		}else{
			flag = true;
		}
		var data = {
			get: 'settings',
			timestamp: +new Date
		};
		$.get(location.href,data,function(d){
			settings = d;
			apiState(location.href,function(){
				if(flag){
					State.data = {
						type: '',
						data: ''
					};
				}
				$(window).trigger('statechange');
			});
		},'json');
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