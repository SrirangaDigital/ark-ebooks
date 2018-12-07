<?php

	$text = preg_replace('/class=MsoNormal/', "", $text);			
	$text = preg_replace('/line-height: *?150%;?/', "", $text);			
	$text = preg_replace('/style=\'font-size:28.0pt; *?font-family: *?"Nudi 01 k"\'/', "", $text);			
	$text = preg_replace('/style=\'font-family: *?"Nudi 01 k"\'/', "", $text);			
	$text = preg_replace('/<p *?align=center *?style=\'text-align:center;?\'>/', "<p class=\"text-center\">", $text);			
	$text = preg_replace('/<p *?style=\'text-align:justify;?\'>/', "<p>", $text);			
	$text = preg_replace('/<p *?style=\'text-align: *?justify; *?text-indent: *?36.0pt;?\'>/', "<p>", $text);			
	$text = preg_replace('/<br clear=all style=\'page-break-before:always\'>/', "", $text);			
	$text = preg_replace('/<p class="text-center"><b><span *?style=\'font-size: *?16.0pt; *?font-family: *?"Nudi 01 k"\'>(.*?)<\/span><\/b><\/p>/', "<h1>$1</h1>", $text);			
	$text = preg_replace('/<p *?align=right *?style=\'text-align: *?right;?\'>/', "<p class=\"text-right\">", $text);			
	$text = preg_replace('/<span *?style=\'font-size: *?12.0pt; *?font-family: *?"Nudi 01 k"\'> *?<\/span>/', "", $text);			
	$text = preg_replace('/<span *?style=\'font-size: *?10.0pt; *?font-family: *?"Arial",sans-serif\'>/', "<span class=\"en\">", $text);			
	$text = preg_replace('/<span *?>/', "<span>", $text);			
	
?>
