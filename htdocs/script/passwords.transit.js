function checkPassword(input, bar) {
	var level = 0;
	if (input.value.length >= 8)
		level++;
	if (/[a-z]/.test(input.value))
		level++;
	if (/[A-Z]/.test(input.value))
		level++;
	if (/[0-9]/.test(input.value))
		level++;
	if (/[\!\@\#\$\%\^\&\*\(\)\-\_\=\+\[\{\]\}\|\\\;\:\'\"\,\<\.\>\/\?\`\~]/.test(input.value))
		level++;
	bar.progress((level/5)*100);
}

function ProgressBar (id, insertBefore) {
	this.div=document.createElement('div');
	this.div.innerHTML='<span></span>';
	if(id) this.div.id=id;
	this.div.className='progress';
	this.bar=this.div.childNodes[0];
}
ProgressBar.prototype.progress=function (val) {
	this.bar.style.paddingLeft=val+'%';
	this.bar.setAttribute('data-progress',Math.floor(val)+'%');
};
ProgressBar.prototype.insertBefore = function (before) {
	((before)?before.parentNode:document.body).insertBefore(this.div, before);
}