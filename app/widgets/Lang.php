<?php
namespace app\widgets;

class Lang
{
	/**
	 * 
	 * @param array $langs
	 * @example <p>
	 * langs = [ [lang_ids], active_lang_id ]
	 * </p>
	 * @return string
	 */
	public static function render($langs)
	{
		$inactive = 'opacity: 0.4; filter: alpha(opacity=40);';
		$width = 40;
		$count = 0;
		$s = '';
		foreach ($langs[0] as $lang_id) {
			$s .=
				'<img class="language-selector" data-language="'.$lang_id.'" src="assets/'.$lang_id.'.png" style="cursor:pointer; width: 40px; position: absolute; top: 6px; left: '.(count($langs[0])+60-$count*$width).'px; '.
			(($lang_id != $langs[1]) ? $inactive : '').'"/>';
			$count++;
		}
		return $s;
	}
}