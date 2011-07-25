<?php

$msgs = array();
function full($site) {
    sql($site, 1);
    xss($site, 1);
}



function xss($site = '', $full = '0') {
   
		
        
        $request = parse_url($site);
        $msgs[] = "[-] URL : $request[host]\n";
        $msgs[] = "[-] Path: $request[path]\n";
        $msgs[] = "[-] Try connect to host\n";

        $url = "".$request['scheme']."://".$request['host'].$request['path']."";
        if(con_host($url))
        {
            $msgs[] = "[+] Connect to host successful\n";
            Get_Info($url);
            $msgs[] = "[-] Finding link on the website\n";
            $msgs[] = "[+] Found link : ".count(find_link($url))."\n";
            $msgs[] = "[-] Finding vulnerable...\n";
         
   			if(is_array(find_link($url)))
            foreach(find_link($url) as $link) {
                $file = explode("/", $request['path']);
                $request['path'] = preg_replace("/".$file[count($file)-1]."/", "", $request['path']);
                if(!preg_match("/$request[host]/", $link)) { $link = "http://$request[host]/$request[path]$link"; }
                $link = preg_replace("/=(.+)/", "=<h1>XSS_HERE</h1>", $link);
                if(preg_match("/<h1>XSS_HERE<\/h1>/", con_host($link))) {
                    $msgs[]  = "[+] XSS vulnerable : $link\n";
                    $save[] = $link;
               }
            }
           $msgs[] = "[+] Done\n";
           if(is_array($save)) {
           foreach($save as $link) {
               save_log('vulnerable.log', "".$link."\r\n");
           }}
           print "[+] See 'vulnerable.log' for vulnerable list\n";
            
        } else {
            $msgs[] = "[!] Connect to host failed\n";
        }
print "here";
   }



	function Get_Info($site) {
	    if($info = con_host($site)) {
	        preg_match("/Content-Type:(.+)/", $info, $type);
	        preg_match("/Server:(.+)/", $info, $server);
	        $msgs[] = "[-] $type[0]\n";
	        $msgs[] = "[-] $server[0]\n";
	        $ip = parse_url($site);
	        $msgs[] = "[-] IP: ".gethostbyname($ip['host'])."\n";
	    }
	}
	
	function save_log($fname = '', $text = '') {
	    $file = @fopen(dirname(__FILE__).'/'.$fname.'', 'a');
	    $write = @fwrite($file, $text, '60000000');
	    if($write) {
	        return 1;
	    } else {
	        return 0;
	    }
	}
	
	function con_host($host) {
	    $ch = curl_init($host);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 200);
	    curl_setopt($ch, CURLOPT_HEADER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_REFERER, "http://google.com");
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9; Mozilla Firefox');
	    $pg = curl_exec($ch);
	    if($pg){
	        return $pg;
	    } else {
	        return false;
	    }
	}
?>