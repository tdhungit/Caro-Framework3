<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

use Phalcon\Di;
use TrueCustomer\Helpers\Utils\TCUtils;
use TrueCustomer\Libraries\ExtApp\ExtAuthenticate;

require_once APP_PATH . '/app/libraries/ExtApp/Google/vendor/autoload.php';
class GoogleAuthenticate extends ExtAuthenticate
{
    protected $category_config = 'google';

    private $token = '';
    private $log_file_name = 'google_authenticate';
    private $scopes = array(
        'https://www.googleapis.com/auth/contacts.readonly',
        Google_Service_Drive::DRIVE_READONLY,
        Google_Service_Drive::DRIVE_FILE,
    );

    private function _getConfig()
    {
        return TCUtils::get_array4file('/app/libraries/ExtApp/Google/config.php');
    }

    private function _getGoogleOauth2Config()
    {
        $config = $this->_getConfig();
        $config['redirect_uri'] = rtrim(Di::getDefault()->get('utils')->base_url, '/')
            . '/index/authenticate/Google';
        return $config;
    }

    private function _getGoogleClient()
    {
        $config = $this->_getGoogleOauth2Config();

        $client = new Google_Client();
        $client->setClientId($config['client_id']);
        $client->setClientSecret($config['client_secret']);
        $client->setRedirectUri($config['redirect_uri']);

        $client->setAccessType('offline');
        $client->setScopes($this->scopes);

        return $client;
    }

    private function _saveToken($token)
    {
        $this->setPreference('token', $token);
    }

    private function _refreshToken(Google_Client $client)
    {
        $auth = $client->getAuth();
        $refreshToken = $auth->getRefreshToken();
        if ($refreshToken) {
            try {
                $client->refreshToken($refreshToken);
            } catch (Google_Auth_Exception $e) {
                $this->getLog()->log($e->getMessage(), $this->log_file_name);
                return;
            }

            $token = $client->getAccessToken();
            $this->_saveToken($token);
        }
    }

    public function getClient()
    {
        $client = $this->_getGoogleClient();
        $preference = $this->getPreference();
        if (!empty($preference['token'])) {
            $client->setAccessToken($preference['token']);
            if ($client->isAccessTokenExpired()) {
                $this->_refreshToken($client);
            }
        }

        return $client;
    }

    public function revokeToken()
    {
        $client = $this->getClient();

        try {
            $client->revokeToken();
        } catch (Google_Auth_Exception $e) {
            $this->getLog()->log($e->getMessage(), $this->log_file_name);
            return false;
        }

        $this->setPreference('token', '');
        return true;
    }

    public function authenticate($data = [])
    {
        if (empty($data['code'])) {
            $this->token = '';
            return false;
        }

        $code = $data['code'];

        $client = $this->getClient();
        try {
            $client->authenticate($code);
        } catch (Google_Auth_Exception $e) {
            $this->getLog()->log($e->getMessage(), $this->log_file_name);
            $this->token = '';
            return false;
        }

        $token = $client->getAccessToken();
        if ($token) {
            $this->_saveToken($token);
        }

        $this->token = $token;
        return true;
    }

    /**
     * @return bool
     */
    public function isLogin()
    {
        if ($this->token) {
            return true;
        }
        $this->redirect_url = $this->getClient()->createAuthUrl();
        return false;
    }

    /**
     * @return bool
     */
    public function logOut()
    {
        $this->token = '';
        $this->revokeToken();
        return true;
    }
}