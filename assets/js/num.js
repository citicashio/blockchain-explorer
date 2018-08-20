const splittingNumb = document.getElementsByClassName('splittingNumb');

function handleSplit(num){
	const value = num.innerHTML;
	if(value > 999){
		var result = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
		console.log(num);
		num.innerHTML = result;
	}
}

for(let i=0; i < splittingNumb.length; i++){
	handleSplit(splittingNumb[i]);
}