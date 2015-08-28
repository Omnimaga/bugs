ready(function(){
	var log = dom.get('#log'),
		form = dom.get('form.hidden'),
		running = false;
	form.on('submit',function(e){
			if(!running){
				running = true;
				log.drop('*')
					.append(
						dom.create('li')
							.append('Running...')
					)
					.css({
						display: 'block'
					});;
				form.children
					.attr({
						disabled: 'disabled'
					});
				fetch('.',{
					method: 'post',
					body: new FormData(this),
					mode: 'cors',
					credentials: 'include'
				})
				.then(function(res){
					return res.json();
				})
				.then(function(data){
					if(data){
						for(var i in data){
							var ul = dom.create('ul'),
								step = dom.create('li').append('Step: '+i).append(ul);
							for(var ii in data[i]){
								var d = data[i][ii];
								ul.append(
									dom.create('li')
										.append(
											ii+': '+(d[0]?'<span style="color:green">PASS</span>':'<span style="color:red">FAIL</span> - '+d[1])
										)
								);
							}
							log.append(step);
						}
						log.append("<li>Don't forget to remove the install directory when you are finished</li>");
					}else{
						log.append("<li>Database Information incorrect</li>");
					}
					form.children
						.attr({
							disabled: ''
						});
					form.get('[name=uninstall]').value = '';
					running = false;
				})
				.catch(function(e){
					log.append(
						dom.create('li')
							.append('Error: '+e)
					);
					form.children
						.attr({
							disabled: ''
						});
					form.get('[name=uninstall]').value = '';
					running = false;
				});
			}
			e.stopPropagation();
			if(e.cancelable){
				e.preventDefault();
			}
			return false;
		})
		.css({
			display: 'block'
		});
	dom.get('#uninstall')
		.on('click',function(e){
			form.get('[name=uninstall]').value = 'Y';
		});
});