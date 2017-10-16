<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Libraries\ExtApp;


use TrueCustomer\Helpers\Utils\TCUtils;

abstract class ExtAuthenticate
{
    protected $category_config;
    protected $redirect_url = '';

    abstract public function authenticate($data = []);
    abstract public function isLogin();
    abstract public function logOut();

    public function setPreference($key, $value)
    {
        $userO = TCUtils::getModel('Users');
        $auth = $userO->getAuth();
        if ($auth->isLogin()) {
            /* @var $user \TrueCustomer\Models\Users */
            $user = $userO->getOne("id = {$auth->id}");
            $preference = @json_decode($user->preference, true);
            if (!$preference) {
                $preference = [];
            }
            $preference[$this->category_config][$key] = $value;
            $auth->setPreference($preference);
            $user->preference = json_encode($preference);
            $user->save();
        }
    }

    public function getRedirectUrl()
    {
        return $this->redirect_url;
    }

    /**
     * @return array|mixed|string
     */
    public function getPreference()
    {
        $userO = TCUtils::getModel('Users');
        return $userO->getAuth()->getPreference($this->category_config);
    }

    /**
     * @return \TrueCustomer\Common\TrueLog
     */
    public function getLog()
    {
        return new \TrueCustomer\Common\TrueLog();
    }
}