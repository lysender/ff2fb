function DcDate(){}

DcDate.prototype = {
	// date constants probably
	YEAR: 	31556926,
	MONTH: 	2629744,
	WEEK: 	604800,
	DAY: 	86400,
	HOUR:	3600,
	MINUTE:	60,

	// days in a week, long
	days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],

	// months in a year, short
	months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

	/** 
	 * Returns a human readable format time span
	 * for a given date in the past against a current date/time
	 *
	 * @param int timestamp		unix timestamp in the past
	 * @param Date currentDate	Date object containing current date
	 * @return string
	 */
	fuzzySpan: function(timestamp, currentDate)
	{
		if (typeof current == "undefined")
		{
			// get the current time stamp
			currentDate = new Date();
		}

		// get the offset in seconds and other related objects
		// need for pre calculations of time span
		var offset = (Math.round(currentDate.getTime() / 1000) - timestamp);
		var subjectDate = new Date(timestamp * 1000);
		var span = '';
		
		// determine the span in human readable format
		if (offset < this.MINUTE)
		{
			span = offset + ' seconds ago';
		}
		else if (offset <= (this.MINUTE + 59))
		{
			span = 'a minute ago';
		}
		else if (offset < this.HOUR)
		{
			span = Math.floor(offset / this.MINUTE) + ' minutes ago';
		}
		else if (offset <= (this.HOUR * 2) - 1)
		{
			span = 'an hour ago';
		}
		else if (offset < this.DAY)
		{
			span = Math.floor(offset / this.HOUR) + ' hours ago';
		}
		else if (offset <= (this.DAY * 2) - 1)
		{
			span = 'Yesterday';
		}
		else if (offset < this.WEEK)
		{
			span = 'last ' + this.days[subjectDate.getDay()];
		}
		else if (offset <= (this.WEEK + (this.HOUR * 5)))
		{
			span = 'a week ago';
		}
		else if (offset < (this.MONTH * 3))
		{
			span = this.months[subjectDate.getMonth()] + ' ' + subjectDate.getDate();
		}
		else
		{
			span = this.months[subjectDate.getMonth()] + ' ' + subjectDate.getDate() + ', ' + subjectDate.getFullYear();
		}
		return span;
	}
}
