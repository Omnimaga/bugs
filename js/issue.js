ready(function(){
	dom.get('#form-issue')
		.on('submit',function(e){
			var form = this,
				id = dom.get(form).get('[name=id]').value;
			global.settings.fetch.native = true;
			fetch(id===null?'./issue/complete':BASE_URL+'/issue/'+id+'/update',{
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
					if(data.error.message){
						alert(data.error.message);
					}else{
						alert(data.error);
					}
				}else{
					form.reset();
					location.assign(BASE_URL+'/!'+data.id);
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