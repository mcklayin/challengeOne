function reverseAsBinary(value) {
	if (typeof value !== 'number') {
		alert('Argument is not a number');
		return NaN;
	}

	if (!Number.isInteger(value)) {
		alert('Argument is not an integer');
		return NaN;	
	}

	const binary = Number(value).toString(2);

	return parseInt(binary.split('').reverse().join(''), 2);
}

console.log(reverseAsBinary(13));