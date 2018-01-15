class PSSWRD 
{
	constructor() {
		this.chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+";
	}
	
	generateSecret(length) {
		length = length || 32;
		let str;
		for(let i = 0; i < length; i++) 
			str += this.chars[Math.floor(Math.random() * length)];
		return str;
	}

	generatePassword(service, password, secret, realm, length, iterations) {
		realm = realm || "";
		length = length || 18;
		iterations = iterations || 10000;
		let hash = service + password + secret + realm;
		for (let i = 0; i < 2500; i++) {
			hash = sha512(hash);
		}
		let num = new BigNumber(this.hexToDec(hash));
		let charsNum = this.chars.length;
		let str = "";
		for (let i = 0; i < length; i++) {
			let idx = parseInt(num.modulo(charsNum));
			num = num.dividedToIntegerBy(charsNum);
			str = this.chars[idx] + str;
		}
		return str;
	}

	hexToDec(hex) {
		function add(x, y) {
			var c = 0, r = [];
			var x = x.split('').map(Number);
			var y = y.split('').map(Number);
			while(x.length || y.length) {
				var s = (x.pop() || 0) + (y.pop() || 0) + c;
				r.unshift(s < 10 ? s : s - 10); 
				c = s < 10 ? 0 : 1;
			}
			if(c) r.unshift(c);
			return r.join('');
		}
	
		let dec = '0';
		hex.split('').forEach(function(chr) {
			let n = parseInt(chr, 16);
			for(let t = 8; t; t >>= 1) {
				dec = add(dec, dec);
				if(n & t) dec = add(dec, '1');
			}
		});
		return dec;
	}
}