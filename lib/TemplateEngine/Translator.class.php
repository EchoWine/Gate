<?php

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
		preg_match_all('/{{extends ([^\}]*)}}/iU',$content,$r);

		foreach($r[1] as $n => $file){

			$source = TemplateEngine::getSourceFile($file);
			$content = $this -> t_blockBySource($source,$content);
			$content = preg_replace('/{{extends '.$file.'}}/iU','',$content);

		}

		# Remove blocks
		while(preg_match("/{{block ([^\}]*)}}((((?R)|.|\n)*)){{\/block}}/iU",$content))
			$content = preg_replace("/{{block ([^\}]*)}}((((?R)|.|\n)*)){{\/block}}/iU","$2",$content);

		return $content;
	}

	/**
	 * Replace extend block to parent block
	 *
	 * @param string $source parent source
	 * @param string $content extends content
	 * @return string
	 */
	public function t_blockBySource($source,$content){
		preg_match_all("/{{block ([^\}]*)}}((((?R)|.|\n)*)){{\/block}}/iU",$content,$r);

		$source_c = $this -> t_blockBySourceRecursive($source);


		foreach($r[1] as $n => $k){
			if(isset($source_c[$k])){
				$source = str_replace($source_c[$k][0],'{{block '.$r[1][$n].'}}'.$r[2][$n].'{{/block}}',$source);
				$source = preg_replace("/{{parent}}/iU",$source_c[$k][2],$source);
			}
		}

		return $source;
	}

	public function t_blockBySourceRecursive($source){

		preg_match_all("/{{block ([^\}]*)}}((((?R)|.|\n)*)){{\/block}}/iU",$source,$result);

		$return = [];
		foreach($result[2] as $n => $res){
			$t = $this -> t_blockBySourceRecursive($res);
			$return[$result[1][$n]] = [$result[0][$n],$result[1][$n],$result[2][$n]];
			$return = array_merge($return,$t);
		}

		return $return;
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
			$content = str_replace($r[0][$n],'<?php include TemplateEngine::getInclude("'.$k.'"); ?>',$content);
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