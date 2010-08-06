$(function(){
	$(".reg-list").tablesorter({
		widgets: ['zebra'],
		headers: {
			1: {sorter: false}
		}
	});
	
	$(".delete a").click(function(){
		if (confirm("Are you sure you want to delete this default role?"))
		{
			return true;
		}
		
		return false;
	});
});