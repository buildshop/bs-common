<?php

class yandexTranslate {

    protected $api_key = 'trnsl.1.1.20141219T203729Z.c1345ed900582266.7501482db4e6901d183127f3933b578656878fcb';
    const API_KEY = 'trnsl.1.1.20141219T203729Z.c1345ed900582266.7501482db4e6901d183127f3933b578656878fcb';
    protected $api_url = 'https://translate.yandex.net/api/v1.5/tr.json/translate';

    public function translitUrl($lang = array(), $text) {
        if (!$params)
            $params = array();
        $params['key'] = $this->api_key;
        $params['format'] = 'json';
        $params['text'] = $text;
        $params['lang'] = $lang[0] . '-' . $lang[1];
        $query = $this->api_url . '?' . $this->params($params);

        $res = $this->curl_get_contents($query);
        $json = CJSON::decode($res, true);
        $array = array(
            '/' => '_',
            ' ' => '-',
            '(' => '_',
            ')' => '_',
            '−' => '-',
            ':' => '_',
            ';' => '_',
            '"' => '_',
            '\'' => '_',
            '&' => 'and',
            '!' => '',
            '?' => '',
            '—' => '',
            '.' => '',
            ',' => '',
            '»' => '',
            '«' => '',
        );
        $text = implode(array_slice(explode('<br>', wordwrap(trim(strip_tags(html_entity_decode($json['text'][0]))), 255, '<br>', false)), 0, 1));
        foreach ($array as $from => $to) {
            $text = str_replace($from, $to, $text);
        }
        return strtolower($text);
    }

    public function translate($lang = array(), $text) {
        if (!$params)
            $params = array();
        $params['key'] = $this->api_key;
        $params['format'] = 'json';
        $params['text'] = $text;
        $params['lang'] = $lang[0] . '-' . $lang[1];
        $query = $this->api_url . '?' . $this->params($params);

        $res = $this->curl_get_contents($query);
        return CJSON::decode($res, true);
    }

    private function curl_get_contents($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    function params($params) {
        $pice = array();
        foreach ($params as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $t) {
                    $pice[] = $k . '=' . urlencode($t);
                }
            } else {
                $pice[] = $k . '=' . urlencode($v);
            }
        }
        return implode('&', $pice);
    }

    public static function onlineLangs() {
        return array(
            'ar' => "Arabic",
            'hy' => "Armenian",
            'sq' => "Albanian",
            'az' => "Azerbaijani",
            'be' => "Belarusian",
            'bg' => "Bulgarian",
            'bs' => "Bosnian",
            'ca' => "Catalan",
            'cs' => "Czech",
            'hr' => "Croatian",
            'zh' => "Chinese",
            'da' => "Danish",
            'nl' => "Dutch",
            'de' => "German",
            'el' => "Greek",
            'ka' => "Georgian",
            'en' => "English",
            'et' => "Estonian",
            'fi' => "Finnish",
            'fr' => "French",
            'he' => "Hebrew",
            'hu' => "Hungarian",
            'id' => "Indonesian",
            'is' => "Icelandic",
            'it' => "Italian",
            'lt' => "Lithuanian",
            'lv' => "Latvian",
            'mk' => "Macedonian",
            'ms' => "Malay",
            'mt' => "Maltese",
            'no' => "Norwegian",
            'pl' => "Polish",
            'pt' => "Portuguese",
            'ro' => "Romanian",
            'ru' => "Russian",
            'sk' => "Slovak",
            'sl' => "Slovenian",
            'sr' => "Serbian",
            'sv' => "Swedish",
            'es' => "Spanish",
            'th' => "Thai",
            'tr' => "Turkish",
            'uk' => "Ukrainian",
            'vi' => "Vietnamese",
        );
    }

}

?>
