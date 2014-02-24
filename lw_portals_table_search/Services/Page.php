<?php

namespace LwPortalsTableSearch\Services;

class Page
{

    public static function reload($url)
    {
        $url = str_replace("&amp;", "&", $url);
        echo '<html>' . PHP_EOL;
        echo '    <head><meta http-equiv="Refresh" content="0;url=' . $url . '" /></head>' . PHP_EOL;
        echo '    <body onload="try {self.location.href=' . "'" . $url . "'" . ' } catch(e) {}"><a href="' . $url . '">Redirect </a></body>' . PHP_EOL;
        echo '</html>' . PHP_EOL;
        exit();
    }

    public static function getUrl($array = false)
    {
        return \lw_page::getInstance()->getUrl($array);
    }

}
