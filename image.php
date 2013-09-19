<?php 

class Image
{
	private $_data;
	public $tag;

	public function __construct($width=1,$height=1)
	{
		$this->_data = @imagecreate($width, $height) or die("Cannot Initialize new GD image stream");
	}

	public function render()
	{
		header('Content-Type: image/png');
		imagepng($this->_data);
	}

	public function encode()
	{
		ob_start();
		imagepng($this->_data);
		$png = ob_get_contents();
		ob_end_clean();
		return $png;
	}
}
