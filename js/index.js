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
		template = window.template = function(type,name,template){
			var d = +new Date,
				id = (function(type,name){
					for(var i in templates){
						if(templates[i].name == name && templates[i].type == type){
							return i;
						}
					}
					return false;
				})(type,name);
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
						type: type,
						date: Number(get('expire'))+Number(d)
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
				console.log('No cached template stored for: '+type+':'+name);
				return '';
			}
		},
		apiCall = window.apiCall = function(data,callback,background){
			console.log('apiCall('+data.type+'-'+data.id+')');
			if(!flag('error')){
				if(exists(background)&&!background){
					loading(true);
				}
				data.get = 'api';
				data.back = State.data.back;
				data.timestamp = +new Date;
				$.get(location.href,data,function(d){
					if(exists(d['error'])){
						error(d);
					}else{
						if(exists(d.state)){
							d.state.title = d.state.title.capitalize();
							if(location.href.substr(location.href.lastIndexOf('/')+1) != d.state.url && d.state.url !== ''){
								console.log('Forced redirection to '+d.state.url);
								History.replaceState(d.state.data,d.state.title,d.state.url);
								getNewState();
							}
							document.title = d.state.title;
						}
					}
					if(exists(callback)){
						console.log('Running apiCall callback');
						try{
							callback(d);
						}catch(e){
							error(e);
						}
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
								d.state.title = d.state.title.capitalize();
								d.state.url = href;
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
		replaceState = window.replaceState = function(href,callback){
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
								d.state.title = d.state.title.capitalize();
								d.state.url = href;
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
		error = window.error = function(e,callback){
			if(!flag('error')){
				flag('error',true);
				var msg = '['+State.url+']'+e.error;
				console.error((msg.trim()+"\n"+(exists(e.state)?JSON.stringify(e.state):'')).trim());
				alert(msg.trim(),'Error',callback);
				console.trace();
			}
		},
		getNewState = function(state){
			State = History.getState();
			console.log("State change: ["+State.title+"] "+JSON.stringify(State.data));
			if(!window.location.origin){
				window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
			}
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
									render.topbar(d.topbar.template,d.topbar.context);
									if(exists(d.template)){
										console.log('Using template: '+d.template.type+':'+d.template.name);
										d.template = template(d.template.type,d.template.name);
										render.content(d.template,d.context);
									}else{
										console.log('No template used');
									}
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
				render.time('#topbar');
				if(State.url == location.origin+'/page-index'){
					$('#topbar').find('.topbar-history').hide();
				}
				$('#topbar').addClass('overflow-hide');
				$(window).resize();
			},
			content: function(t,c){
				$(document).unbind('ready');
				$('#content').html(
					Handlebars.compile(t)(c)
				);
				render.links('#content');
				render.buttons('#content');
				render.accordions('#content');
				render.menus('#content');
				render.form('#content');
				render.time('#content');
				$(window).resize();
			},
			time: function(selector){
				$(selector).find('time.timeago').each(function(){
					var time = new Date($(this).text()*1000);
					$(this).replaceWith(
						$('<abbr>').attr({
							'title': time.toISOString(),
							'style': $(this).attr('style')
						}).addClass($(this).attr('class')).timeago()
					);
				});
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
				render.comment.buttons(selector);
			},
			comment: {
				buttons: function(selector){
					$(selector).find('.comment').each(function(){
						var context = JSON.parse($(this).text());
						$(this).text(context.text);
						$(this).button();
						$(this).click(function(){
							render.comment.dialog(context.id,context.type,context.title);
						});
					});
				},
				dialog: function(id,type,title){
					loading(true);
					flag('ignore_statechange',true);
					$('#comment').find('form').find('input[name=comment_type]').val(type);
					$('#comment').find('form').find('input[name=comment_id]').val(id);
					$('#comment').find('form').find('textarea[name=message]').val('');
					$('#comment').dialog({
						close: function(){
							$('#comment').find('form').find('input[name=comment_type]').val('');
							$('#comment').find('form').find('input[name=comment_id]').val('');
						},
						resizable: false,
						draggable: false,
						title: title,
						buttons: [
							{
								text: 'Ok',
								class: 'recommend-force',
								click: function(){
									var diag = $(this),
										context = diag.find('form').serializeObject();
									if(context.message !== ''){
										context.type = 'action';
										context.id = 'comment';
										context.url = State.url;
										context.title = State.title;
										apiCall(context,function(d){
											if(!exists(d.error)){
												diag.dialog('close');
												flag('ignore_statechange',false);
												$('.topbar-current').click();
											}
										});
									}
								}
							},{
								text: 'Cancel',
								class: 'cancel-force',
								click: function(){
									$(this).dialog('close');
									flag('ignore_statechange',false);
									loading(false);
								}
							}
						]
					});
				},
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
				$(selector).find('#form').width('320px').children();
				render.inputs(selector);
			},
			inputs: function(selector){
				$(selector).find('input[type=text],input[type=password]').each(function(){
					var input = $(this),
						height = input.height()>=17?17:input.height();
					input.siblings('.input-clear').remove();
					input.off('focus').off('blur').after(
						$('<div>').css({
							position: 'absolute',
							right: $(window).width() - (input.outerWidth() + input.position().left)+2,
							top: input.position().top+2,
							'background-image': 'url(img/headers/icons/clear.png)',
							'background-position': 'center',
							'background-size': height+'px '+height+'px',
							'background-repeat': 'no-repeat',
							width: input.height(),
							height: input.height(),
							cursor: 'pointer'
						}).hide().addClass('input-clear').mousedown(function(){
							input.val('');
						})
					);
					input.focus(function(){
						input.next().show();
					}).blur(function(e){
						input.next().hide();
					});
				});
				$(selector).find('input[type=text],input[type=password],textarea').each(function(){
					var input = $(this);
					if(input.hasClass('fill-width')){
						input.css('width','calc(100% - '+(input.outerWidth()-input.width())+'px)');
					}
				});
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
								}else if($(this).hasClass('topbar-current')){
									replaceState(href);
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
		back = window.back = function(reload){
			console.log('reload',exists(reload));
			var go = function(url){
				if(exists(reload)){
					console.log('FORCING RELOAD');
					location = url;
				}else{
					console.log('FORCING LOADSTATE');
					loadState(url);
				}
			}
			if(exists(State.data.back)){
				if(!History.back()){
					loadState(State.data.back);
				}
			}else if(State.data.type != 'page' || State.data.id != 'index'){
				go('page-index');
			}else{
				location.reload();
			}
		},
		alert = function(text,title,callback){
			if(exists(text)){
				title=exists(title)?title:'';
				callback=exists(callback)?callback:function(){};
				$('#dialog').text(text).data('callback',callback);
				render.dialog('#dialog',title,callback);
			}
		},
		hasFocus = function(){
			if(typeof document.hasFocus === 'undefined'){
				document.hasFocus = function(){
					return document.visibilityState == 'visible';
				}
			}
			return document.hasFocus();
		},
		notify = window.notify = function(title,text,onclick,onclose){
			var notification;
			if(exists(window.Notification)&&!exists(window.webkitNotifications)&&!flag('default_notify')&&!hasFocus()){
				if(Notification.permission === 'denied'){
					flag('default_notify',true);
					notify(title,text,onclick,onclose);
				}else if(Notification.permission === 'granted'){
					notification = new Notification(title,{
						body: text,
						icon: location.origin+'/img/favicon.ico'
					});
					notification.onclick = onclick;
					notification.onclose = onclose;
				}else{
					Notification.requestPermission(function(p){
						console.log('permission for notify: '+p);
						Notification.permission = p;
						notify(title,text,onclick,onclose);
					});
				}
			}else if(exists(window.navigator.mozNotification)&&!hasFocus()){
				notification = window.navigator.mozNotification.createNotification(title,text,location.origin+'/img/favicon.ico');
				notification.onclick = onclick;
				notification.onclose = onclose;
				notification.show();
			}else{
				$('#notification-container').notify('create',{
					title: title,
					text: text,
					click: onclick,
					close: onclose
				});
			}
		},
		loading = function(state){
			if(!flag('ignore_statechange')){
				state = exists(state)?state:false;
				console.log('loading state '+state);
				if(!flag('error') && !state){
					$('#loading').hide();
				}else if(state){
					$('#loading').show();
				}
			}
		},
		debug = window.debug = {
			hardReload: function(){
				debug.clearCache();
				location.reload();
			},
			clearCache: function(){
				templates = [];
				$.localStorage('templates',null);
				console.log('Templates cleared.');
			},
			manifesto: function(){
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
								if($('#cacheStatus').length === 0){
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
			},
			firebug: function(){
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
			}
		},
		getTemplates = function(callback){
			$.get('api.php',{
					type: 'manifest',
					id: 'pages'
				},function(d){
				if(!exists(d.error)){
					var count = d.manifest.length,
						m = +new Date;
					for(var i in d.manifest){
						console.log('Loading template('+(Number(i)+1)+'/'+d.manifest.length+'): '+d.manifest[i]);
						if(template('pages',d.manifest[i]) === ''){
							$.get('api.php',{
								type: 'template',
								id: 'pages',
								name: d.manifest[i]
							},function(d){
								templates.push({
									name: d.name,
									template: d.template,
									type: d.type,
									date: Number(get('expire'))+Number(m)
								});
								$.localStorage('templates',templates);
								count--;
								console.log('Loaded template('+count+' left): '+d.name);
							},'json');
						}else{
							count--;
						}
					}
					setTimeout(function wait_for_templates(){
						if(count === 0){
							console.log('getTemplates callback');
							callback();
						}else{
							setTimeout(wait_for_templates,10);
						}
					},10);
				}else{
					error(d.error);
				}
			},'json');
		};
	if(exists($.cookie('key'))){
		setKey($.cookie('key'));
	}else{
		setKey(null);
	}
	$(document).ready(function(){
		if(exists(typeof Notification.permission)&&Notification.permission !== 'granted'){
			Notification.requestPermission();
		}
		$.ajaxSetup({
			async: false,
			cache: false,
			timeout: 30000 // 30 seconds
		});
		$(document).ajaxError(function(event, request, settings) {
			error({error:'Request timed out'});
		});
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
			render.inputs('#content');
			render.inputs('#topbar');
		});
		$.get(location.href,{
			get: 'settings',
			timestamp: +new Date,
			back: false,
			no_state: true
		},function(d){
			if(!exists(d.error)){
				settings = d.settings;
				if(d.version != $.localStorage('version')){
					$.localStorage('version',d.version);
					$.localStorage('templates',null);
					templates = [];
				}else{
					templates = $.localStorage('templates');
					if(templates === null){
						templates = [];
					}
				}
				getTemplates(function(){
					replaceState(location.href);
					(function notifications(){
						var context = State;
						context.type = 'action';
						context.id = 'notifications';
						context.url = State.url;
						context.title = State.title;
						context.topbar = false;
						context.no_state = true;
						apiCall(context,function(d){
							if(!exists(d.error)){
								if(d.count>0 && $.localStorage('last_pm_check') < d.timestamp){
									notify('Alert','You have '+d.count+' new message'+(d.count>1?'s':''),function(){
										loadState('page-messages');
									});
								}
								$('.topbar-notifications').css('display',d.count>0?'block':'').text('('+d.count+')');
								$.localStorage('last_pm_check',d.timestamp);
							}
							setTimeout(notifications,5*1000); // every 5 seconds
						},true);
					})();
				});
			}else{
				error(d.error);
			}
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
		$('#notification-container').notify();
	});
	shortcut.add('f12',function(){
		debug.firebug();
	});
	shortcut.add('Ctrl+f12',function(){
		debug.manifesto();
	});
	shortcut.add('Shift+f12',function(){
		debug.clearCache();
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
	String.prototype.capitalize = function(lower) {
		return (lower?this.toLowerCase():this).replace(/(?:^|\s)\S/g, function(a){
			return a.toUpperCase();
		});
	};
})(jQuery,History,console);