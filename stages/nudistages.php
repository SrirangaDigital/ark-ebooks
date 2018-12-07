<?php

class Stages{

	public function __construct() {
		
	}

	public function processFiles($bookID) {


		$allFiles = $this->getAllFiles($bookID);

		var_dump($allFiles);

		foreach($allFiles as $file){
	
			$this->process($bookID,$file);		
		}
	
	}

	public function getAllFiles($bookID) {

		$allFiles = [];
		
		$folderPath = RAW_SRC . $bookID . '/Stage1/';
		
	    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath));

	    foreach($iterator as $file => $object) {
	    	
	    	if(preg_match('/.*\.htm[l]?$/',$file)) array_push($allFiles, $file);
	    }

	    sort($allFiles);

		return $allFiles;
	}


	public function process($bookID,$file) {

		// stage1.html : Input html from adobe acrobat
		$rawHTML = file_get_contents($file);

		// Process html to strip off unwanted tags and elements
		$processedHTML = $this->processRawHTML($bookID,$rawHTML);

		// stage2.html : Output html for conversion		
		$baseFileName = basename($file);

		if (!file_exists(RAW_SRC . $bookID . '/Stage2/')) {
			mkdir(RAW_SRC . $bookID . '/Stage2/', 0775);
			echo "Stage2 directory created\n";
		}

		$fileName = RAW_SRC . $bookID . '/Stage2/' . $baseFileName;

		// $processedHTML = html_entity_decode($processedHTML, ENT_QUOTES);
		file_put_contents($fileName, $processedHTML);

		// Convert nudi/baraha data to Unicode retaining html tags
	
		$unicodeHTML = $this->parseHTML($processedHTML);

		$unicodeHTML = preg_replace("/><p>/i", ">\n<P>", $unicodeHTML);
		$unicodeHTML = preg_replace("/<\/p></i", "</P>\n<", $unicodeHTML);

		// stage3.html : Output Unicode html with tags, english retained as it is
		if (!file_exists(RAW_SRC . $bookID . '/Stage3a/')) {
			mkdir(RAW_SRC . $bookID . '/Stage3a/', 0775);
			echo "Stage3a directory created\n";
		}

		$fileName = RAW_SRC . $bookID . '/Stage3a/' . $baseFileName;	
			
		$unicodeHTML = html_entity_decode($unicodeHTML);

		file_put_contents($fileName, $unicodeHTML);

		if(file_exists(RAW_SRC . $bookID . '/Stage3/' . $baseFileName)) {

			$unicodeHTML = preg_replace('/<sup>.*?<\/sup>/i', ' ', $unicodeHTML);
			$strippedHTML = strip_tags($unicodeHTML);
			// new file normalizations
			$strippedHTML = str_replace('.', '. ', $strippedHTML);
			$strippedHTML = preg_replace('/\s+/', ' ', $strippedHTML);
			$strippedHTML = preg_replace('/ /', "\n", $strippedHTML);
			$strippedHTML = str_replace('–', '-', $strippedHTML);

			$fileNameAfter = RAW_SRC . $bookID . '/Stage3a/' . $baseFileName . '.after.txt';	
			file_put_contents($fileNameAfter, $strippedHTML);

			$oldHTML = file_get_contents(RAW_SRC . $bookID . '/Stage3/' . $baseFileName);

			// remove 200c character
			$oldHTML = str_replace('‌', '', $oldHTML);
			file_put_contents(RAW_SRC . $bookID . '/Stage3/' . $baseFileName, $oldHTML);

			$oldHTML = preg_replace('/<sup>.*?<\/sup>/i', ' ', $oldHTML);
			$oldHTML = strip_tags($oldHTML);
			$oldHTML = preg_replace('/\s+/', ' ', $oldHTML);
			$oldHTML = preg_replace('/ /', "\n", $oldHTML);

			$fileNameBefore = RAW_SRC . $bookID . '/Stage3a/' . $baseFileName . '.before.txt';	
			file_put_contents($fileNameBefore, $oldHTML);

			$fileNameDiff = RAW_SRC . $bookID . '/Stage3a/' . $baseFileName . '.diff';	
			exec('diff ' . $fileNameBefore . ' ' . $fileNameAfter . ' > ' . $fileNameDiff);
			exec('rm ' . $fileNameBefore);
			exec('rm ' . $fileNameAfter);
		}
	}

	public function parseHTML($html) {

		$dom = new DOMDocument("1.0");
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;

		$dom->loadXML($html);
		$xpath = new DOMXpath($dom);

		foreach($xpath->query('//text()') as $text_node) {

			if(preg_replace('/\s+/', '', $text_node->nodeValue) === '') continue; 

			if($text_node->parentNode->hasAttribute('class'))
				if($text_node->parentNode->getAttribute('class') == 'en'){
					
					$text_node->nodeValue = preg_replace('/`(.*?)\'/',"‘$1’",$text_node->nodeValue);
					$text_node->nodeValue = preg_replace('/"(.*?)"/',"“$1”",$text_node->nodeValue);
					$text_node->nodeValue = preg_replace('/‘‘(.*?)"/',"“$1”",$text_node->nodeValue);					 
					continue;
				 }

			// echo $text_node->nodeValue . "\n";		
			$text_node->nodeValue = $this->nudi2Unicode($text_node->nodeValue);
			$text_node->nodeValue = preg_replace('/"(.*?)"/',"“$1”",$text_node->nodeValue);
			$text_node->nodeValue = preg_replace('/‘‘(.*?)"/',"“$1”",$text_node->nodeValue);
			 // echo $text_node->nodeValue . "\n";		

		}

		return $dom->saveXML();
	}

	public function processRawHTML($bookID,$text) {
		
		$text = preg_replace('/ lang=EN-IN/', "", $text);
		$text = preg_replace('/ *lang=EN-US */', " ", $text);
		$text = preg_replace('/<div class=WordSection1>/', "", $text);
		$text = preg_replace('/<!--.*/', "", $text);
		$text = preg_replace('/`/', "`", $text);
		$text = preg_replace('/“/', '``', $text);
		$text = preg_replace('/”/', '"', $text);
		$text = preg_replace('/’/', "'", $text);
		$text = preg_replace('/‘/', "`", $text);

		$text = str_replace("\n\n", "\n", $text);		
		$text = str_replace("\n", " ", $text);		
		$text = str_replace("\r", "", $text);

		$text = preg_replace('/&([^a-zA-Z])/', "&amp;$1", $text);

		//~ $text = preg_replace('/(<[a-zA-Z])/', "\n$1", $text);
		$text = preg_replace('/<p>/', "\n<p>", $text);
		$text = preg_replace('/<p /', "\n<p ", $text);
		$text = preg_replace('/<\/p>/', "</p>\n", $text);
		$text = preg_replace('/<meta /', "\n<meta ", $text);
		$text = preg_replace('/<title>/', "\n<title>", $text);
		$text = preg_replace('/<style>/', "\n<style>", $text);
		$text = preg_replace('/<div /', "\n<div ", $text);
		$text = preg_replace('/<\/div>/', "\n</div>", $text);
		$text = preg_replace('/<body /', "\n<body ", $text);
		$text = preg_replace('/<\/body>/', "\n</body>", $text);
		$text = preg_replace('/<head>/', "\n<head>", $text);
		$text = preg_replace('/<\/head>/', "\n</head>", $text);
		$text = preg_replace('/<\/html>/', "\n</html>", $text);

		include 'b' . $bookID . '.php';	
	
		$text = preg_replace('/<b>/i', '<strong>', $text);
		$text = preg_replace('/<\/b>/i', '</strong>', $text);
		$text = preg_replace('/<i>/i', '<em>', $text);
		$text = preg_replace('/<\/i>/i', '</em>', $text);
		

		$text = preg_replace("/ \n/", "\n", $text);
		$text = preg_replace("/\n{2}/", "\n", $text);
		
		$text = str_replace("&nbsp;", " ", $text);	
		$text = str_replace("\xc2\xa0", "", $text);			
		$text = preg_replace("/<\/div> *\n<\/body>/", "\n</body>", $text);

		//~ $text = preg_replace('/ */', " ", $text);

		return trim($text);
	}

	public function nudi2Unicode($text) {

		// ya group
		$text = str_replace('AiÀiï', 'ಯ್​', $text);
		$text = str_replace('AiÀÄ', 'ಯ', $text);
		$text = str_replace('AiÀiÁ', 'ಯಾ', $text);
		$text = str_replace('¬Ä', 'ಯಿ', $text);
		$text = str_replace('AiÉÄ', 'ಯೆ', $text); 
		$text = str_replace('AiÉÆ', 'ಯೊ', $text);
		$text = str_replace('AiÀiË', 'ಯೌ', $text);
		
		//ma group
		$text = str_replace('ªÀiï', 'ಮ್', $text);
		$text = str_replace('ªÀiË', 'ಮೌ', $text);
		$text = str_replace('ªÀÄ', 'ಮ', $text);
		$text = str_replace('ªÀiÁ', 'ಮಾ', $text);
		$text = str_replace('ªÉÄ', 'ಮೆ', $text);
		$text = str_replace('ªÉÆ', 'ಮೊ', $text);
		$text = str_replace('«Ä', 'ಮಿ', $text);
		
		// jjha group
		$text = str_replace('gÀhÄ', 'ಝ', $text);
		$text = str_replace('gÀhiÁ', 'ಝಾ', $text);
		$text = str_replace('gÉhÄ', 'ಝೆ', $text);
		$text = str_replace('gÉhÆ', 'ಝೊ', $text);
		$text = str_replace('jhÄ', 'ಝಿ', $text);

		//dha group
		$text = str_replace('zs', 'ಧ್', $text);
		$text = str_replace('¢ü', 'ಧಿ', $text);

		//Dha group
		$text = str_replace('qs', 'ಢ್', $text);
		$text = str_replace('rü', 'ಢಿ', $text);
		
		//pha group
		$text = str_replace('¥s', 'ಫ್', $text);
		$text = str_replace('¦ü', 'ಫಿ', $text);

		//ha group
		$text = str_replace('¨s', 'ಭ್', $text);
		$text = str_replace('©ü', 'ಭಿ', $text);

		// RRi group
		$text = str_replace('IÄ', 'ಋ', $text);
		$text = str_replace('IÆ', 'ೠ', $text);

		// Lookup ---------------------------------------------
		$text = str_replace('!', '!', $text);
		$text = str_replace('"', '"', $text);// tbh
		$text = str_replace('#', '#', $text);
		$text = str_replace('$', '$', $text);
		$text = str_replace('%', '%', $text);
		$text = str_replace('&', '&', $text);
		$text = str_replace("'", '’', $text);
		$text = str_replace('(', '(', $text);
		$text = str_replace(')', ')', $text);
		$text = str_replace('*', '*', $text);
		$text = str_replace('+', '+', $text);
		$text = str_replace(',', ',', $text);
		$text = str_replace('-', '-', $text);
		$text = str_replace('.', '.', $text);
		$text = str_replace('/', '/', $text);
		$text = str_replace('0', '೦', $text);
		$text = str_replace('1', '೧', $text);
		$text = str_replace('2', '೨', $text);
		$text = str_replace('3', '೩', $text);
		$text = str_replace('4', '೪', $text);
		$text = str_replace('5', '೫', $text);
		$text = str_replace('6', '೬', $text);
		$text = str_replace('7', '೭', $text);
		$text = str_replace('8', '೮', $text);
		$text = str_replace('9', '೯', $text);
		$text = str_replace(':', ':', $text);
		$text = str_replace(';', ';', $text);
		$text = str_replace('<', '<', $text);
		$text = str_replace('=', '=', $text);
		$text = str_replace('>', '>', $text);
		$text = str_replace('?', '?', $text);
		$text = str_replace('@', '@', $text);
		$text = str_replace('A', 'ಂ', $text);
		$text = str_replace('B', 'ಃ', $text);
		$text = str_replace('C', 'ಅ', $text);
		$text = str_replace('D', 'ಆ', $text);
		$text = str_replace('E', 'ಇ', $text);
		$text = str_replace('F', 'ಈ', $text);
		$text = str_replace('G', 'ಉ', $text);
		$text = str_replace('H', 'ಊ', $text);
		//~ $text = str_replace('I', '', $text); //handled above in RRi group
		$text = str_replace('J', 'ಎ', $text);
		$text = str_replace('K', 'ಏ', $text);
		$text = str_replace('L', 'ಐ', $text);
		$text = str_replace('M', 'ಒ', $text);
		$text = str_replace('N', 'ಓ', $text);
		$text = str_replace('O', 'ಔ', $text); 
		$text = str_replace('P', 'ಕ್', $text);
		$text = str_replace('Q', 'ಕಿ', $text);
		$text = str_replace('R', 'ಖ', $text);
		$text = str_replace('S', 'ಖ್', $text);
		$text = str_replace('T', 'ಖಿ', $text);
		$text = str_replace('U', 'ಗ್', $text);
		$text = str_replace('V', 'ಗಿ', $text);
		$text = str_replace('W', 'ಘ್', $text);
		$text = str_replace('X', 'ಘಿ', $text);
		$text = str_replace('Y', 'ಙ', $text);
		$text = str_replace('Z', 'ಚ್', $text);
		$text = str_replace('[', '[', $text);
		$text = str_replace("\\", '\\', $text);
		$text = str_replace(']', ']', $text);
		$text = str_replace('^', '^', $text);
		$text = str_replace('_', '_', $text);
		$text = str_replace('`', '‘', $text);
		$text = str_replace('a', 'ಚಿ', $text);
		$text = str_replace('b', 'ಛ್', $text);
		$text = str_replace('c', 'ಛಿ', $text);
		$text = str_replace('d', 'ಜ', $text);
		$text = str_replace('e', 'ಜ್', $text);
		$text = str_replace('f', 'ಜಿ', $text);
		$text = str_replace('g', 'ರ್', $text);
		//~ $text = str_replace('h', '', $text); //pre processing (ya Jha)
		//~ $text = str_replace('i', '', $text); //pre processing (ya Jha)
		$text = str_replace('j', 'ರಿ', $text);
		$text = str_replace('k', 'ಞ', $text); // pre processing
		$text = str_replace('l', 'ಟ', $text); 
		$text = str_replace('m', 'ಟ್', $text);
		$text = str_replace('n', 'ಟಿ', $text); 
		$text = str_replace('o', 'ಠ್', $text);
		$text = str_replace('p', 'ಠಿ', $text);
		$text = str_replace('q', 'ಡ್', $text);
		$text = str_replace('r', 'ಡಿ', $text);
		//~ $text = str_replace('s', '', $text); //pre processing
		$text = str_replace('t', 'ಣ', $text);
		$text = str_replace('u', 'ಣ್', $text);
		$text = str_replace('v', 'ತ್', $text);
		$text = str_replace('w', 'ತಿ', $text);
		$text = str_replace('x', 'ಥ್', $text);
		$text = str_replace('y', 'ಥಿ', $text);
		$text = str_replace('z', 'ದ್', $text);
		$text = str_replace('{', '{', $text);
		$text = str_replace('|', '|', $text); 
		$text = str_replace('}', '}', $text);
		$text = str_replace('~', '~', $text);
		// $text = str_replace(' ', '', $text); // tbh (no break space)
		$text = str_replace('¢', 'ದಿ', $text);
		$text = str_replace('£', 'ನ್', $text);
		$text = str_replace('¤', 'ನಿ', $text);
		$text = str_replace('¥', 'ಪ್', $text);
		$text = str_replace('¦', 'ಪಿ', $text);
		$text = str_replace('§', 'ಬ', $text);
		$text = str_replace('¨', 'ಬ್', $text);
		$text = str_replace('©', 'ಬಿ', $text);
		$text = str_replace('ª', 'ವ್', $text);
		$text = str_replace('«', 'ವಿ', $text);
		//~ $text = str_replace('¬', '', $text); //handled above in ya group (yi)
		$text = str_replace('®', 'ಲ', $text);
		$text = str_replace('¯', 'ಲ್', $text);
		$text = str_replace('°', 'ಲಿ', $text);
		$text = str_replace('±', 'ಶ್', $text);
		$text = str_replace('²', 'ಶಿ', $text);
		$text = str_replace('µ', 'ಷ್', $text);
		$text = str_replace('¶', 'ಷಿ', $text);
		$text = str_replace('¸', 'ಸ್', $text);
		$text = str_replace('¹', 'ಸಿ', $text);
		$text = str_replace('º', 'ಹ್', $text);
		$text = str_replace('»', 'ಹಿ', $text);
		$text = str_replace('¼', 'ಳ್', $text);
		$text = str_replace('½', 'ಳಿ', $text);
		$text = str_replace('¾', 'ಱ', $text);
		$text = str_replace('¿', 'ೞ', $text);
		$text = str_replace('À', 'ಅ', $text); // replacing a kara to swara 'a'
		$text = str_replace('Á', 'ಾ', $text);//kA
		$text = str_replace('Â', 'ಿ', $text);//ki
		$text = str_replace('Ä', 'ು', $text);//ku
		$text = str_replace('Å', 'ು', $text);//ku
		$text = str_replace('Æ', 'ೂ', $text);//kU
		$text = str_replace('Ç', 'ೂ', $text);//kU
		$text = str_replace('È', 'ೃ', $text);//kaq
		$text = str_replace('É', 'ೆ', $text);//ke
		$text = str_replace('Ê', 'ೈ', $text);//kai
		$text = str_replace('Ë', 'ೌ', $text);
		$text = str_replace('Ì', '್ಕ', $text);
		$text = str_replace('Í', '್ಖ', $text);
		$text = str_replace('Î', '್ಗ', $text);
		$text = str_replace('Ï', '್ಘ', $text);
		$text = str_replace('Ð', '್ಙ', $text);
		$text = str_replace('Ñ', '್ಚ', $text);
		$text = str_replace('Ò', '್ಛ', $text);
		$text = str_replace('Ó', '್ಜ', $text);
		$text = str_replace('Ô', '್ಝ', $text);
		$text = str_replace('Õ', '್ಞ', $text);
		$text = str_replace('Ö', '್ಟ', $text);
		$text = str_replace('×', '್ಠ', $text);
		$text = str_replace('Ø', '್ಡ', $text);
		$text = str_replace('Ù', '್ಢ', $text);
		$text = str_replace('Ú', '್ಣ', $text);
		$text = str_replace('Û', '್ತ', $text);
		$text = str_replace('Ü', '್ಥ', $text);
		$text = str_replace('Ý', '್ದ', $text);
		$text = str_replace('Þ', '್ಧ', $text);
		$text = str_replace('ß', '್ನ', $text);
		$text = str_replace('à', '್ಪ', $text);
		$text = str_replace('á', '್ಫ', $text);
		$text = str_replace('â', '್ಬ', $text);
		$text = str_replace('ã', '್ಭ', $text);
		$text = str_replace('ä', '್ಮ', $text);
		$text = str_replace('å', '್ಯ', $text);
		$text = str_replace('æ', '್ರ', $text);
		$text = str_replace('ç', '್ರ', $text);
		$text = str_replace('è', '್ಲ', $text);
		$text = str_replace('é', '್ವ', $text);
		$text = str_replace('ê', '್ಶ', $text);
		$text = str_replace('ë', '್ಷ', $text);
		$text = str_replace('ì', '್ಸ', $text);
		$text = str_replace('í', '್ಹ', $text);
		$text = str_replace('î', '್ಳ', $text);
		$text = str_replace('ï', '್​', $text);
		$text = str_replace('ð', 'ð', $text);//arka vottu
		$text = str_replace('ñ', 'ೄ', $text);
		$text = str_replace('ò', 'ನ್​', $text);
		$text = str_replace('ó', '಼', $text);
		$text = str_replace('ô', 'ô', $text);//tbh
		$text = str_replace('õ', 'õ', $text);//tbh
		$text = str_replace('ø', 'ೃ', $text);
		$text = str_replace('ù', '್ಱ', $text);
		$text = str_replace('ú', '್ೞ', $text);
		$text = str_replace('û', '಼', $text);
		//~ $text = str_replace('ü', '', $text);//tbh
		$text = str_replace('ý', 'ಽ', $text);
		//~ $text = str_replace('Œ', '', $text);//tbh
		//~ $text = str_replace('Š', '', $text);//tbh
		//~ $text = str_replace('¯', '', $text);//tbh
		$text = str_replace('‘', '‘', $text);
		$text = str_replace('’', '’', $text);
		$text = str_replace('“', '“', $text);
		$text = str_replace('”', '”', $text);
		$text = str_replace('„', 'ಽ', $text);
		$text = str_replace('†', '।', $text);
		$text = str_replace('‡', '॥', $text);
		//~ $text = str_replace('‰', '', $text);//tbh
		//~ $text = str_replace('‹', '', $text);//tbh

		// Special cases

		//remove ottu spacer
		$text = str_replace('ö', '', $text);//ottu spacer
		$text = str_replace('÷', '', $text);//ottu spacer


		// Swara
		$text = preg_replace('/್[ಅ]/u', '', $text);
		$text = preg_replace('/್([ಾಿೀುೂೃೄೆೇೈೊೋೌ್])/u', "$1", $text);

		$text = str_replace('ೊ', 'ೊ', $text);//ko
		$text = str_replace('ೆೈ', 'ೈ', $text);//kai

		$swara = "ಅ|ಆ|ಇ|ಈ|ಉ|ಊ|ಋ|ಎ|ಏ|ಐ|ಒ|ಓ|ಔ";
		$vyanjana = "ಕ|ಖ|ಗ|ಘ|ಙ|ಚ|ಛ|ಜ|ಝ|ಞ|ಟ|ಠ|ಡ|ಢ|ಣ|ತ|ಥ|ದ|ಧ|ನ|ಪ|ಫ|ಬ|ಭ|ಮ|ಯ|ರ|ಱ|ಲ|ವ|ಶ|ಷ|ಸ|ಹ|ಳ|ೞ";
		$swaraJoin = "ಾ|ಿ|ೀ|ು|ೂ|ೃ|ೄ|ೆ|ೇ|ೈ|ೊ|ೋ|ೌ|ಂ|ಃ|್";

		$syllable = "($vyanjana)($swaraJoin)|($vyanjana)($swaraJoin)|($vyanjana)|($swara)";

		$text = preg_replace("/($swaraJoin)್($vyanjana)/u", "್$2$1", $text);
		$text = preg_replace("/್​್($vyanjana)/u", "್$1್​", $text);


		$text = str_replace('ೊ', 'ೊ', $text);//ko
		$text = str_replace('ೆೈ', 'ೈ', $text);//kai

		$text = str_replace('ಿÃ', 'ೀ', $text);//kiV
		$text = str_replace('ೆÃ', 'ೇ', $text);//keV
		$text = str_replace('ೊÃ', 'ೋ', $text);//koV		
		
		$text = str_replace('್​ð', '್ð', $text);//halanta+zwj+R = halanta+R
		

		$text = preg_replace("/($swaraJoin)್($vyanjana)/u", "್$2$1", $text);
		
		// First pass of repha inversion
		$text = preg_replace("/($syllable)/u", "$1zzz", $text);
		$text = preg_replace("/್zzz/u", "್", $text);
		$text = preg_replace("/್ð/u", "್zzzð", $text);
		$text = preg_replace("/zzz([^z]*?)zzzð/u", "zzzರ್zzz" . "$1", $text);
		$text = str_replace("zzz", "", $text);

		$text = str_replace('ೊ', 'ೊ', $text);//ko
		$text = str_replace('ೆೈ', 'ೈ', $text);//kai

		$text = str_replace('ಿÃ', 'ೀ', $text);//kiV
		$text = str_replace('ೆÃ', 'ೇ', $text);//keV
		$text = str_replace('ೊÃ', 'ೋ', $text);//koV		

		// Second pass of repha inversion
		$text = preg_replace("/($syllable)/u", "$1zzz", $text);
		$text = preg_replace("/್zzz/u", "್", $text);
		$text = preg_replace("/್ð/u", "್zzzð", $text);
		$text = preg_replace("/zzz([^z]*?)zzzð/u", "zzzರ್zzz" . "$1", $text);
		$text = str_replace("zzz", "", $text);

		$text = str_replace('ೊ', 'ೊ', $text);//ko
		$text = str_replace('ೆೈ', 'ೈ', $text);//kai

		$text = str_replace('ಿÃ', 'ೀ', $text);//kiV
		$text = str_replace('ೆÃ', 'ೇ', $text);//keV
		$text = str_replace('ೊÃ', 'ೋ', $text);//koV	

		$text = str_replace('ಿ Ã', 'ೀ', $text);//kiV
		$text = str_replace('ೆ Ã', 'ೇ', $text);//keV
		$text = str_replace('ೊ Ã', 'ೋ', $text);//koV	

		// Final replacements
		$text = str_replace(' ್', '್', $text);
		$text = str_replace('||', '॥', $text);
		$text = str_replace('|', '।', $text);
		
		$text = str_replace('<', '&lt;', $text);
		$text = str_replace('>', '&gt;', $text);

		//~ $text = preg_replace('/’(.*?)’/', '‘$1’', $text);
		
		// echo $result . "\n"; 
		return $text;
	}
}

?>
