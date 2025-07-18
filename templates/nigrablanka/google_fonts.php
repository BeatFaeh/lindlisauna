<?php
/**
 *
 * @category        include
 * @package         localfont
 * @author          Ruud Eisinga - Dev4me
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.10.x || WBCE 1.4.x
 * @requirements    PHP 7.4 and higher
 * @version         0.3
 * @lastmodified    August 15, 2022
 *
 */

if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

/* 

v0.3 accepts 3 different formats:

1. just using the name and optionally weight. 
$font[] = "Open Sans";
$font[] = "Open Sans:400,600,700";
$font[] = "Noto Sans:wght@400;600;700;900";

2. use the link with one or more fonts
$font[] = 'https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&display=swap';
$font[] = 'https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&family=Mouse+Memoirs&display=swap';

3. use the complete tag presented by google fonts
$font[] = '<link href="https://fonts.googleapis.com/css2?family=DynaPuff:wght@400;700&family=Ubuntu:wght@300;350;400;700&display=swap" rel="stylesheet">';

*/


if(!isset($font)) return;
if(!is_array($font)) $font = explode('||',$font);
if(!isset($font_format)) $font_format = "woff";


$cssdir = '/css/local/';
$fontdir = '/fonts/local/';

define ('LOCALFONT_INCLUDE_DEBUG', false);  // set to true to get lots of info in the errorlog on refreshing

define ('LOCALFONT_INCLUDE_CSS',  TEMPLATE_DIR . $cssdir);
define ('LOCALFONT_INCLUDE_FONT', TEMPLATE_DIR . $fontdir);
define ('LOCALFONT_PATH_CSS',     __DIR__ . $cssdir);
define ('LOCALFONT_PATH_FONT',    __DIR__ . $fontdir);
define ('GOOGLE_API', 'https://fonts.googleapis.com/');





$rval = "<!-- google fonts loaded locally by 'localfonts_include v0.3' - dev4me.com -->".PHP_EOL;
if(!function_exists('curl_version')) {
	$rval .= "\t<!-- ERROR: unfortunately local fonts downloading is not possible because the PHP extension cUrl is not available. Please follow the google-font instructions to download and use local copies of the fonts needed! -->".PHP_EOL;
} else {
	$localfonts_include = new localfonts_include();
	foreach($font as $fontfile) {
		$fonts = $localfonts_include->get_query($fontfile);
		foreach($fonts as $family) {
			$apiversion = stripos($family,'wght@') ? 'css2':'css';
			$rval .= "\t".$localfonts_include->get_font_file(GOOGLE_API.$apiversion.'?family='.$family.'&display=swap');
		}
	}
}
echo $rval;


class localfonts_include {

	var $reloading = false;
	
	function __construct() {
		global $font_format;
		$this->create_folders();
		if(isset($_SESSION['USER_ID'])) {  //Detect CTRL-F5 only when logged in user
			if(isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'no-cache') {
				$this->localfont_log("CTRL-F5 detected, force reloading fonts");
				$this->localfont_log(" - using font_format '$font_format'");
				$this->reloading = true;
				// Delete all files in the local folders, force reload
				array_map( 'unlink', array_filter((array) glob(LOCALFONT_PATH_CSS."*") ) );
				array_map( 'unlink', array_filter((array) glob(LOCALFONT_PATH_FONT."*") ) );
			}
		}
	}

	function get_query($line) {
		if($this->reloading) $this->localfont_log(" - process '$line'");
		$params = array();
		if(stripos($line,'href') !== false) {
			if($this->reloading) $this->localfont_log(" - extract href from '$line'");
			$line = $this->get_href($line);
		}
		if(strpos($line,'?') !== false) {
			if($this->reloading) $this->localfont_log(" - extract parameters from '$line'");
			$line = substr($line,strpos($line,'?')+1);
		}
		if (strpos($line, 'family=') === false) {
			if($this->reloading) $this->localfont_log(" - no url in '$line', use name 'as is'");
			$params[] = str_replace(" ","+",$line);
		} else {
			if($this->reloading) $this->localfont_log(" - check for multiple families '$line'");
			$query  = explode('&', $line);
			foreach( $query as $param ) {
				if (strpos($param, '=') === false) $param += '=';
				list($name, $value) = explode('=', $param, 2);
				if($name == 'family') {
					if($this->reloading) $this->localfont_log(" - - found '$value'");
					$params[] = $value;
				}
			}
		}
		return $params;		
	}
	
