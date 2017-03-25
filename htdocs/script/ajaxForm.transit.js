function form2args (elements) {
	var args = '';
	for(var i=0, l=elements.length; i<l; i++) {
		var el=elements[i];
		if (el.name) {
			if (el.tagName.toLowerCase() == 'select')
				args+= '&'+el.name+'=' + el.options[el.selectedIndex].value;
			else if (el.tagName.toLowerCase()=='input' && el.type=='checkbox')
				if(el.checked)
					args+= '&'+el.name+'=' + el.value;
				else
					args+= '';
			else if(el.tagName.toLowerCase()=='textarea' || (el.tagName.toLowerCase()=='input' && el.type!='file' && el.type!='submit' && el.type!='button'))
				args+= '&'+el.name+'=' + el.value;
		}
	}
	return args.substring(1);
}

AjaxForm = function (form, callback, callfirst) {
	var that = this;

	function defaultCallfirst (that) {
		console.log('callfirst here !', that);
	}

	function defaultCallback (response) {
		console.log('callback here !', response);
	}

	this.form      = form;
	this.callfirst = typeof callfirst!=='undefined' ? callfirst:defaultCallfirst;
	this.callback  = typeof callback!=='undefined' ? callback:defaultCallback;
	this.xhr       = getXMLHttpRequest();

	this.form.addEventListener('submit', function (evt) {
		//@TODO form validation...
		evt.preventDefault();
		that.post(evt);
		return false;
	});
}
AjaxForm.prototype.post = function (evt) {
	if(this.xhr) {
		if(evt)
			evt.preventDefault();

		if(this.xhr.readyState === 0 || this.xhr.readyState == 4) {
			var that=this,
			params = form2args(this.form.elements);

			var callfirst = this.callfirst();
			params += (callfirst!=='' ) ? ((params==='') ? '?':'')+'&'+callfirst:'';

			this.xhr.open(this.form.method, this.form.action+((this.form.method=='get')?((params.lastIndexOf('?')==-1)?'?':'&')+params:''), true);
			this.xhr.setRequestHeader("Cache-Control", "no-cache");
			this.xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			this.xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			this.xhr.onreadystatechange = function () {
				if(this.readyState  == 4)
					try {
						that.callback(JSON.parse(this.responseText));
						if(that.status == 205) // http 205 : Reset Content
							that.form.reset();
					} catch(e) {
						alert('An error occured. Sorry for the inconvenience. ;-) (xhr.status = '+this.status+'  (http status))');
					}
			};

			this.xhr.send((this.form.method=='post')?params:null);

		} else setTimeout(this.get, 500);
	} else if(this.xhr == null)
		alert('An error occured. Sorry for the inconvenience. ;-) (xhr = null)');

	return false;
}