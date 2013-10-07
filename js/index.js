// TODO - Add initial page loading and handlers
(function($,History){
	var State = History.getState();
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
						$('body').html(Handlebars.compile(d.template)(d.context));
					},'json');
				break;
				default:
					alert("Something went wrong!\nYour current state:\n"+JSON.stringify(State));
			}
		}).trigger('statechange');
	});
})(jQuery,History);