<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: Blameable.php
 */

namespace TrueCustomer\Common;


use Phalcon\Mvc\Model\Behavior;
use Phalcon\Mvc\Model\BehaviorInterface;
use Phalcon\Mvc\ModelInterface;
use TrueCustomer\Models\Audit;
use TrueCustomer\Models\AuditDetail;
use TrueCustomer\Models\Notifications;

class Blameable extends Behavior implements BehaviorInterface
{
    /**
     * Receives notifications from the Models Manager
     *
     * @param string $type
     * @param ModelInterface $model
     * @return boolean
     */
    public function notify($type, ModelInterface $model)
    {
        if ($type == 'afterCreate') {
            if ($model->audit) {
                $this->auditAfterCreate($model);
            }

            if ($model->notify) {
                $this->auditNotify('Create', $model);
            }
        }

        if ($type == 'afterUpdate') {
            if ($model->audit) {
                $this->auditAfterUpdate($model);
            }

            if ($model->notify) {
                $this->auditNotify('Update', $model);
            }
        }

        return true;
    }

    /**
     * Audit a CREATE operation
     *
     * @param ModelInterface $model
     * @return bool
     */
    public function auditAfterCreate(ModelInterface $model)
    {
        $audit = new Audit();
        $audit->model_name = str_replace('TrueCustomer\Models\\', '', get_class($model));
        $audit->type = 'Create';
        $audit->record_id = $model->readAttribute('id');

        return $audit->save();
    }

    /**
     * Audit an UPDATE operation
     *
     * @param ModelInterface $model
     * @return bool
     */
    public function auditAfterUpdate(ModelInterface $model)
    {
        // Get the name of the fields that have changed
        //$changedFields = $model->getChangedFields();
        // check and verify phalcon.orm.update_snapshot_on_save
        $changedFields = $model->getUpdatedFields();

        if (count($changedFields)) {
            // Get the original data before modification
            //$originalData = $model->getSnapshotData();
            // check and verify phalcon.orm.update_snapshot_on_save
            $originalData = $model->getOldSnapshotData();

            $audit = new Audit();
            $audit->model_name = str_replace('TrueCustomer\Models\\', '', get_class($model));
            $audit->type = 'Update';
            $audit->record_id = $originalData['id'];

            $details = [];
            foreach ($changedFields as $field) {
                if ($originalData[$field] != $model->readAttribute($field)) {
                    $auditDetail = new AuditDetail();
                    $auditDetail->field_name = $field;
                    $auditDetail->old_value = $originalData[$field];
                    $auditDetail->new_value = $model->readAttribute($field);
                    $details[] = $auditDetail;
                }
            }

            if (!empty($details)) {
                $audit->details = $details;
                return $audit->save();
            }
        }

        return false;
    }

    /**
     * @param $type
     * @param ModelInterface $model
     * @return bool
     */
    public function auditNotify($type, ModelInterface $model)
    {
        if (!empty($model->assigned_user_id)) {
            $notification = new Notifications();
            $notification->model_name = str_replace('TrueCustomer\Models\\', '', get_class($model));
            $notification->record_id = $model->readAttribute('id');
            $notification->controller_name = $model->action_controller_name;
            $notification->view_detail_action = $model->action_detail;
            $notification->type = $type;
            $notification->action = $type;
            $notification->assigned_user_id = $model->assigned_user_id;
            $notification->is_read = 0;
            return $notification->save();
        }
    }

}