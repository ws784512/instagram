<?php

	$url="https://api.instagram.com/v1/users/self/media/recent/?access_token={ACCESS TOKEN GOES HERE} ";
	$raw = file_get_contents($url);
	$data = json_decode($raw,true);

	$arrCount = count($data);
	
	$dataArr=$data['data'];
	
	$f = fopen("/var/includes/instagram/instagram_includes.php", "w") or die("Unable to open file!");
	
	fwrite($f,"<div class=\"row-fluid\">");
	
	
	
	for($i=0;$i<=2;$i++){
		
		$iStr = $dataArr[$i]['images']['low_resolution']['url'];
		$nStr = substr($iStr,(strrpos($iStr,"/"))+1,strlen($iStr));
		$tStr = $dataArr[$i]['caption']['text'];
		$link = $dataArr[$i]['link'];
		$lInt = $dataArr[$i]['likes']['count'];
		$cInt = $dataArr[$i]['comments']['count'];
		
		copy($iStr,"/var/www/public/local/archives-instagram/$nStr");
		
		$pattern='/\[#alttext (.+)\]/';
		preg_match($pattern,$tStr,$matches,PREG_OFFSET_CAPTURE);
		
		if(isset($matches[1][0])){
			$aStr=$matches[1][0];
			if(strlen($tStr)>130){
				$tStr=substr($tStr,0,130);
				$tStr .= "...";
			}
			
			fwrite($f,"<div class=\"col-md-4\">");
			fwrite($f,"<div class=\"i-container\">");
			fwrite($f,"<div class=\"i-image\">");
			fwrite($f,"<img src=\"/local/archives-instagram/$nStr\" alt=\"$aStr\" />");
			fwrite($f,"<div class=\"i-text\">$tStr</div>");
			fwrite($f,"<div class=\"i-comments\">");
			if($lInt>0 || $cInt>0){
				if($lInt>0){
					fwrite($f,"<img src=\"http://www.lib.umt.edu/local/instagram_like.png\" alt=\"heart icon\" />");
					fwrite($f,$lInt);
					fwrite($f,"&nbsp;&nbsp;");
				}
					
				if($cInt>0){
					fwrite($f,"<img src=\"http://www.lib.umt.edu/local/instagram_comment.png\" alt=\"comment icon\" />");
					fwrite($f,$cInt);
				}
			}
			fwrite($f,"</div>");
			fwrite($f,"<div class=\"i-logo\"><a href=\"$link\"><img src=\"http://www.lib.umt.edu/local/instagram-glyph.png\" alt=\"instagram logo\" /></a></div>");
			fwrite($f,"</div>");
			fwrite($f,"</div>");
			fwrite($f,"</div>");
		}
		
	}
	
	fwrite($f,"</div>");
	fclose($f);
?>