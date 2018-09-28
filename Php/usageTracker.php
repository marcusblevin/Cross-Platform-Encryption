<?php
ini_set("log_errors" , "1");                                    // Enable logging
ini_set("error_log" , "../logs/Errors.log.txt");                // Set log path
ini_set("display_errors" , "1");
include_once('include/header.php');


function strToHex($string){
	$hex = '';
	for ($i=0; $i<strlen($string); $i++){
		$ord = ord($string[$i]);
		$hexCode = dechex($ord);
		$hex .= substr('0'.$hexCode, -2);
	}
	return strToUpper($hex);
}
function hexToStr($hex){
	$string='';
	for ($i=0; $i < strlen($hex)-1; $i+=2){
		$string .= chr(hexdec($hex[$i].$hex[$i+1]));
	}
	return $string;
}

$secret = hash("sha256", 'ABCDABCDABCD');
$secret = hexToStr($secret); // R requires to be raw type, strToHex converts to same as charToRaw
$cipher = "AES-256-CBC";
$iv = '24c962288f3789e8'; // generated from PHP random_bytes(8) and converted to 16 bytes using bin2hex
// converting 8 bytes of binary data using bin2hex yields a 16 byte string


/*
//OPENSSL_ZERO_PADDING prevents openssl from padding the string with an unknown character, have to manually pad it to the desired length before passing in
$pad = (strlen($dbName) != 16) ? (16 - (strlen($dbName) % 16)) : 0; // determined number of spaces to pad
$dbName_padded = $dbName . str_repeat(chr(32), $pad); // pad with spaces
var_dump($dbName_padded).PHP_EOL;
*/


$enc = openssl_encrypt($dbName, $cipher, $secret, $options=0, $iv);
$dec = openssl_decrypt($enc, $cipher, $secret, $options=0, $iv);
$enc = bin2hex(base64_decode($enc)); // enc is base64 encoded, convert to string



/*
if (base64_encode(base64_decode($enc)) === $enc) {
	echo 'enc is valid';
} else {
	echo 'enc is NOT valid';
}
*/
?>
<script type="text/javascript">
    function resizeIFrame(Frame) {
       var iframe=document.getElementById(Frame);
       if ( iframe.contentDocument && iframe.contentDocument.body.offsetHeight ) {
          iframe.height = iframe.contentDocument.body.offsetHeight + 16;
       }
       else if (iframe.Document && iframe.Document.body.scrollHeight) {
          iframe.height = iframe.Document.body.scrollHeight + 16;
       }
    }

    // as the page scrolls, check to see if any plots need to be loaded
    window.addEventListener('scroll', loadIFrame, true);

    function loadIFrame() {
        $( ".analyticFrame" ).each(function(index) {
            //console.log($(this).data('src'));
            if ($( this ).attr('src') == '' && Utils.isElementInView($( this ), false)) {
                //console.log("loading: "+$(this).data('src'));
                $( this ).attr('onload', "javascript: this.parentElement.style.background = 'none';").attr('src', $( this ).data('src'));
            }
        });
    }

    // this will delay the resize event from firing until the page is done moving
    // https://developer.mozilla.org/en-US/docs/Web/Events/resize
    (function() {
        window.addEventListener("resize", resizeIFrameThrottler, false);

        var resizeIFrameTimeout;
        function resizeIFrameThrottler() {
            // ignore resize events as long as an actualResizeHandler execution is in the queue
            if ( !resizeIFrameTimeout ) {
              resizeIFrameTimeout = setTimeout(function() {
                resizeIFrameTimeout = null;
                actualIFrameResizeHandler(); // The actualResizeHandler will execute at a rate of 15fps
               }, 66);
            }
        }

        function actualIFrameResizeHandler() {
            // handle the resize event
            var diagrams = document.getElementsByClassName("analyticFrame");
            for (var i=0; i<diagrams.length; i++) { resizeIFrame(diagrams[i].id); } 
        }

    }());

</script>
<div class="col-sm-12"><h1 class="page-header">Usage Tracker</h1></div>
<div class="pane-single">
	<iframe src="" data-src="http://matlab.graphet.local:3838/sample-apps/CumulativeSavings?d=<?php echo $enc; ?>" class="analyticFrame" style="border: none; width: 100%; height: 100%; min-height: 50vh;"></iframe>
</div>
<script type="text/javascript">
loadIFrame();
</script>

<?php 
include_once('include/footer.php'); 
?>