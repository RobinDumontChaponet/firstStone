View = function (container, callback) {
	var headElement = document.getElementsByTagName('head')[0];
	function addTag (type, content) {
		var fileref;
		if(type=='meta') {
			headElement.insertAdjacentHTML('beforeend', content);
		} else if(type=='link') {
			headElement.insertAdjacentHTML('beforeend', content);
		} else if(type=='css') {
			fileref=document.createElement('style');
			fileref.setAttribute('type', 'text/css');
			fileref.innerHTML = content;
			headElement.appendChild(fileref);
		} else if(type=='js') {
			headElement.insertAdjacentHTML('beforeend', content);
		} else if(type=='script') {
			fileref=document.createElement('script');
			fileref.setAttribute('type', 'text/javascript');
			fileref.setAttribute('src', content);
			headElement.appendChild(fileref);
		}
	}
	function addToHead (type, arr) {
		if(Array.isArray(arr))
			for(var i=0, l=arr.length; i<l; i++)
				addTag(type, arr[i]);
	}

	function defaultCallback (response) {
		document.title = response.title;

		addToHead ('meta', response.metaTags);
		addToHead ('link', response.linkTags);
		addToHead ('css', response.styles);
		addToHead ('js', response.scripts);
		addToHead ('script', response.scriptLinks);

		if(response.style) {
			var style = document.createElement('style');
			style.innerHTML = response.style;
			document.getElementsByTagName('head')[0].appendChild(style);
		}

		this.container.innerHTML=response.content;
	}

	this.container = container;
	this.callback  = typeof callback!=='undefined' ? callback:defaultCallback;
	this.src       = '';
	this.xhr       = getXMLHttpRequest();
}
View.prototype.get = function (src) {
	if(typeof src !== 'undefined')
		this.src = src;
	if(!this.src)
		return false;

	if(this.xhr) {
		if(this.xhr.readyState === 0 || this.xhr.readyState == 4) {
			var that=this;
			this.xhr.open('post', this.src, true);
			this.xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			this.xhr.onreadystatechange = function () {
				if(this.readyState  == 4)
					//if(this.status == 200) {
					try {
						that.callback(JSON.parse(this.responseText));
					} catch(e) {
						alert('An error occured. Sorry for the inconvenience. ;-) (xhr.status = '+this.status+')');
					}
			};
			this.xhr.send(null);
		} else setTimeout(this.get, 500);
	} else if(this.xhr == null)
		alert('An error occured. Sorry for the inconvenience. ;-) (xhr = null)');
}