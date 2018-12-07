<?php

	$text = preg_replace('/<span *style=\'font-size:\s*(6|7|8|9|10|11|12|13|14|18|20|26).0pt;\s*font-family:\s*"Nudi 01 (k|e)"\'>/', "<span>", $text);
	$text = preg_replace('/<span *style=\'font-family:\s*"Nudi 01 (k|e)"\'>/', "<span>", $text);
	$text = preg_replace('/<span *style=\'font-size:\s*16.0pt;\s*font-family:\s*"Nudi 01 (k|e)"\'>/', "<span class=\"h1\">", $text);
	$text = preg_replace('/<span *style=\'font-family:Wingdings\'>/', "<span class=\"fnt-symbol\">", $text);
	$text = preg_replace('/<span *style=\'font-size:11.0pt;font-family:Wingdings\'>/', "<span class=\"fnt-symbol\">", $text);
	
	$text = preg_replace('/<p class=(MsoNormal|MsoBodyText) align=center style=\'text-align:center\'>/', "<p class=\"text-center\">", $text);
	$text = preg_replace('/<p class=MsoNormal style=\'margin-left:(.*?)pt;text-align:justify;text-indent: (.*?)pt\'>/', "<p class=\"verse\">", $text);
	$text = preg_replace('/<p class=MsoNormal style=\'text-align:justify\'>/', "<p>", $text);
	$text = preg_replace('/<p class=MsoBodyText>/', "<p>", $text);
	$text = preg_replace('/<p class=MsoNormal style=\'margin-left:1.5in;text-align:justify\'>/', "<p class=\"verse\">", $text);
	$text = preg_replace('/<p class=MsoNormal style=\'margin-left:1.5in;text-align:justify;text-indent: -.5in\'>/', "<p class=\"verse\">", $text);
	$text = preg_replace('/<p><b><span style=\'font-size:(10|11).0pt;font-family:"Arial","sans-serif"\'>([0-9]+)/', "<p class=\"footnote\"><b><span>$2", $text);
	$text = preg_replace('/<p style=\'margin-left:108.0pt;text-align:justify\'>/', "<p class=\"verse\">", $text);

	$text = str_replace('class=MsoNormal ', "", $text);
	$text = str_replace('class=MsoBodyText ', "", $text);

	$text = preg_replace('/<p style=.*?><b><span style=\'font-size:(10|11).0pt;font-family:"Arial","sans-serif"\'>([0-9]+)/', "<p class=\"footnote\"><b><span>$2", $text);
	$text = preg_replace('/<p style=\'.*?;text-indent:.*?(pt|in)\'><span>([0-9]+)/', "<p class=\"li\"><span>$2", $text);
	$text = preg_replace('/<p style=\'.*?;text-indent:.*?(pt|in)\'><b><span>([0-9]+)/', "<p class=\"li\"><b><span>$2", $text);

	$text = preg_replace('/<span *style=\'font-size:\s*([0-9]+).0pt;\s*font-family:\s*"Arial",sans-serif\'>/i', '<span class="en">', $text);	
	$text = preg_replace('/<span *style=\'font-family:\s*"Arial","?sans-serif"?\'>/i', '<span class="en">', $text);	
	$text = preg_replace('/<span *style=\'font:[0-9]+.0pt\s*"Times New Roman"\'>/i', '<span class="en">', $text);
	$text = preg_replace('/<span *style=\'font-family:"Times New Roman","?serif"?\'>/i', '<span class="en">', $text);
	$text = preg_replace('/<span *style=\'font-size:\s*([0-9]+).0pt;\s*font-family:\s*"Bookman Old Style",serif\'>/i', '<span class="en">', $text);
	
	$text = preg_replace('/<p style=\'.*?\'><span class="fnt-symbol">/i', '<p class="footnote"><span class="fnt-symbol">', $text);
	$text = preg_replace('/<img width=[0-9]+ height=[0-9]+ src=".*?">/i', '', $text);
	$text = preg_replace('/<br clear=ALL>/i', '', $text);
	$text = preg_replace('/<p>\s*<table(.*?)>(.*?)<\/table>(.*?)<\/p>/i', '<table$1>$2</table>', $text);
	$text = preg_replace('/<(table|tr|td)(.*?)>/i', '<$1>', $text);

	$text = preg_replace('/<p style=\'.*?;text-indent:.*?(pt|in)\'><span class="en">([0-9]+)/', "<p class=\"li\"><span class=\"en\">$2", $text);		
	$text = preg_replace('/<p style=\'.*?;text-indent:.*?(pt|in)\'><span class="en">(\([a-z]+\))/', "<p class=\"li\"><span class=\"en\">$2", $text);		
	$text = preg_replace('/<p style=\'.*?;?text-indent:.*?(pt|in)\'><b><span>(\([0-9]+\))/', "<p class=\"li\"><b><span>$2", $text);		
	$text = preg_replace('/<p style=\'margin-left:.*?pt;text-align:justify\'>/', "<p>", $text);		
	$text = preg_replace('/<p align=center style=\'margin-left:-2.85pt;text-align:center\'>/', "<p class=\"text-center\">", $text);		
	$text = preg_replace('/<b><span class="h1">(.*?)<\/span><\/b><b><span class="en">(.*?)<\/span><\/b>/u', "<b><span>$1</span></b><b><span class=\"en\">$2</span></b>", $text);		
	$text = preg_replace('/<p class="text-center"><b><span class="h1">(.*?)<\/span><\/b><\/p>/u', "<h1>$1</h1>", $text);

?>
