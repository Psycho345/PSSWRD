<?

class PSSWRD 
{
	private $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';

	public function generateSecret($length = 32) {
		$str = '';
		for($i = 0; $i < $length; $i++) 
			$str .= $this->chars[rand(0, $length - 1)];
		return $str;
	}

	public function generatePassword($service, $password, $secret, $realm = '', $realm2 = '', $maxLength = 18, $iterations = 2500) {
		$hash = $password . $secret;
		for ($i = 0; $i < $iterations; $i++) 
			$hash = hash('sha512', $hash);
		$hash = $service . $this->hashToChars($hash);
		for ($i = 0; $i < $iterations; $i++)
			$hash = hash('sha512', $hash);
		$hash = $this->hashToChars($hash) . $realm;
		for ($i = 0; $i < $iterations; $i++)
			$hash = hash('sha512', $hash);
		$hash = $this->hashToChars($hash) . $realm2;
		for ($i = 0; $i < $iterations; $i++)
			$hash = hash('sha512', $hash);	
		return $this->hashToChars($hash, $maxLength);
	}

	private function hashToChars($hash, $maxLength = 0) {
		$num = $this->hexToDec($hash);
		$charsNum = (string)strlen($this->chars);
		$str = '';
		while ($num != '0' && ($maxLength <= 0 || strlen($str) < $maxLength)) {
			$idx = (int)bcmod($num, $charsNum);
			$num = bcdiv($num, $charsNum);
			$str = $this->chars[$idx] . $str;
		}
		return $str;
	}

	private function hexToDec($hex) {
		$str = '';
		$length = strlen($hex);
		for ($i = 0; $i < $length; $i++) {
			$str = bcadd($str, bcmul(strval(hexdec($hex[$i])), bcpow('16', strval($length - $i - 1))));
		}
		return $str;
	}
}