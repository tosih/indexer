<?php

$URI = urldecode($_SERVER['REQUEST_URI']);
$breadcrumbs = array_filter(explode("/", $URI));
$dir = $_SERVER['DOCUMENT_ROOT'].$URI;
$units = explode(' ', 'B KB MB GB TB PB');
$config = array(
    'fileExcludePatterns' => array('.gitignore', '*.sublime-*', '*favicon*', '.DS_Store'),
    'folderExcludePatterns' => array('.git')
);

function base($uri) {
    return dirname($_SERVER['SCRIPT_NAME']).$uri;
}

function format_size($size) {
    global $units;
    $mod = 1024;

    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    $endIndex = strpos($size, ".")+3;
    return substr( $size, 0, $endIndex).' '.$units[$i];
}

function inArray($string, $array = array()) {
    if (empty($array) || empty($string)) return false;
    foreach ($array as $key) {
        if (fnmatch($key, $string)) {
            return true;
        }
    }
    return false;
}

function breadcrumb() {
    global $breadcrumbs;
    $path = "";

    $activeClass = "flex-shrink bg-gray-200 text-gray-600 px-3 py-2 rounded-md text-sm font-medium";
    $inactiveClass = "flex-shrink bg-gray-100 text-gray-400 hover:bg-gray-400 hover:text-white px-3 py-2 rounded-md text-sm font-medium";

    $home = '<a href="/" class="'.$inactiveClass.'">back</a>';
    $home .= '<a class="text-gray-200 py-2 text-sm font-medium">/</a>';
    echo $home;
    foreach ($breadcrumbs as $key => $crumb) {
        $path .= "/$crumb";
        $class = ($key !== count($breadcrumbs)) ? $inactiveClass : $activeClass;

        $output = '<a href="'.$path.'" class="'.$class.'">'.$crumb.'</a>';
        $output .= '<a class="text-gray-200 py-2 text-sm font-medium">/</a>';
        echo $output;
    }
}

function listing() {
    global $dir;
    global $URI;
    global $config;

    $folderExcludePatterns = $config['folderExcludePatterns'];
    $fileExcludePatterns = $config['fileExcludePatterns'];

    if ($handle = opendir($dir)) {
        $dirs = array();
        $files = array();
        $errors = array();

        while (false !== ($entry = readdir($handle))) {
            if (is_dir($dir.$entry)) {
                if ($entry == '.' or inArray($entry, $folderExcludePatterns)) continue;
                if(($URI == '/' and ($entry == '..'))) continue;
                $dirs[] = $entry;
            } else {
                if (inArray($entry, $fileExcludePatterns)) continue;
                $files[] = $entry;
            }
        }

        sort($dirs);
        sort($files);

        if (! empty($errors)) {
            foreach ($errors as $entry) {
                echo '<li><a href="'.$_SERVER['REQUEST_URI'].$entry.'"><i class="fa fa-question-circle fa-2x"></i> &nbsp;'.$entry.'</a></li>';
            }
            closedir($handle);
            return;
        }

        foreach ($dirs as $entry) {
            $icon = '<path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />';
            $list = '<li class="flex items-start hover:bg-grey-200">'.
              '  <span class="h-6 flex items-center sm:h-7">'.
              '    <svg class="flex-shrink-0 h-5 w-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">'.
              '      '.$icon.
              '    </svg>'.
              '  </span>'.
              '  <p class="ml-2">'.
              '    <a  href="'.$_SERVER['REQUEST_URI'].$entry.'">'.
              '      '.$entry.
              '    </a>'.
              '  </p>'.
              '</li>';

            echo $list;
        }

        foreach ($files as $entry) {
            $exploded = explode('.', $entry);

            switch (end($exploded)) {
                case 'css':
                case 'js':
                case 'html':
                case 'php':
                    $icon = '<path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />';
                    break;
                case 'png':
                case 'jpg':
                case 'jpeg':
                case 'ico':
                case 'svg':
                    $icon = '<path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />';
                    break;
                case 'pdf':
                    $icon = '<path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />';
                    break;
                case 'wav':
                case 'mp3':
                case 'ogg':
                    $icon = '<path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z" />';
                    break;
                case 'mp4':
                case 'swf':
                case 'mkv':
                    $icon = '<path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm3 2h6v4H7V5zm8 8v2h1v-2h-1zm-2-2H7v4h6v-4zm2 0h1V9h-1v2zm1-4V5h-1v2h1zM5 5v2H4V5h1zm0 4H4v2h1V9zm-1 4h1v2H4v-2z" clip-rule="evenodd" />';
                    break;
                case 'zip':
                case 'rar':
                  $icon = '<path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" /><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />';
                  break;
                default:
                    $icon = '<path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />';
                    break;
            }

            $list = '<li class="flex items-start">'.
              '  <span class="h-6 flex items-center sm:h-7">'.
              '    <svg class="flex-shrink-0 h-5 w-5 text-cyan-500" fill="currentColor" viewBox="0 0 20 20">'.
              '      '.$icon.
              '    </svg>'.
              '  </span>'.
              '  <p class="ml-2">'.
              '    <a href="'.$_SERVER['REQUEST_URI'].$entry.'">'.
              '      '.$entry.
              '    </a>'.
              '    <small class="entry">('.format_size(filesize($dir.$entry)).')</small>'.
              '  </p>'.
              '</li>';

            echo $list;
        }
        closedir($handle);
    }
}
