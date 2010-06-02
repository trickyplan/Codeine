<?php
	/**
	 *
	 * @author Andrii Kasian
	 */
	class Captcha4d{
		const CHARS = 'WEafRTYIPAGHJKXBNM3479j';
		protected $hypot = 5;
		protected $image = null;
		protected $_sin = array();
		protected $text = '';

		public function __construct()
		{
			$this->time = microtime(true);
			$this->generateCode();

		}
		protected function generateCode()
		{
			$chars = self::CHARS;
			for($i =0; $i<3; $i++){
				$this->text .= $chars{ mt_rand(0,22)};
			}
		}

		public function getText()
		{
			return $this->text;
		}
		protected function getProection($x, $y, $z)
		{

			$xx = 0.70710;
			$xz = 0;
			$xy = 0.70710;

			$yx = 0.40824;
			$yz = 0.81649;
			$yy = -0.40824;

			$cx = $xx*$x + $xy*$y + $xz*$z - 5;
			$cy = $yx*$x + $yy*$y + $yz*$z + 20;
			return array(
				'x' => $cx * $this->hypot,
				'y' => $cy * $this->hypot
				);
		}

		function zFunction($x,$y){
			$z = imagecolorat($this->image,$y/2,$x/2)>0?3:0;
			if( $z != 0 ){
				$z += -2+ 2*
						$this->_sin[($x+$this->startX)%30]
						*
						$this->_sin[($y+$this->startY)%30];
			}
			$z += mt_rand(0,30)/50;
			return $z;
		}
		public function render($text)
		{
                        $this->text = $text;
			$xx = 30;
			$yy = 60;

			$animation = new Imagick();
			$animation->setFormat( "gif" );

			$cw = new ImagickPixel("white");
			$cb = new ImagickPixel("#000000");

			$this->image = imageCreateTrueColor(100, 20);

			$whiteColor = imageColorAllocate($this->image,255,255,255);
			imageFilledRectangle($this->image,0,0,$yy * $this->hypot  , $xx * $this->hypot, $whiteColor);
			$textColor = imageColorAllocate($this->image,0,0,0);
			imageString($this->image, 5, 3, 0, $this->text, $textColor);

			$cof = 2*3.141592654/$xx;
			for($x = 0; $x < $xx + 1; $x++){
				$this->_sin[$x] = sin($x*$cof);
			}
			$this->startX = mt_rand(0,$xx);
			$this->startY = mt_rand(0,$yy);

			$draw = new ImagickDraw();
			$countFrame = 25;

			for ( $i = 0; $i < $countFrame; $i++ ) {
				$this->startX += $xx / $countFrame;

				$coordinates = array();
				for($x = 0; $x < $xx + 1; $x++){
					for($y = 0; $y < $yy + 1; $y++){
						$coordinates[$x][$y] = $this->getProection($x,$y,$this->zFunction($x,$y));
					 }
				 }

				$animation->newImage( $yy * $this->hypot  , $xx * $this->hypot, $cw);

				$im = new ImagickDraw();
				$im->setFillColor($cw);
				$im->setStrokeColor($cb);
				$im->setStrokeAntialias(true);
				for($x = 0; $x < $xx; $x++){
					for($y = 0; $y < $yy; $y++){
						 $coord = array();
						 $coord[] = $coordinates[$x][$y];
						 $coord[] = $coordinates[$x+1][$y];
						 $coord[] = $coordinates[$x+1][$y+1];
						 $coord[] = $coordinates[$x][$y+1];

						 $im->polygon($coord);
					 }
				 }
				$animation->drawImage($im);
				$animation->setImageDelay( 100/$countFrame );
			}

			file_put_contents(Root.Data.'Temp/CAPTCHA/'.$UID.'.gif', $animation->getImagesBlob());
		}
	}

function F_CAPTCHA4D_Generate()
{
        $Answer = Code::E('Generator/Keyword','Generate',array('Length'=>4),'Numbers');
        $UID = sha1($Answer);

        $captcha = new Captcha4d();
	$captcha->render($UID);

        Client::$Ticket->Set('CAPTCHA:4D:Answer', $Answer);
        return '<img src="/Data/Temp/CAPTCHA/'.$UID.'.gif" /> <input name="CAPTCHA" type="text" />';
}

function F_CAPTCHA4D_Check($Args)
{
    $Answer = Client::$Ticket->Get('CAPTCHA:4D:Answer');
    unlink (Root.Data.'Temp/CAPTCHA/'.sha1($Answer).'.gif');
    if ($Answer == Server::Get('CAPTCHA'))
        return true;
    else
        return false;
}