<?php

require_once 'config.php';

/**
 * Check if current time is within low quality time period
 * @return bool true if current time is within low quality period
 */
function isLowQualityTime() {
    if (!LOW_QUALITY_ENABLED) {
        return false;
    }
    
    // Get timezone from config
    $timezone = new DateTimeZone(LOW_QUALITY_TIME_ZONE);
    
    // Get current time in the configured timezone
    $now = new DateTime('now', $timezone);
    $currentTime = $now->format('H:i:s');
    
    // Get start and end times from config
    $startTime = LOW_QUALITY_TIME_START;
    $endTime = LOW_QUALITY_TIME_END;
    
    // Handle case where end time is before start time (spans midnight)
    if ($endTime < $startTime) {
        // Time period spans midnight (e.g., 22:00:00 to 06:00:00)
        return ($currentTime >= $startTime || $currentTime <= $endTime);
    } else {
        // Normal time period within same day
        return ($currentTime >= $startTime && $currentTime <= $endTime);
    }
}

function yt_dlp_base_command() {
    $command = "yt-dlp --js-runtimes deno:" . DENO_PATH . " ";
    if (COOKIES_FILE) {
        $escaped_cookies_file = escapeshellarg(COOKIES_FILE);
        $command .= " --cookies " . $escaped_cookies_file . " ";
    }
    if (PO_TOKEN) {
        $full_po_arg = "youtube:po_token=" . PO_TOKEN;
        $po_arg = escapeshellarg($full_po_arg);
        $command .= " --extractor-args " . $po_arg . " ";
    }

    return $command;
}

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
if (isLowQualityTime()) {
    $video_dir = 'videos_low/';
}

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
    $command = yt_dlp_base_command();
    if (!$DOWNLOAD_LIVESTREAMS) {
        $command .= ' --match-filter "live_status!=is_live & live_status!=is_upcoming" ';
    }
    // download only first video in playlist if url is a playlist
    $command .= " --playlist-items 1 ";
    if (isLowQualityTime()) {
        $command .= " -f 'worstvideo*+worstaudio/worst' --merge-output-format mp4 -o $video_file_escped $video_url_escaped ";
    } else {
        $command .= " -S '+height:480' -f 'bv*+ba/best' --merge-output-format mp4 -o $video_file_escped $video_url_escaped ";
    }
    exec($command, $output, $return_var);
    // save error to syslog
    if ($return_var !== 0) {
        error_log($command);
        error_log("Error downloading video: " . implode("\n", $output));
        header('Content-Type: text/plain');
        die('Error downloading video');
    }
}

if (!file_exists($video_file)) {
    header('Content-Type: text/plain');

    // check if video is a livestream
    $command = yt_dlp_base_command();
    $command .= "--print \"%(live_status)s\" " . $video_url_escaped;
    exec($command, $output, $return_var);
    // live status is last line of output
    $live_status = end($output);
    echo "live_status: " . $live_status . "\n";
    if ($return_var === 0 && isset($live_status) && $live_status === 'is_live' && !$DOWNLOAD_LIVESTREAMS) {
        die('Video is a livestream, downloading livestreams is not allowed');
    }
    die('Video file does not exist after download attempt');
}

// redirect to video file by 302
header('Location: ' . $video_file);

