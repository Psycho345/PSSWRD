<?

class PSSWRD 
{
	private $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+";

	public function generateSecret($length = 32) {
		$str = "";
		for($i = 0; $i < $length; $i++) 
			$str .= $this->chars[rand(0, $length - 1)];
		return $str;
	}

	public function generatePassword($service, $password, $secret, $realm = "", $length = 18, $iterations = 10000) {
		$hash = $service . $password . $secret . $realm;
		for ($i = 0; $i < $iterations; $i++) {
			$hash = hash("sha512", $hash);
		}
		$num = $this->hexToDec($hash);
		$charsNum = (string)strlen($this->chars);
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$idx = (int)bcmod($num, $charsNum);
			$num = bcdiv($num, $charsNum);
			$str = $this->chars[$idx] . $str;
		}
		return $str;
	}

	private function hexToDec($hex) {
		$str = "";
		$length = strlen($hex);
		for ($i = 0; $i < $length; $i++) {
			$str = bcadd($str, bcmul(strval(hexdec($hex[$i])), bcpow('16', strval($length - $i - 1))));
		}
		return $str;
	}
}