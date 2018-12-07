<?php

	$text = preg_replace('/class=MsoNormal/', "", $text);			
	$text = preg_replace('/line-height: *150%;?/', "", $text);			
	
	$text = preg_replace('/align=center *style=\'text-align:center;?\'/', "class=\"text-center\"", $text);			
	$text = preg_replace('/align=right style=\'text-align:right;?\'/', "class=\"text-right\"", $text);			
	$text = preg_replace('/style=\'text-align:justify;text-indent:36.0pt;?\'/', "", $text);			

	$text = preg_replace('/<p  *?>/', "<p>", $text);			


	$text = preg_replace('/<span *style=\'font-size:\s*(10|12|14).0pt;\s*font-family:\s*"Nudi 01 (k|e)"\'>/', "<span>", $text);	
	$text = preg_replace('/<span *style=\'font-size:\s*(10).0pt;\s*font-family:\s*"Nudi 01 (k|e)";\s*color:black\'>/', "<span>", $text);	
	$text = preg_replace('/<span *style=\'font-size:\s*(16).0pt;\s*font-family:\s*"Nudi 01 (k|e)"\'>/', "<span class=\"h1\">", $text);
	$text = preg_replace('/<span *style=\'font-size:\s*(18).0pt;\s*font-family:\s*"Nudi 01 (k|e)"\'>/', "<span class=\"h1like\">", $text);
	$text = preg_replace('/<span *style=\'font-size:\s*(18).0pt;\s*font-family:\s*"Nudi 01 (k|e)";\s*color:black\'>/', "<span class=\"h1like\">", $text);
	$text = preg_replace('/<span *style=\'font-size:\s*13.0pt;\s*font-family:\s*"Nudi 01 (k|e)";\s*color:(black|red)\'>/', "<span>", $text);
	$text = preg_replace('/<span *style=\'font-size:\s*13.0pt;\s*color:(black|red)\'>/', "<span>", $text);
	$text = preg_replace('/<span *style=\'font-family:\s*"Nudi 01 (k|e)"\'>/', "<span>", $text);	
	$text = preg_replace('/<span *style=\'font-size:\s*([0-9]+).0pt;\s*font-family:\s*"Arial",sans-serif\'>/i', '<span class="en">', $text);
	$text = preg_replace('/<span *style=\'font-size:\s*([0-9]+).0pt;\s*font-family:\s*"Arial",sans-serif;\s*color:black\'>/i', '<span class="en">', $text);
	$text = preg_replace('/<span *style=\'font-family:\s*"Arial",sans-serif\'>/i', '<span class="en">', $text);
	$text = preg_replace('/<span *style=\'text-decoration:none\'>/i', '<span>', $text);
	$text = preg_replace('/<ol *style=\'margin-top:0cm\' *start=([0-9]+) *type=([0-9]+)>/i', '<ol>', $text);
	$text = preg_replace('/<li *style=\'text-align:justify;\'>/i', '<li>', $text);
		
	$text = preg_replace('/<p *class="text-center"><b><span class="h1">(.*?)<\/span><\/b><\/p>/u', "<h1>$1</h1>", $text);
	$text = preg_replace('/<p *?class="text-center"><b><span>(.*?)<\/span><\/b><\/p>/', "<h2>$1</h2>", $text);			
	$text = preg_replace('/<p *style=\'text-align:justify;?\'>/', "<p>", $text);						
	$text = preg_replace('/<br *clear=all *style=\'page-break-before:always\'>/', "", $text);			
	$text = preg_replace('/<p *style=\'margin-left:[0-9]+.[0-9]+pt;text-align:justify;text-indent: *-?[0-9]+.[0-9]+pt;?\'>/', "<p class=\"li\">", $text);			
	$text = preg_replace('/<span style=\'font:7.0pt *"Times New Roman"\'>/', "<span class=\"en\">", $text);			
	$text = preg_replace('/<p *style=\'margin-left:[0-9]+.0pt;\s*text-align:justify;?\'>/', "<p>", $text);			
	
	$text = preg_replace('/<p><b><u><span>(.*?)<\/span><\/u><\/b><\/p>/', "<h2>$1</h2>", $text);			
	$text = preg_replace('/<br>/', "", $text);			
	$text = preg_replace('/<p><span> *?<\/span><\/p>/i', "", $text);
		
?>
