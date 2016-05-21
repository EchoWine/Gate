<?php


namespace CoreWine\TemplateEngine;

/**
 * Translate content page
 */
class Translator{

	/**
	 * Filename
	 */
	public $filename;

	/**
	 * Sub Path
	 */
	public $subPath;

	/**
	 * Relative Path
	 */
	public $relativePath;

	/**
	 * Construct
	 *
	 * @param string $filename
	 * @param string $subPath
	 * @param string $relativePath
	 * @return string
	 */
	public function __construct($filename,$subPath,$relativePath){
		$this -> filename = $filename;
		$this -> subPath = $subPath;
		$this -> relativePath = $relativePath;
	}

	/**
	 * Translate
	 *
	 * @param string $content
	 * @return string
	 */
	public function translate($content){

		$content = $this -> t_block($content);
		$content = $this -> t_include($content);
		// $content = $this -> t_array($content);
		$content = $this -> t_if($content);
		$content = $this -> t_for($content);
		$content = $this -> t_print($content);

		return $content;
	}

	/**
	 * Translate block
	 *
	 * @param string $content
	 * @return string
	 */
	public function t_block($content){

		$content = preg_replace('/{{extends ([^\s]*) ([^\}]*)}}/iU','<?php Engine::startExtends("$1","$2"); ?>',$content);
		$content = preg_replace('/{{extends ([^\}]*)}}/iU','<?php Engine::startExtends("$1","$1"); ?>',$content);
		$content = preg_replace('/{{\/extends}}/iU','<?php Engine::endExtends(); ?>',$content);

		$content = preg_replace('/{{parent}}/',"{% parent %}",$content);

		$content = preg_replace('/{{block ([^\}]*)}}/iU',"<?php Engine::startBlock('$1'); ?>",$content);
		$content = preg_replace('/{{\/block}}/',"<?php Engine::endBlock(); ?>",$content);

		return $content;
	}

	/**
	 * Translate Include
	 *
	 * @param string $content
	 * @return string
	 */
	public function t_include($content){

		# Include
		preg_match_all('/{{include ([^\}]*)}}/iU',$content,$r);
		foreach($r[1] as $n => $k){
			$content = str_replace($r[0][$n],'<?php include Engine::getInclude("'.$k.'"); ?>',$content);
		}

		return $content;
	}


	/**
	 * Translate array
	 *
	 * @param string $content
	 * @return string
	 */
	public function t_array($content){

		# array
		preg_match_all('/{{([^\}]*)}}/iU',$content,$r);
		foreach($r[0] as $n => $k){
			$i = preg_replace('/\.([\w]*)/','[\'$1\']',$k);
			$content = str_replace($k,$i,$content);
		}

		return $content;
	}

	/**
	 * Translate for
	 *
	 * @param string $content
	 * @return string
	 */
	public function t_for($content){

		# for 
		preg_match_all('/{{for ([^\}]*) as ([^\}]*)}}/iU',$content,$r);
		
		foreach($r[0] as $n => $k){

			$content = str_replace("{$k}",'<?php foreach((array)'.$r[1][$n].' as '.$r[2][$n].'){ ?>',$content);
		}

		$content = preg_replace('/{{\/for}}/iU','<?php } ?>',$content);

		return $content;
	}

	/**
	 * Translate switch
	 *
	 * @param string $content
	 * @return string
	 */
	public function t_switch($content){

		$content = preg_replace('/{{switch ([^\}]*)}}([^\{]*){{case/iU',"{{switch $1}}\n{{case",$content);
		$content = preg_replace('/{{\/(case)}}([^\{]*){{(case)/iU','{{/case}}'."\n".'{{case',$content);
		$content = preg_replace('/{{\/(case)}}([^\{]*){{\/switch}}/iU','{{/case}}'."\n".'{{/switch}}',$content);

		# switch
		preg_match_all('/{{switch ([^\}]*)}}/iU',$content,$r);
	
		foreach($r[0] as $n => $k){
			$content = str_replace($k,'<?php switch('.$r[1][$n].'){ ?>',$content);
		}

		$content = preg_replace('/{{case default}}/iU','<?php default: ?>',$content);
		preg_match_all('/{{case ([^\} ]*)}}/iU',$content,$r);
	
		foreach($r[0] as $n => $k)
			$content = str_replace($k,'<?php case '.$r[1][$n].': ?>',$content);


		$content = preg_replace(
			[
				'/{{\/switch}}/iU',
				'/{{\/case}}/iU',
			],
			[
				'<?php } ?>',
				'<?php break; ?>',
			],
			$content
		);

		return $content;
	}

	/**
	 * Convert all {{$foo}} into echo $foo
	 *
	 * @param string $content
	 * @return string
	 */
	public function t_if($content){

		# if
		preg_match_all('/{{if ([^\}]*)}}/iU',$content,$r);
	
		foreach($r[0] as $n => $k){
			$content = str_replace($k,'<?php if('.$r[1][$n].'){ ?>',$content);
		}
		
		# else if
		preg_match_all('/{{elseif ([^\} ]*)}}/iU',$content,$r);
	
		foreach($r[0] as $n => $k)
			$content = str_replace($k,'<?php }else if('.$r[1][$n].'){ ?>',$content);


		$content = preg_replace(
			[
				'/{{\/for}}/iU',
				'/{{\/if}}/iU',
				'/{{else}}/iU'
			],
			[
				'<?php } ?>',
				'<?php } ?>',
				'<?php }else{ ?>'
			],
			$content
		);

		return $content;
	}

	/**
	 * Convert all {{$foo}} into echo $foo
	 *
	 * @param string $content
	 * @return string
	 */
	public function t_print($content){

		
		# variables
		preg_match_all('/{{([^\}]*)}}/iU',$content,$r);
		foreach($r[1] as $n => $k){

			# Count row
			preg_match_all('/\n/',explode($k,$content)[0],$r);
			$r = count($r[0])+1;

			$v = preg_replace('/\.([\w]*)/','',$k);

			# Check if defined
			/*if(!in_array($v,self::$contenthecked) && !isset($GLOBALS[$v])){
				$e = new stdClass();
				$e -> message = "Undefined variable {$v}";
				$e -> row = $r;
				$e -> file = basename($f);
				self::$error[] = $e;
			}*/

			$content = str_replace('{{'.$k.'}}','<?php echo '.$k.'; ?>',$content);
		}

		return $content;

	}
}

?>