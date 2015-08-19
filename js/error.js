ready(function(){
	var divs = dom.get('div.error,div.error>div.collapsable>div');
	dom.get('div.error>div.collapsable>div')
		.each(function(i){
			if(i%2 == 1){
				this.classList.add('odd');
			}
		});
	divs.on('click',function(e){
		var self = this;
		dom.get(self)
			.get('div.collapsable, span.collapse-arrow')
			.each(function(){
				if(self === this.parentNode){
					if(this.classList.indexOf('collapsed') == -1){
						this.classList.add('collapsed');
					}else{
						this.classList.remove('collapsed');
					}
				}
			});
		e.stopPropagation();
		e.preventDefault();
		return false;
	});
});