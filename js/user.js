ready(function(){
	dom.get('#form-user')
		.on('submit',function(e){
			var form = this,
				id = dom.get(form).get('[name=id]').value;
			global.settings.fetch.native = true;
			fetch('./user/'+id+'/update',{
				method: 'post',
				body: new FormData(form),
				mode: 'cors',
				credentials: 'include'
			})
			.then(function(res){
				return res.json();
			})
			.then(function(data){
				if(data.error){
					alert(data.error);
				}else{
					form.reset();
					location.assign('~'+data.name);
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