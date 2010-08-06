$(function(){
	$("a.play-trigger").click(function(){
		// get the id
		var id = this.id + "";
		// get the feed id
		var chunks = id.split("_");
		var feed_id = chunks[2];
		var player_id = "player_" + feed_id;
		
		if ($(this).hasClass("playing"))
		{
			$("#" + player_id).hide();
			$("#play_alt_" + feed_id).removeClass("playing").attr("title", "Play video");
			$("#play_main_" + feed_id).removeClass("playing").attr("title", "Play video");
			$("#play_main_" + feed_id + " img").attr("src", base_url + "media/img/playbutton.png");
		}
		else
		{
			$("#" + player_id).show();
			$("#play_alt_" + feed_id).addClass("playing").attr("title", "Stop video");
			$("#play_main_" + feed_id).addClass("playing").attr("title", "Stop video");
			$("#play_main_" + feed_id + " img").attr("src", base_url + "media/img/stopbutton.png");
		}
		return false;
	});
	
	$("a.show-more-thumbs").click(function(){
		// get the id
		var id = this.id + "";
		// get the feed id
		var chunks = id.split("_");
		var feed_id = chunks[2];
		
		var thumbs_id = "thumbs_" + feed_id;
		// show more thumbs
		$("#" + thumbs_id + " .img-thumb").removeClass("hidden");
		//hide button
		$(this).parent('span').hide();
		
		return false;
	});

	// delay update a bit
	setTimeout('updateDateSpan()', 150);
	// schedule span update every 1 minute
	setInterval('updateDateSpan()', 60000);
});

function updateDateSpan()
{
	var currentDate = new Date();
	var dcDate = new DcDate();
	$(".fuzzy_span").each(function(){

		// find the timestamp from the id
		var id = this.id + '';
		var chunks = id.split('_');
		if (chunks.length <= 1)
		{
			return false;
		}

		var timestamp = parseInt(chunks[1]);
		if (isNaN(timestamp))
		{
			return false;
		}

		// update the date span now
		var span = dcDate.fuzzySpan(timestamp, currentDate);
		if (span.length > 0)
		{
			$(this).html(span);
		}
	});
}
