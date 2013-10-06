(function($,History){
	var State = History.getState();
	$(document).ready(function(){
		if($.isEmptyObject(State.data)){
			History.replaceState({
				type: 'install',
				id: 'config'
			},'Bugs');
		}
		$(window).on('statechange',function(){
			State = History.getState();
			$.get('api.php',State.data,function(d){
				$('body').html(d);
				$('#config').submit(function(){
					$('#install').attr('disabled','disabled');
					$.get('api.php?'+$("#config").serialize()+'&dbtemplate=install&type=install&id=run',function(d){
						if(d != "pass"){
							alert(d);
							$('#install').removeAttr('disabled');
						}else{
							alert('Installation successful!');
							location = '..';
						}
					},'text');
					return false;
				});
			},'html');
		}).trigger('statechange');
	});
})(jQuery,History);