<?php

namespace App\Facades;

use Illuminate\Support\Facades\Http;

class IDP
{
    public $token       = null;
    public $http        = null;
    public $credentials = null;
    public $header      = null;

    public function __construct()
    {
        $this->http   = Http::acceptJson()->baseUrl(config('services.dashboard.api_url'))->withOptions(["verify" => false]);
        $this->credentials = [
            "grant_type"    => "client_credentials",
            "client_id"     => config('services.dashboard.client_id'),
            "client_secret" => config('services.dashboard.client_secret'),
        ];
        $this->token  = $this->http->post('/oauth/token', $this->credentials)->json('access_token');
        $this->header = ['Authorization' => "Bearer {$this->token}"];
    }

    /*
     * **********************
     *  FECTCH CLIENT TOKEN
     * ******************** */
    static function token()
    {
        return (new IDP())->token;
    }



    /*
     * ****************
     *  CLIENT OBJECT
     * ************** */
    static function client()
    {
        $idp = new IDP();
        return ($idp->http)->withHeaders($idp->header);
    }

    static function post($path = '', $data = [])
    {
        return IDP::client()->post($path, $data);
    }

    static function get($path = '', $data = [])
    {
        // logger(config('services.dashboard.api_url'));
        return IDP::client()->get($path, $data);
    }

    static function user($token = '')
    {
        $idp = new IDP();
        return $idp->http->withHeaders(['Authorization' => "Bearer {$token}"])->get('user');
    }
}
