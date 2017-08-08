<?php
// example of how to use basic selector to retrieve HTML contents
include('simple_html_dom.php');
function unshorten_url($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_FOLLOWLOCATION => TRUE,  // the magic sauce
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_SSL_VERIFYHOST => FALSE, // suppress certain SSL errors
        CURLOPT_SSL_VERIFYPEER => FALSE, 
    ));
    curl_exec($ch);
    $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    return $url;
}

$source = unshorten_url($_GET['source']);
// get DOM from URL or file
$html = file_get_html('http://newspaper-demo.herokuapp.com/articles/show?url_to_clean='.$source);
foreach( $html->find('table.table',0)-> find('tr') as $tr){
$key = trim($tr->find('td',0)->innertext );
if($key != 'Top Image' && $key != 'Article HTML'){
$data[strtolower($key)]  = utf8_encode(trim($tr->find('td',1)->innertext));
}
}
header('Content-Type: application/json');
echo json_encode($data);
?>