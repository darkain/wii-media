<?php
  $base = '';

  $path = (isset($_GET['path'])) ? $_GET['path'] : './';

  // Search for MP3 files
  $mydir = opendir($base . $path);
  $files = array();
  while ($fn = readdir($mydir)) {
    if (strtolower(substr($fn, -4)) == '.mp3') {
    $time = filemtime($base . $path . $fn);
      $files[$fn] = $fn;
    }
  }

  $file = fopen('playlist.xml', 'w');
  fwrite($file, "<playlist version=\"1\" xmlns=\"http://xspf.org/ns/0/\">\n");
  fwrite($file, "<trackList>\n");

  // Sort and display items
  ksort($files);
  reset($files);
  while ($item = each($files)) {
    $fn    = $item[1];
    $url   = $base . $path . rawurlencode($fn);

    fwrite($file, "<track>");
    fwrite($file, "<title>");
    fwrite($file, $fn);
    fwrite($file, "</title>");
    fwrite($file, "<location>");
    fwrite($file, $url);
    fwrite($file, "</location>");
    fwrite($file, "</track>\n");
  }

  fwrite($file, "</trackList>\n");
  fwrite($file, "</playlist>\n");
  fclose($file);
?>

<html>
<head>
<script type="text/javascript" src="ufo.js"></script>

<style>
* {
	padding: 0;
	margin: 0;
	border: 0;
	background: #000000;
	color: white;
}

div {
	text-align: center;
}
</style>

</head>

<body>


<div id="player1"></div>


<script type="text/javascript">
var FO = {
	movie:"mp3player.swf",width:"700",height:"500",majorversion:"7",build:"0",bgcolor:"#000000",
	flashvars:"file=playlist.xml&showdigits=true&repeat=true&shuffle=false&displayheight=50&autostart=false&lightcolor=0x0099CC&backcolor=0x000000&frontcolor=0xffffff"
};
UFO.create(FO, "player1");
</script>


<?php
  $self = $_SERVER["PHP_SELF"];

  $mydir = opendir($base . $path);
  while ($fn = readdir($mydir)) {
    if (is_dir($base . $path . $fn)  &&  $fn!='.'  &&  $fn!='..') {
      echo "<a href=\"$self?path=" . rawurlencode($base . $path . $fn) . "/\">" . $base . $path . $fn . "</a><br>\n";
//      echo $base . $path . $fn . "<br>\n";
    }
  }
?>


</body>
</html>