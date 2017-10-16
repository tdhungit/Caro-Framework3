<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

/**
 * @param $field \TrueCustomer\Helpers\ModelLayoutItem
 * @param $value mixed
 * @param $auth \TrueCustomer\Helpers\TCAuthenticate
 * @return string
 */
function datetime_field_setting_toDb($field, $value, $auth)
{
    if ($field->type != 'datetime') {
        return $value;
    }

    if (!$auth->isLogin()) {
        return $value;
    }

    if (!$value) {
        return $value;
    }

    $datetime = \Moment\Moment::createFromFormat($auth->datetimeFormat(), $value)->format('Y-m-d H:i:s');
    $m = new \Moment\Moment($datetime, $auth->timezone());
    $m->setTimezone('UTC');
    return $m->format('Y-m-d H:i:s');
}

/**
 * @param $field \TrueCustomer\Helpers\ModelLayoutItem
 * @param $value mixed
 * @param $auth \TrueCustomer\Helpers\TCAuthenticate
 * @return string
 */
function datetime_field_setting_format($field, $value, $auth)
{
    if ($field->type != 'datetime') {
        return $value;
    }

    if (!$auth->isLogin()) {
        return $value;
    }

    if (!$value) {
        return $value;
    }

    $m = new \Moment\Moment($value, 'UTC');
    $m->setTimezone($auth->timezone());
    return $m->format($auth->datetimeFormat());
}
