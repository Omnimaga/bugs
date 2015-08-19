ready(function(){
	dom.get('body>div.error>div')
		.on('click',function(){
			dom.get(this)
				.get('.collapsable, .collapse-arrow')
				.each(function(){
					if(this.classList.indexOf('collapsed') == -1){
						this.classList.add('collapsed');
					}else{
						this.classList.remove('collapsed');
					}
				});
		})
		.each(function(i){
			if(i%2 == 1){
				this.classList.add('odd');
			}
		});
});