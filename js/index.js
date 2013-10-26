// TODO - Add initial page loading and handlers
(function($,History,console){
	var State = History.getState(),
		Old = {},
		Key = null,
		flags = [],
		templates = [],
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
				d.setTime(d.getTime()+get('expire'));
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
		template = window.template = function(name,template){
			var d = +new Date,
				id = (function(name){
					for(var i in templates){
						if(templates[i].name == name){
							return i;
						}
					}
					return false;
				})(name);
			if(exists(template)){
				if(template === null){
					if(id!==false){
						templates.splice(id,1);
					}
					$.localStorage('templates',templates);
					console.log('Dropping template for: '+name);
					return '';
				}else{
					var o = {
						name: name,
						template: template,
						date: get('expire')+d
					}
					if(id===false){
						console.log('Storing new template for: '+name);
						templates.push(o);
					}else{
						console.log('Replacing old template for: '+name);
						templates[id] = o;
					}
					$.localStorage('templates',templates);
				}
			}else if(id!==false){
				console.log('Using cached template for: '+name);
				var template = templates[id].template;
				if(templates[id].date < d){
					delete templates[name];
					$.localStorage('templates',templates);
				}
				return template;
			}else{
				console.log('No cached template stored for: '+name);
				return '';
			}
		},
		apiCall = window.apiCall = function(data,callback){
			console.log('apiCall('+data.type+'-'+data.id+')');
			if(!flag('error')){
			loading(true);
			data.get = 'api';
				data.back = State.data.back;
				data.timestamp = +new Date;
				if(''!=template(data.type+'-'+data.id)){
					data.template = false;
				}
				$.get(location.href,data,function(d){
					if(exists(d['error'])){
						error(d);
					}else{
						if(location.href.substr(location.href.lastIndexOf('/')+1) != d.state.url){
							console.log('Forced redirection to '+d.state.url);
							History.replaceState(d.state.data,d.state.title,d.state.url);
							getNewState();
						}
					}
					if(exists(callback)){
						console.log('Running apiCall callback');
						callback(d);
					}
				},'json');
			}
		},
		loadState = window.loadState = function(href,callback){
			console.log('loadState('+href+')');
			if(!flag('error')){
				loading(true);
				var data = {
					get:'state',
					timestamp: +new Date,
					back: location.href
				};
				ajax = $.ajax(href,{
						data: data,
						async: true,
						type: 'GET',
						success: function(d){
							if(exists(d['error'])){
								error(d);
							}else{
								console.log('pushState: '+d.state.title+'['+href+']');
								History.pushState(d.state.data,d.state.title,href);
								getNewState();
							}
							if(exists(callback)){
								callback(d);
							}
							if(!exists(d['error'])){
								flag('handled',true);
								stateChange();
								flag('handled',false);
							}
						},
						error: function(x,t,e){
							error({
								error: '['+t+'] '+e
							});
							if(exists(callback)){
								callback({
									error: '['+t+'] '+e
								});
							}
						},
						dataType: 'json'
				});
			}
		},
		apiState = window.apiState = function(href,callback){
			console.log('apiState('+href+')');
			if(!flag('error')){
				loading(true);
				var data = {
					get:'state',
					timestamp: +new Date,
					back: State.data.back
				};
				$.ajax(href,{
						data: data,
						async: true,
						type: 'GET',
						success: function(d){
							if(exists(d['error'])){
								error(d);
							}else{
								console.log('pushState: '+d.state.title+'['+href+']');
								History.replaceState(d.state.data,d.state.title,href);
								getNewState();
							}
							if(exists(callback)){
								callback(d);
							}
							console.log(d.state.title);
							if(!exists(d['error'])){
								flag('handled',true);
								stateChange();
								flag('handled',false);
							}
						},
						error: function(x,t,e){
							error({
								error: '['+t+'] '+e
							});
							if(exists(callback)){
								callback({
									error: '['+t+'] '+e
								});
							}
						},
						dataType: 'json'
				});
			}
		},
		error = function(e,callback){
			if(!flag('error')){
				flag('error',true);
				var msg = '['+State.url+']'+e.error;
				console.error(msg.trim()+"\n"+(exists(e.state)?JSON.stringify(e.state):''));
				alert(msg.trim(),'Error',callback);
			}
		},
		getNewState = function(){
			State = History.getState();
			console.log("State change: ["+State.title+"] "+JSON.stringify(State.data));
			if(!window.location.origin){
				window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
			}
			document.title = State.title;
		},
		stateChange = function(){
			getNewState();
			if(!equal(State.data,Old)){
				switch(State.data.type){
					case 'page':case 'user':case 'project':
						apiCall(State.data,function(d){
							if(!exists(d.error)){
								if(exists(d.context)){
									if(!exists(d.context.key)&&Key!==null){
										console.log('Context detected console.logout');
										setKey(null);
									}
									if(exists(d.template)){
										template(State.data.type+'-'+State.data.id,d.template);
									}else{
										d.template = template(State.data.type+'-'+State.data.id);
									}
									render.topbar(d.topbar.template,d.topbar.context);
									render.content(d.template,d.context);
									$(window).resize();
									loading(false);
								}else{
									console.error('No context given');
									back();
								}
							}else{
								error(d);
								back();
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
				loading(false);
			}
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
		render = window.render = {
			topbar: function(t,c){
				$('#topbar').html(Handlebars.compile(t)(c));
				render.links('#topbar');
				render.buttons('#topbar');
				render.menus('#topbar');
				if(State.url == location.origin+'/page-index'){
					$('#topbar').find('.topbar-history').hide();
				}
				$('#topbar').addClass('overflow-hide');
				$(window).resize();
			},
			content: function(t,c){
				$('#content').html(
					Handlebars.compile(t)(c)
				);
				render.links('#content');
				render.buttons('#content');
				render.accordions('#content');
				render.menus('#content');
				render.form('#content');
				$(window).resize();
			},
			accordions: function(selector){
				$(selector).find('.accordion').each(function(){
					var icons = {};
					if($(this).children('.icons').length == 1){
						icons = JSON.parse($(this).children('.icons').text());
					}
					$(this).children('.icons').remove();
					$(this).accordion({
						collapsible: true,
						icons: icons,
						active: false
					});
				});
			},
			buttons: function(selector){
				$(selector).find('.button').button();
				$(selector).find('input[type=submit]').button();
				$(selector).find('input[type=button]').button();
				$(selector).find('button').button();
			},
			menus: function(selector){
				$(selector).find('.menu').css({
					'list-style':'none'
				}).menu({
					icons:{
						submenu: "ui-icon-circle-triangle-e"
					}
				}).removeClass('ui-corner-all').addClass('ui-corner-bottom').parent().click(function(e){
					e.stopPropagation();
				});
			},
			form: function(selector){
				$(selector).find('#form').position({of:selector,my:'center',at:'center'});
			},
			dialog: function(selector,title){
				$(selector).dialog({
					close: function(){
						flag('error',false);
						var fn = $(this).data('callback');
						if(exists(fn)){
							fn();
						}
						loading(false);
					},
					resizable: false,
					draggable: false,
					title: title,
					buttons: [
						{
							text: 'Ok',
							class: 'recommend-force',
							click: function(){
								$(this).dialog('close');
							}
						}
					]
				});
			},
			links: function(selector){
				$(selector).find('a').each(function(){
					var href = this.href;
					if(href.indexOf('#')!=-1&&(href.indexOf(location.origin)!=-1||href.indexOf('#')==0)){
						href = href.substr(href.indexOf('#')+1);
						$(this).click(function(e){
							try{
								if(($(this).hasClass('topbar-home') || $(this).hasClass('topbar-back'))&&$(window).width()<767){
									$('#topbar').children('div.topbar-right,div.topbar-left').toggle();
									$('#topbar').toggleClass('overflow-hide');
									$(window).resize();
								}else if($(this).hasClass('topbar-history')){
									back();
								}else{
									loadState(href);
								}
							}catch(error){
								console.error(error);
							}
							e.preventDefault();
							return false;
						});
					}
				});
			}
		},
		back = window.back = function(){
			if(exists(State.data.back)){
				if(!History.back()){
					loadState(State.data.back);
				}
			}else if(State.data.type != 'page' || State.data.id != 'index'){
				loadState('page-index');
			}else{
				location.reload();
			}
		},
		alert = function(text,title,callback){
			$('#dialog').text(text).data('callback',callback);
			render.dialog('#dialog',title,callback);
		},
		loading = function(state){
			state = exists(state)?state:false;
			console.log('loading state '+state);
			if(!flag('error') && !state){
				$('#loading').hide();
			}else if(state){
				$('#loading').show();
			}
		};
	if(exists($.cookie('key'))){
		setKey($.cookie('key'));
	}else{
		setKey(null);
	}
	$(document).ready(function(){
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
			async: false,
			cache: false,
			timeout: 2000
		});
		 $(document).ajaxError(function(event, request, settings) {
			error({error:'Request timed out'});
		});
		templates = $.localStorage('templates');
		if(templates === null){
			templates = [];
		}
		if(!exists($.support.touch)){
			$.support.touch = 'ontouchstart' in window || 'onmsgesturechange' in window;
		}
		$('#content').niceScroll({
			cursorwidth: 10,
			nativeparentscrolling: false,
			preservenativescrolling: false
		});
		$('#content,#topbar').click(function(){
			$('.menu').hide();
		});
		document.addEventListener('touchmove',function(e){
			e.preventDefault();
		});
		$(window).resize(function(){
			if($(window).width()>767){
				$('#topbar div.topbar-right, #topbar div.topbar-left').css({
					'display': ''
				});
			}
			$('#content').height($('body').height()-$('#topbar').height());
			$('#content').getNiceScroll().resize();
			render.form('#content');
		});
		var data = {
			get: 'settings',
			timestamp: +new Date,
			back: false
		};
		$.get(location.href,data,function(d){
			settings = d;
			apiState(location.href);
		},'json');
		$(window).on('statechange',function(){
			if(!flag('handled')){
				console.log('unhandled state change event');
				stateChange();
			}
		});
		var getState = History.getState;
		History.getState = function(flag){
			if(exists(flag)){
				return State;
			}else{
				return getState.call(History);
			}
		};
	});
	shortcut.add('f12',function(){
		if(!flag('firebug-lite')){
			$('head').append(
				$('<script>').attr({
					'type': 'text/javascript',
					'src': 'https://getfirebug.com/firebug-lite.js#startOpened',
					'id': 'FirebugLite'
				})
			);
			$('<image>').attr('src','https://getfirebug.com/releases/lite/latest/skin/xp/sprite.png');
			flag('firebug-lite',true);
		}
	});
	shortcut.add('Ctrl+f12',function(){
		if(!flag('manifesto')){
			if(window.applicationCache){
				if(window.applicationCache.status==window.applicationCache.UNCACHED){
					$('head').append(
						$('<script>').attr({
							'type': 'text/javascript',
							'src': 'http://manifesto.ericdelabar.com/manifesto.js?x="+(Math.random())'
						})
					);
					(function wait(){
						if($('#cacheStatus').length == 0){
							setTimeout(wait,10);
						}else{
							$('#cacheStatus').niceScroll();
						}
					})();
				}else{
					alert("Manifest file is valid.");
				}
			}else{
				alert("This browser does not support HTML5 Offline Application Cache.");
			}
			flag('manifesto',true);
		}
	});
	shortcut.add('Shift+f12',function(){
		templates = [];
		$.localStorage('templates',null);
		console.log('Templates cleared.');
	});
})(jQuery,History,console);