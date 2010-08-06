$(function(){
	$(".reg-list").tablesorter({
		widgets: ['zebra'],
		headers: {
			2: {sorter: false}
		}	
	});
});