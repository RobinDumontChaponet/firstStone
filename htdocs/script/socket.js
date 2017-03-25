Socket = function (url, logger) {
	function defaultLogger (user, content, time) {
		console.log('Socket log : ', time.humanReadable, user, content);
	}

	this.ws = null;
	this.url = url;

	this.subscribers = Array();

	this.logger = typeof logger!=='undefined' ? logger:defaultLogger;

	this.init();

	var that = this;
	window.addEventListener('close', function(){that.ws.close()});
	window.addEventListener('unload', function(){that.ws.close()});
};

Socket.prototype.subscribe = function (subscriber) {
	this.subscribers.push(subscriber);
};

Socket.prototype.init = function() {
	try {
		this.ws = new WebSocket(this.url);

		var that = this;

		this.log('system', 'Connecting... (' + this.ws.readyState +')', Date.now());
		this.ws.onopen = function() {
			that.log('system', 'Connected. (' + this.readyState +')', Date.now());

			that.subscribers.forEach(function(subscriber) {
				if(typeof(subscriber.onOpen) == "function")
					subscriber.onOpen();
			});
		};

		this.ws.onmessage = function(message) {
			var data = JSON.parse(message.data);


			that.subscribers.forEach(function(subscriber) {
				if(typeof(subscriber.receive) == "function")
					subscriber.receive(data);
			});
		};

		this.ws.onclose = function() {
			that.log('system', 'Disconnected. (' + this.readyState +')', Date.now());

			that.subscribers.forEach(function(subscriber) {
				if(typeof(subscriber.onClose) == "function")
					subscriber.onClose();
			});
		};
	} catch (e) {
		this.log('sytem-error', e, Date.now());
	}
};

Socket.prototype.send = function(content) {
	var that = this;
    this.onReady(function() {
		try {
			that.ws.send(JSON.stringify(content));
		} catch (e) {
			that.log('system-error', e, Date.now());
		}
    });
}

Socket.prototype.onReady = function (callback) {
	var that = this;
	setTimeout(function () {
		if (that.ws.readyState === 1) {
			if(callback != null)
				callback();

			return;
		} else
			that.onReady(callback);
	}, 5);
}

Socket.prototype.close = function(content) {
	if (this.ws != null) {
		this.log('system', 'Goodbye !', Date.now());
		this.ws.close();
		this.ws = null;
	}
};

Socket.prototype.reconnect = function(content) {
	this.close();
	this.init();
};

Socket.prototype.log = function (user, content, time) {
	var date = new Date((time.toString().length==10)?time*1000 : time);

	time = {
		hours : date.getHours(),
		minutes : ('0' + date.getMinutes()).substr(-2),
		seconds : ('0' + date.getSeconds()).substr(-2),
	}
	time.humanReadable = time.hours + ':' + time.minutes + ':' + time.seconds

	this.logger(user, content, time);
};