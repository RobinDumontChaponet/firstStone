(function() {
	// define console if not already defined
	console || (console = {});

	if(DEBUG=='send') {
		console.log = function(log) {
			var http = new XMLHttpRequest(),
			url = 'helpers/log.php',
			params = 'log=' + Date.now() + ' ' + escape(sanitize(log));
			http.open('POST', url, true);
			http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			http.send(params);
		}

		var sanitize = function(txt) {
			var output, x;

			if(typeof txt == 'object') {
				// create a new object to get around circular references
				output = {};
				for(x in txt) {
					// type conversion of each element to a string
					output[x] = txt[x] + '';
				}
				output = JSON.stringify(output, null, 2);
			} else
				output = txt;

			return output;
		}
	} else if (!DEBUG)
		console.log = function(){};

})();