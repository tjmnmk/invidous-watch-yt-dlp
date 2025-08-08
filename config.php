<?php
// https://github.com/yt-dlp/yt-dlp/wiki/Extractors#exporting-youtube-cookies
// cookies file must be writable by the web server user
// make sure cookies_file is not publicly accessible
define("COOKIES_FILE", ""); // e.g. /opt/cookies.txt
// https://github.com/yt-dlp/yt-dlp/wiki/PO-Token-Guide#po-token-for-gvs
define("PO_TOKEN", ""); // e.g. web.gvs+...