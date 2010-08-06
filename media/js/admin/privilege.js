$(function(){
	$(".reg-list").tablesorter({
		widgets: ['zebra'],
		headers: {
			3: {sorter: false},
			5: {sorter: false}
		}
	});
	
	$(".delete a").click(function(){
		if (confirm("Are you sure you want to delete this privilege?"))
		{
			return true;
		}
		
		return false;
	});
});