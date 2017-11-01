MediaUpload = function (container, callback, url) {
	this.container = container;
	this.input = container.querySelector('input[type=file]');
	this.image = container.querySelector('img');

	this.sent = 0;
	this.progress = 0;

	if(!this.image) {
		this.image = document.createElement('img');
		this.container.appendChild(this.image);
	}

	this.callback=callback;
	this.url = url || 'data/media/upload.php';

	this.rotation = 0;
	var that = this;

	this.tools = document.createElement('nav');
	var rotate = document.createElement('a');
	rotate.className = 'action rotateLeft';
	rotate.innerHTML = 'r';
	rotate.addEventListener('click', throttle(function() {
		that.rotate(90);
		return false;
	}, 250));
	this.tools.appendChild(rotate);

/*
	rotate = document.createElement('a');
	rotate.className = 'action rotateRight';
	rotate.innerHTML = 'l';
	rotate.addEventListener('click', throttle(function() {
		that.rotate(-90);
		return false;
	}, 250));
	this.tools.appendChild(rotate);
*/

	rotate = document.createElement('a');
	rotate.className = 'action submit';
	rotate.innerHTML = 'Ok';
	rotate.addEventListener('click', function(){
		that.send();
		that.showTools(false);
		return false;
	})
	this.tools.appendChild(rotate);

	this.container.appendChild(this.tools);


	var readFiles = function(e) {
		if ( window.FileReader && window.File && window.FileList && window.Blob ) {
			var files = e.target.files; // FileList object

			// Loop through the FileList and render image files as thumbnails.
			for (var i = 0, f; f = files[i]; i++) {

				// Only process image files.
				if (!f.type.match('image.*'))
					continue;

				var reader = new FileReader();

				// Closure to capture the file information.
				reader.onload = (function(theFile) {
					return function(e) {
						// display tooooools
						that.showTools();

						// Render thumbnail
						that.image.src = e.target.result;
					};
				})(f);

				// Read in the image file as a data URL.
				reader.readAsDataURL(f);
			}
			return true;
		}
		return false;
	};

	this.input.onchange = function(e){
		that.progress = 0;

		if (!readFiles(e))
			that.send();
	};
};

MediaUpload.prototype.showTools = function(show) {
	if(show === false)
		this.tools.classList.remove('open');
	else
		this.tools.classList.add('open');
}

MediaUpload.prototype.setRotation = function (angle) {
	this.rotation = angle || 0;
}
MediaUpload.prototype.rotate = function (angle) {
	this.rotation+= angle || 0;
	this.rotation %= 360;

	this.image.setAttribute('data-rotation', this.rotation);
}

MediaUpload.prototype.send = function () {
	this.container.classList.add = 'progress';

	var fd = new FormData();
	fd.append('upload', this.input.files[0]);
	fd.append('rotation', this.rotation);

	var xhr = new XMLHttpRequest();
	xhr.open('POST', this.url, true);

	xhr.upload.onprogress = function(e) {
		if (e.lengthComputable) {
			//var percentComplete = Math.round( ((e.loaded / e.total)*100) / 5) *5;
			var percentComplete = Math.round( (e.loaded / e.total)*100 );
			that.container.setAttribute('data-progress', percentComplete);
			that.progress = percentComplete;
		}
	};

	var that = this;
	xhr.onload = function() {
		if(this.readyState  == 4)
			try {
				that.container.removeAttribute('data-progress');
				++that.sent;
				that.progress = 100;

				var data = JSON.parse(this.response);

				if(this.status != 200 || data.error)
					new Note(data.error, 3000, 'error');
				else {
					that.image.setAttribute('data-rotation', 0);
					that.showTools(false);

					that.input.value = that.input.defaultValue;
					that.callback(data);
				}
			} catch (e) {
				new Note(e + ' (MediaUpload) Error '+this.responseText, 3000, 'error');
			}

			that.image.style.visibility='visible';
	};
	xhr.send(fd);
}