(function($,History){
	var State = History.getState(),
		returnToIndex = function(){
			History.replaceState({},'Bugs','..');
			location = '..';
		};
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
							if(confirm("Installation successful!\nDo you want to delete the installation files?")){
								$.get('api.php?&dbtemplate=install&type=install&id=cleanup',function(d){
									returnToIndex();
								});
							}else{
								returnToIndex();
							}
						}
					},'text');
					return false;
				});
			},'html');
		}).trigger('statechange');
	});
})(jQuery,History);