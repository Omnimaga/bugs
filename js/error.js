ready(function(){
	dom.get('body>div')
		.css({
			margin: 0,
			padding: '5px'
		})
		.each(function(i){
			if(i%2 == 1){
				dom.get(this)
					.css({
						backgroundColor: 'lightgray'
					});
			}
		});
	dom.get('body>div ul')
		.css({
			margin: 0
		});
});