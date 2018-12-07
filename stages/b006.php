<?php

	$text = preg_replace('/class=MsoNormal/', "", $text);
	$text = preg_replace('/<span *?style=\'font-size: *?18\.0pt; *?font-family: *?"Nudi 01 k"\'>/', "<span class=\"h1\">", $text);
	$text = preg_replace('/<span *?style=\'font-size: *?(11|12|14)\.0pt; *?font-family: *?"Nudi 01 k"\'>/', "<span>", $text);
	$text = preg_replace('/<span *?style=\'font-size: *?16\.0pt; *?font-family: *?"Nudi 01 k"\'>/', "<span class=\"h2\">", $text);			
	$text = preg_replace('/<p *?style=\'text-align:justify;text-indent:36\.0pt\'>/', "<p>", $text);			
	$text = preg_replace('/<p *?class=MsoBodyText2 *?style=\'text-indent:36\.0pt\'>/', "<p>", $text);			
	$text = preg_replace('/<p *?class=MsoBodyText2 *?style=\'text-indent:36\.0pt\'>/', "<p>", $text);			
	$text = preg_replace('/<p *?style=\'margin-right: *?-18.0pt; *?text-align: *?justify; *?text-indent: *?36\.0pt\'>/', "<p>", $text);			
	$text = preg_replace('/<p *?align=center *?style=\'text-align: *?center; *?text-indent: *?36\.0pt\'>/', "<p class=\"text-center\">", $text);			
	$text = preg_replace('/<p><b><span>([0-9]+)\.(.*?)<\/span><\/b><\/p>/', "<h2>$1.$2</h2>", $text);			
	$text = preg_replace('/<p *?style=\'text-align: *?justify; *?text-indent: *?36\.0pt; *?text-autospace: *?none\'>/', "<p>", $text);			
	$text = preg_replace('/<span *?style=\'font-size: *?14\.0pt; *?font-family: *?"Arial",sans-serif\'>/', "<span class=\"en\">", $text);			
	$text = preg_replace('/<span *?style=\'font-size: *?(11|14)\.0pt; *?font-family: *?"Calibri",sans-serif\'>/', "<span class=\"en\">", $text);			
	$text = preg_replace('/<span *?style=\'font-size: *?14\.0pt\'>/', "<span class=\"en\">", $text);
	$text = preg_replace('/<p *?align=center *?style=\'margin-right: *?-(9|18).0pt;text-align: *?center; *?text-indent: *?36.0pt\'>/', "<p class=\"text-center\">", $text);
	$text = preg_replace('/<br *?clear=all *?style=\'page-break-before:always\'>/', "<br />", $text);
	$text = preg_replace('/<p *?class=MsoBodyText3? *?style=\'text-indent: *?36.0pt\'>/', "<p>", $text);
	$text = preg_replace('/<p *?class="text-center"><b><span *?class="h1">(.*?)<\/span><\/b><\/p>/', "<h1>$1</h1>", $text);
	$text = preg_replace('/<p *?class="text-center"><b><span *?class="h2">(.*?)<\/span><\/b><\/p>/', "<h2>$1</h2>", $text);
	
?>
