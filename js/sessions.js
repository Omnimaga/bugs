ready(function(){
	dom.get('button.session-delete')
		.on('click',function(e){
			fetch('./sessions/remove/'+this.name)
			.then(function(data){
				location.reload();
			})
			.catch(function(e){
				alert(e);
			});
			e.stopPropagation();
			if(e.cancelable){
				e.preventDefault();
			}
			return false;
		});
});