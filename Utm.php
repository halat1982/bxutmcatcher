<?php

namespace ItTower;

use Bitrix\Main\Application;

class Utm
{
    const SESSION_KEY_KEEPER = "SAVED_UTM";
    const UTM_BEGIN_PHRASE = "UTM метки:";
    protected $utmNames = array(
        "utm_source",
        "utm_medium",
        "utm_campaign",
        "utm_content",
        "utm_term",
        "gclid",
        "yclid"
    );

    protected $bRequest;

    public function __construct()
    {
        $this->bRequest = Application::getInstance()->getContext()->getRequest();
    }

    public function catchUtm()
    {
        $requestGetParams = $this->getRequestGetParameters();
        if(!empty($requestGetParams)){
            foreach($requestGetParams as $key => $getParamValue) {
                if($this->checkUtm($key)){

                    $this->writeUtmToSession($key, $getParamValue);
                }
            }
        }
    }

    public static function getUtmString($utm)
    {
        $str = "";
        foreach($utm as $key => $value){
            $str .= $key." = ".$value.", ";
        }

        if(!empty($str)){
            return self::UTM_BEGIN_PHRASE." ".substr($str,0,-2);
        } else {
            return $str;
        }

    }

    protected function getRequestGetParameters()
    {
        return $this->bRequest->getQueryList()->toArray();
    }

    protected function checkUtm($getParamKey)
    {
       return in_array($getParamKey, $this->utmNames);
    }

   protected function writeUtmToSession($name, $value)
    {
        $_SESSION[self::SESSION_KEY_KEEPER][$name] = $value;
    }
}

// ?utm_source=source&utm_medium=medium&utm_campaign=campaign&utm_content=content&utm_term=term&gclid=google&yclid=yandex
