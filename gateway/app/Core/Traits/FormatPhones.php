<?php

namespace App\Core\Traits;

use App\Countries\Countries;
use hisorange\BrowserDetect\Exceptions\Exception;
use Propaganistas\LaravelPhone\PhoneNumber;
use Stevebauman\Location\Facades\Location;

trait FormatPhones
{
    protected $countries;

    public function __construct(Countries $countries)
    {
        $this->countries = $countries;
    }

    public function makeFormat($request, $phone)
    {
        if(!$checkFormat = $this->checkFormat($phone)){
            $location = Location::get($request->getClientIp());
            $code = $location && $location->countryCode ? $location->countryCode : 'UA';
            if($code){
                return PhoneNumber::make($phone, $code)->formatE164();
            }else{
                return false;
            }
        }

        return PhoneNumber::make($phone, $checkFormat)->formatE164();
    }

    public function makeFormatData($request, $phone)
    {
        if(!$checkFormat = $this->checkFormat($phone)){
            $location = Location::get($request->getClientIp());
            $code = $location && $location->countryCode ? $location->countryCode : 'UA';
            if($code){
                return [
                    'phone' => PhoneNumber::make($phone, $code)->formatE164(),
                    'code'  => $code
                ];
            }else{
                return false;
            }
        }

        return [
            'phone' => PhoneNumber::make($phone, $checkFormat)->formatE164(),
            'code'  => $checkFormat
        ];
    }

    public function checkFormat($phone)
    {
        $codes = $this->countries->getPhoneCodes();
        $phone = ltrim($phone, '+');
        foreach($codes as $code=>$countryCode){
            if($code == substr($phone, 0, strlen($code)) && $code != null)
            {
                return $countryCode;
            }
        }

        return false;
    }
}
