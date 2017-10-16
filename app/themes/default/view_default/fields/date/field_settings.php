<?php
/**
 * Created by CaroDev.
 * User: Jacky
 */

/**
 * @param $field \TrueCustomer\Helpers\ModelLayoutItem
 * @param $value mixed
 * @param $auth \TrueCustomer\Helpers\TCAuthenticate
 * @return mixed
 */
function date_field_setting_toDb($field, $value, $auth)
{
    if ($field->type != 'date') {
        return $value;
    }

    if (!$auth->isLogin()) {
        return $value;
    }

    if (!$value) {
        return $value;
    }

    $date = \Moment\Moment::createFromFormat($auth->dateFormat(), $value)->format('Y-m-d');
    return $date;
}

/**
 * @param $field \TrueCustomer\Helpers\ModelLayoutItem
 * @param $value mixed
 * @param $auth \TrueCustomer\Helpers\TCAuthenticate
 * @return mixed
 */
function date_field_setting_format($field, $value, $auth)
{
    if ($field->type != 'date') {
        return $value;
    }

    if (!$auth->isLogin()) {
        return $value;
    }

    if (!$value) {
        return $value;
    }

    $m = new \Moment\Moment($value);
    return $m->format($auth->dateFormat());
}
