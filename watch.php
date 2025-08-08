<?php

require_once 'config.php';

// get video code from ?v
if (isset($_GET['v'])) {
    $video_code = $_GET['v'];
} else {
    die('No video URL provided');
}


// check if video code is valid, allowed a-zA-Z0-9-_
if (!preg_match('/^[a-zA-Z0-9-_]+$/', $video_code)) {
    die('Invalid video code');
}

$video_url = 'https://www.youtube.com/watch?v=' . $video_code;

// download video to video dir
$video_dir = 'videos/';
if (!is_dir($video_dir)) {
    mkdir($video_dir, 0755, true);
}
$video_file = $video_dir . $video_code . '.mp4';
// escape the video file name
$video_file_escped = escapeshellarg($video_file);
// escape the video url
$video_url_escaped = escapeshellarg($video_url);


// download if video file does not exist
if (!file_exists($video_file)) {
    // download video using youtube-dl
    // quality 480p
    $command = "yt-dlp --cookies " . COOKIES_FILE . " --extractor-args 'youtube:po_token=" . PO_TOKEN . "' --format 'bestvideo[height<=480]+bestaudio/best[height<=480]' --merge-output-format mp4 -o $video_file_escped $video_url_escaped";
    exec($command, $output, $return_var);
    
    // save error to syslog
    if ($return_var !== 0) {
        error_log("Error downloading video: " . implode("\n", $output));
        die('Error downloading video');
    }
}

// redirect to video file by 302
header('Location: ' . $video_file);

