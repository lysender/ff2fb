$(function(){
	$(".reg-list").tablesorter({
		widgets: ['zebra'],
		headers: {
			1: {sorter: false},
			2: {sorter: false},
			3: {sorter: false}
		}
	});
	
	$(".delete a").click(function(){
		if (confirm("Are you sure you want to delete this role?"))
		{
			return true;
		}
		
		return false;
	});
});