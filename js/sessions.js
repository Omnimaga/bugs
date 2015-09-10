ready(function(){
	dom.get('button.session-delete')
		.on('click',function(e){
			fetch('./sessions/remove/'+this.name,{
				mode: 'cors',
				credentials: 'include'
			})
			.then(function(res){
				return res.json();
			})
			.then(function(data){
				if(data.error){
					if(data.error.message){
						alert(data.error.message);
					}else{
						alert(data.error);
					}
				}else{
					location.reload();
				}
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