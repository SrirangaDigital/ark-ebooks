<?php

	$text = preg_replace('/class=MsoNormal/', "", $text);			
	$text = preg_replace('/<p *style=\'text-align:justify\'>/', "<p>", $text);
	$text = preg_replace('/style=\'font-size: *?(5|12|13|14|16|20).0pt; *?font-family: *?"Nudi 01 k"\'/', "", $text);
	$text = preg_replace('/style=\'font-size: *?(5|12|13|14|16|20).0pt; *?font-family: *?"Nudi Akshara-01"\'/', "", $text);
	$text = preg_replace('/style=\'font-size: *?(5|12|13|14|16|20).0pt; *?font-family: *?"Nudi B-Akshara"\'/', "", $text);
	$text = preg_replace('/<span *?style=\'font-family: *?"Nudi 01 k"\'>/', "<span>", $text);
	$text = preg_replace('/<span *?style=\'font-size: *?(5|12|13|14|16|20).0pt\'>/', "<span>", $text);
	$text = preg_replace('/<p *?style=\'text-align:justify;border:none;padding:0cm\'>/', "<p>", $text);
	$text = preg_replace('/<table .*?>/', "<table>", $text);
	$text = preg_replace('/<td .*?>/', "<td>", $text);
	$text = preg_replace('/<p *?align=right *?style=\'text-align:right\'>/', "<p class=\"text-right\">", $text);
	$text = preg_replace('/<p *?align=center *?style=\'text-align:center\'>/', "<p class=\"text-center\">", $text);
	$text = preg_replace('/<span *?style=\'font-size: *?13.0pt; *?font-family:"Roman",serif\'>/', "<span class=\"en\">", $text);						
	$text = preg_replace('/<p *?class=MsoBodyText>/', "<p>", $text);						
	$text = preg_replace('/<p *?>/', "<p>", $text);
	$text = preg_replace('/<span *?>/', "<span>", $text);					
	$text = preg_replace('/<span> *?<\/span>/', "", $text);
	
?>
