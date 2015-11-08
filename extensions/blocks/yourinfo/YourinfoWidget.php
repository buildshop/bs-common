<?php

/**
 * Cart Widget
 * Display is module shop installed
 * @uses Widget 
 */
class YourinfoWidget extends BlockWidget {

    public function getTitle() {
        return 'Ваша информация';
    }

    public function run() {
        Yii::import('app.addons.Browser');
        $browser = new Browser();


        if ($browser->getBrowser() == Browser::BROWSER_FIREFOX) {
            $browserIcon = 'flaticon-firefox';
        } elseif ($browser->getBrowser() == Browser::BROWSER_SAFARI) {
            $browserIcon = 'flaticon-safari';
        } elseif ($browser->getBrowser() == Browser::BROWSER_OPERA) {
            $browserIcon = 'flaticon-opera';
        } elseif ($browser->getBrowser() == Browser::BROWSER_CHROME) {
            $browserIcon = 'flaticon-chrome';
        } elseif ($browser->getBrowser() == Browser::BROWSER_IE) {
            $browserIcon = 'flaticon-explorer';
        }

        if ($browser->getPlatform() == Browser::PLATFORM_WINDOWS) {
            $platformIcon = 'flaticon-windows';
        } elseif ($browser->getPlatform() == Browser::PLATFORM_WINDOWS_8) { //no tested
            $platformIcon = 'flaticon-windows8';
        } elseif ($browser->getPlatform() == Browser::PLATFORM_ANDROID) {
            $platformIcon = 'flaticon-android';
        } elseif ($browser->getPlatform() == Browser::PLATFORM_LINUX) {
            $platformIcon = 'flaticon-linux';
        } elseif ($browser->getPlatform() == Browser::PLATFORM_APPLE) {
            $platformIcon = 'flaticon-apple ';
        }


        $this->render($this->skin, array(
            'platformIcon' => $platformIcon,
            'browserIcon' => $browserIcon,
            'browser' => $browser,
        ));
    }

}
