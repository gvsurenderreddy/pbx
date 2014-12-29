<?php

namespace VoIP\UI\BasicBundle\Extra;

use Aws\Common\Aws;
use Aws\S3\Exception\S3Exception;

class Image {
	private $file;
	private $dimensions;
	private $paths = array();
	private $hash;
	private $extension;
	private $names = array();
	private $container;
	
	function __construct($file, $dimensions = array('256'), $destinationFolder, $container) {
		$this->container = $container;
		$this->file = $file;
		$this->dimensions = $dimensions;
		$hash = hash('crc32b', uniqid(mt_rand(), true));
		$this->hash = $hash;
		$name = $hash.'.'.$file->guessExtension();
		$this->extension = $file->guessExtension();
	
        $file->move($this->getUploadRootDir(), $name);
		$names = array();
		foreach ($dimensions as $dimension) {
			$this->names[$dimension] = $hash.'_'.$dimension.'.'.$this->extension;
			$this->resizeImage($this->getAbsolutePath($name), $dimension, $this->getUploadRootDir(), $this->names[$dimension]);
			$this->paths[$dimension] = $this->toS3($this->getAbsolutePath($this->names[$dimension]), $destinationFolder.'/'.$this->names[$dimension]);
		}
	}
	
	public function getPaths($dimension = null)
	{
		if (!$dimension) return $this->paths;
		else return $this->paths[$dimension];
		
	}
	
	private function toS3($absolutePath, $destinationPath)
    {
    	$aws = Aws::factory(array(
			'key'    => $this->container->getParameter('aws_key'),
			'secret' => $this->container->getParameter('aws_secret'),
			'region' => 'ap-southeast-1'
			));
    	$s3 = $aws->get('s3')->putObject(array(
			'Body' => file_get_contents($absolutePath),
			'Bucket' => 'vf-fortyeight',
			'Key' => $destinationPath,
			'ACL' => \AmazonS3::ACL_PUBLIC,
			'Metadata' => array(
				'Cache-Control'    => 'max-age=8000000',
				'Content-Language' => 'en-US',
				'Expires'          => 'Tue, 01 Jan 2030 03:54:42 GMT',
			)
		));
		return $s3['ObjectURL'];
    }
	
	private function resizeImage( $tmpname, $size, $save_dir, $save_name )
	{
	    $save_dir .= ( substr($save_dir,-1) != "/") ? "/" : "";
		$gis = GetImageSize($tmpname);
	    $type = $gis[2];
	    switch ($type) {
	        case "1": $imorig = imagecreatefromgif($tmpname); break;
	        case "2": $imorig = imagecreatefromjpeg($tmpname);break;
	        case "3": $imorig = imagecreatefrompng($tmpname); break;
	        default:  $imorig = imagecreatefromjpeg($tmpname);
		}
		$x = imageSX($imorig);
		$y = imageSY($imorig);
		if ($x < $y) {
			//$imorig->resizeToHeight($x);
			$min = $x;
		} else {
			//$imorig->resizeToWidth($y);
			$min = $y;
		}
		$x = $min;
		$y = $min;
		if ($gis[0] <= $size) {
			$av = $x;
			$ah = $y;
		} else {
			$yc = $y*1.3333333;
			$d = $x>$yc?$x:$yc;
			$c = $d>$size ? $size/$d : $size;
			$av = $x*$c;
			$ah = $y*$c;   
		}   
		$im = imagecreate($av, $ah);
		$im = imagecreatetruecolor($av,$ah);
		imageinterlace($im, true);
		if (imagecopyresampled($im,$imorig , 0,0,0,0,$av,$ah,$x,$y))
			if (imagejpeg($im, $save_dir.$save_name)) return true;
	        else return false;
	}
	
	private function getAbsolutePath($imagePath)
    {
        return $this->getUploadRootDir().'/'.$imagePath;
    }

    private function getWebPath($imagePath)
    {
        return $this->getUploadDir().'/'.$imagePath;
    }

    private function getUploadRootDir()
    {
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    private function getUploadDir()
    {
        return 'uploads';
    }
}