	function get_href($link) {
		
		$link =  substr ( $link,(strpos($link,' href=') + 6) );
		$link = explode($link[0],$link);
		return $link[1];
	}


	function get_font_file($url) {
		
		if($cssfilename = $this->local_name($url)) {
			if(file_exists(LOCALFONT_PATH_CSS.$cssfilename)) {
				$data = $this->read_font_file($url); // read local css file
			} else {
				if($data = $this->read_font_url($url)) { // read css from google fonts
					preg_match_all("/url\((.*?)\\)/", $data, $matches); 
					foreach($matches[1] as $match) {
						$p = pathinfo($match);
						$filename = $p['filename'].'.'.$p['extension'];
						$data = str_replace($match, LOCALFONT_INCLUDE_FONT.$filename, $data);
						
						$fontdata = $this->read_font_url($match);
						$this->save_font_file(LOCALFONT_PATH_FONT.$filename,$fontdata);
					}
					$this->save_font_file(LOCALFONT_PATH_CSS.$cssfilename,$data);
				} 
			}
			$timestamp = filectime(LOCALFONT_PATH_CSS.$cssfilename);
			$rval = '<link href="'.LOCALFONT_INCLUDE_CSS.$cssfilename.'?if='.$timestamp.'" rel="stylesheet" type="text/css">';
			if(LOCALFONT_INCLUDE_DEBUG) { 
				preg_match_all("/font-family: (.*?);/s", $data, $matches); 
				$rval .= ' <!-- Use: font-family:'.$matches[1][0]. ' -->'.PHP_EOL;
			} else {
				$rval .= PHP_EOL;
			}
			return $rval;
		}
	}

	function read_font_url($url) {
		global $font_format;
		
		// The useragent determines what type is used by Google

		if($this->reloading) $this->localfont_log(" - - loading '$url'");
		
		$ua = 'Mozilla/5.0 (Unknown; Linux x86_64) AppleWebKit/538.1 (KHTML, like Gecko) Safari/538.1 Daum/4.1'; // TrueType format
		if(strtolower($font_format) == "woff")          $ua = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0'; // Woff format (supported by all browsers, including IE9+)
		if(strtolower($font_format) == "woff2")         $ua = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0'; // Woff2 format (Modern browsers)
		if(strtolower($font_format) == "woff2-unicode") $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.81 Safari/537.36'; // Woff2 format multiple unicode ranges
		if(strtolower($font_format) == "user")          $ua = $_SERVER['HTTP_USER_AGENT'];
		
		// Fetch data fromt url
		$ch = curl_init();   
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);   
		curl_setopt($ch, CURLOPT_URL, $url);   
		$res = curl_exec($ch);  
		if(($err = curl_getinfo($ch, CURLINFO_HTTP_CODE)) == 200) {
			return $res;
		}
		if($this->reloading) $this->localfont_log(" - - status '$err' on '$url'");
		return null;
	}
	
	function read_font_file($filename) {
		$rval = file_get_contents($filename);
		return $rval;
	}

	function save_font_file($filename, $content)  {
		file_put_contents($filename, $content);
	}

	function local_name($url) {
		// example: https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700'
		if(stripos($url,'family=') !== false ) {
			$myname = substr($url,stripos($url,'family=')+7);
			if(stripos($myname,':') !== false ) { $myname = substr($myname,0,stripos($myname,':'));	}
			if(stripos($myname,'&') !== false ) { $myname = substr($myname,0,stripos($myname,'&'));	}
			$myname = str_replace('+','-',$myname);
			$myname = strtolower ($myname).'.css';
			return $myname;
		}
		return NULL;
	}
	
	function create_folders() {
		if (!file_exists(LOCALFONT_PATH_CSS)) mkdir(LOCALFONT_PATH_CSS, OCTAL_DIR_MODE , true);
		if (!file_exists(LOCALFONT_PATH_FONT)) mkdir(LOCALFONT_PATH_FONT, OCTAL_DIR_MODE , true);
	}
	
	function localfont_log($message) {
		if(!LOCALFONT_INCLUDE_DEBUG) return;
		$sLogfile = ini_get ('error_log');
		if (!is_writeable($sLogfile)){return false;}
		$sMsg = date('c').' '.'[LOCALFONT] "'.htmlspecialchars($message).'"'.PHP_EOL;
		file_put_contents($sLogfile, $sMsg, FILE_APPEND);
	}

}
