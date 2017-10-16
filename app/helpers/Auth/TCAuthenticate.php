<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

namespace TrueCustomer\Helpers\Auth;


use Phalcon\Di;
use TrueCustomer\Helpers\Utils\TCUtils;
use TrueCustomer\Models\AuthPermissions;
use TrueCustomer\Models\Users;

class TCAuthenticate
{
    public $id;
    public $username;
    public $email;
    public $avatar;
    public $is_admin;
    public $group_id;
    public $group_name;
    public $role_id;
    public $login_by;
    public $preference = [];
    public $theme;

    /**
     * TCAuthenticate constructor.
     * @param $data array
     */
    public function __construct($data)
    {
        $this->id = (empty($data['id'])) ? '' : $data['id'];
        $this->username = (empty($data['username'])) ? '' : $data['username'];
        $this->email = (empty($data['email'])) ? '' : $data['email'];
        $this->avatar = (empty($data['avatar'])) ? '' : $data['avatar'];
        $this->is_admin = (empty($data['is_admin'])) ? false : $data['is_admin'];
        $this->group_id = (empty($data['group_id'])) ? 0 : $data['group_id'];
        $this->group_name = (empty($data['group_name'])) ? 'Register' : $data['group_name'];
        $this->role_id = (empty($data['role_id'])) ? 0 : $data['role_id'];
        $this->login_by = (empty($data['login_by'])) ? false : $data['login_by'];
        $this->preference = (empty($data['preference'])) ? array() : @json_decode($data['preference'], true);
        // setup theme
        $tcconfig = \TrueCustomer\Helpers\Utils\TCUtils::get_array4file('/app/config/system.php');
        $this->theme = (empty($data['theme'])) ? $tcconfig['systems']['theme'] : $data['theme'];
    }

    /**
     * @return array
     */
    public function getAuth()
    {
        return array(
            'id'    => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'group_id' => $this->group_id,
            'role_id' => $this->role_id,
            'login_by' => $this->login_by,
            'preference' => json_encode($this->preference)
        );
    }

    /**
     * @return bool
     */
    public function isLogin()
    {
        if (!$this->id) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        if ($this->is_admin) {
            return true;
        }

        return false;
    }

    /**
     * @param $controller string
     * @param $action string
     * @return bool
     */
    public function isAccess($controller, $action = 'detail')
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (!$controller) {
            return false;
        }

        if (!$this->role_id) {
            return false;
        }

        $permissions = AuthPermissions::getPermissions($this->role_id);

        if (empty($permissions[$controller]['controller'])) {
            return false;
        }

        $group_action = 'View';
        $access_actions = TCConfigACLAccess::accessActions();
        if (!empty($access_actions[$action])) {
            $group_action = $access_actions[$action];
        }

        if (empty($permissions[$controller][$group_action . 'Action'])) {
            return false;
        }

        return true;
    }

    /**
     * @param $model \TrueCustomer\Common\BaseModel
     * @param string $type
     * @return bool
     */
    public function isAccessData($model, $type = 'view')
    {
        if (!$model) {
            return false;
        }

        if ($this->isAdmin() || $model->access_all) {
            return true;
        }

        $model_name = str_replace('TrueCustomer\Models\\', '', get_class($model));
        $access = $this->levelAccessData($model_name, $type);

        switch ($access) {
            case TCConfigACLAccess::ALL:
                return true;
            case TCConfigACLAccess::GROUP:
                $users_group = Users::getUsersInGroup($this->group_id);
                if ($model->existField('assigned_user_id')) {
                    if (in_array($model->assigned_user_id, $users_group['siblings'])
                        || in_array($model->assigned_user_id, $users_group['children'])) {
                        return true;
                    }
                } else {
                    if (in_array($model->user_created_id, $users_group['siblings'])
                        || in_array($model->user_created_id, $users_group['children'])) {
                        return true;
                    }
                }
                return false;
            case TCConfigACLAccess::CHILD:
                $users_group = Users::getUsersInGroup($this->group_id);
                $users_group['children'][] = $this->id;
                if ($model->existField('assigned_user_id')) {
                    if (in_array($model->assigned_user_id, $users_group['children'])) {
                        return true;
                    }
                } else {
                    if (in_array($model->user_created_id, $users_group['children'])) {
                        return true;
                    }
                }
                return false;
            case TCConfigACLAccess::OWNER:
            default:
                if ($model->existField('assigned_user_id')) {
                    if ($model->assigned_user_id == $this->id) {
                        return true;
                    }
                } else {
                    if ($model->user_created_id == $this->id) {
                        return true;
                    }
                }
                return false;
        }
    }

    /**
     * @param $model string
     * @param string $type
     * @return int
     */
    public function levelAccessData($model, $type = 'view')
    {
        if (!$model) {
            return false;
        }

        if ($this->isAdmin()) {
            return TCConfigACLAccess::ALL;
        }

        $permissions = AuthPermissions::getPermissions($this->role_id);

        if (!empty($permissions[$model][$type])) {
            return $permissions[$model][$type];
        }

        return TCConfigACLAccess::OWNER;
    }

    /**
     * @param $preference array
     */
    public function setPreference($preference)
    {
        $auth = $this->getAuth();
        $auth['preference'] = json_encode($preference);
        Di::getDefault()->get('session')->set('auth', $auth);
    }

    /**
     * @param string $name
     * @return array|mixed|string
     */
    public function getPreference($name = '')
    {
        if (!$name) {
            return $this->preference;
        }

        if (empty($this->preference[$name])) {
            return '';
        }

        return $this->preference[$name];
    }

    /**
     * @return string
     */
    public function getJsPreference()
    {
        $preference = [
            'timezone' => $this->timezone(),
            'date_format' => $this->dateFormat(true),
            'date_time_format' => $this->datetimeFormat(true)
        ];

        return json_encode($preference);
    }

    /**
     * @return mixed|string
     */
    public function timezone()
    {
        if (empty($this->preference['timezone'])) {
            return 'UTC';
        }

        return $this->preference['timezone'];
    }

    /**
     * @param $momentjs boolean return momentjs format or php format
     * @return mixed|string
     */
    public function dateFormat($momentjs = false)
    {
        if (empty($this->preference['date_format'])) {
            $format = 'Y-m-d';
        } else {
            $format = $this->preference['date_format'];
        }

        if ($momentjs) {
            return TCUtils::convertPHPToMomentFormat($format);
        }

        return $format;
    }

    /**
     * @param $momentjs boolean return momentjs format or php format
     * @return mixed|string
     */
    public function datetimeFormat($momentjs = false)
    {
        if (empty($this->preference['date_time_format'])) {
            $format = 'Y-m-d H:i:s';
        } else {
            $format = $this->preference['date_time_format'];
        }

        if ($momentjs) {
            return TCUtils::convertPHPToMomentFormat($format);
        }

        return $format;
    }

    /**
     * @return mixed|string
     */
    public function numberFormat()
    {
        if (empty($this->preference['number_format'])) {
            return ',';
        }

        return $this->preference['number_format'];
    }

    /**
     * @param $date
     * @param $type string date|datetime
     * @return string
     */
    public function toDisplayDate($date, $type = 'date')
    {
        if (strlen($date) > 5) {
            $m = new \Moment\Moment($date, 'UTC');
            $m->setTimezone($this->timezone());

        } else {
            $m = new \Moment\Moment($date);
        }

        if ($type == 'date') {
            return $m->format($this->dateFormat());
        }

        return $m->format($this->datetimeFormat());
    }
}