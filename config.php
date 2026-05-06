<?php
// https://github.com/yt-dlp/yt-dlp/wiki/Extractors#exporting-youtube-cookies
// cookies file must be writable by the web server user
// make sure cookies_file is not publicly accessible
define("COOKIES_FILE", ""); // e.g. /opt/cookies.txt
// https://github.com/yt-dlp/yt-dlp/wiki/PO-Token-Guide#po-token-for-gvs
define("PO_TOKEN", ""); // e.g. web.gvs+...

//low quality time start e.g. 00:00:00
define("LOW_QUALITY_TIME_START", "00:00:00"); 
// low quality time end e.g. 00:00:00
define("LOW_QUALITY_TIME_END", "06:00:00");

define("LOW_QUALITY_ENABLED", true);

// low quality time zone e.g. UTC
define("LOW_QUALITY_TIME_ZONE", "Asia/Yekaterinburg"); // https://www.php.net/manual/en/timezones.asia.php

define("DENO_PATH", "/usr/local/bin/deno"); // e.g. /usr/bin/deno

define("DOWNLOAD_LIVESTREAMS", false); // set to true to allow downloading livestreams, may cause issues with some channels