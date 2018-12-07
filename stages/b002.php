<?php

	$text = preg_replace('/class=MsoNormal/', "", $text);			
	$text = preg_replace('/line-height: *?150%;?/', "", $text);			
	$text = preg_replace('/<p *?align=center *?style=\'text-align:center;?\'>/', "<p class=\"text-center\">", $text);
	$text = preg_replace('/<p *?class="text-center"><b><span *style=\'font-size: *?16.0pt; *?font-family: *?"Nudi 01 k"\'>(.*?)<\/span><\/b><\/p>/', "<h1>$1</h1>", $text);
	$text = preg_replace('/<p *?style=\'text-align:justify;?\'>/', "<p>", $text);			
	$text = preg_replace('/style=\'font-family: *?"Nudi 01 k"\'/', "", $text);						
	$text = preg_replace('/<p *?style=\'text-align: *?justify; *?text-indent: *?36.0pt;?\'>/', "<p>", $text);
	$text = preg_replace('/<span *?>/', "<span>", $text);
	$text = preg_replace('/<span>([0-9]+)\./', "<span class=\"h2\">$1.", $text);
	
?>
