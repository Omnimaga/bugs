// TODO - Add initial page loading and handlers
(function($,History){
	var State = History.getState();
	$(document).ready(function(){
		if($.isEmptyObject(State.data)){
			History.pushState({
				type: 'template',
				id: 'index'
			},'Bugs','page/index');
		}
		$(window).on('statechange',function(){
			State = History.getState();
			console.log(State);
		}).trigger('statechange');
	});
})(jQuery,History);