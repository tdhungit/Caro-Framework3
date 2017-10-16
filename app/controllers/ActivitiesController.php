<?php
/**
 * Created by Caro Team (carodev.com).
 * User: Jacky (jacky@youaddon.com).
 * Year: 2017
 * File: ActivitiesController.php
 */

namespace TrueCustomer\Controllers;


use TrueCustomer\Common\BaseController;

class ActivitiesController extends BaseController
{
    protected $model_name = 'Activities';

    /**
     * @param \TrueCustomer\Common\BaseModel $model
     * @param array $data
     * @param array $errors_msg
     * @return bool|null|object
     */
    protected function saveRecord($model, $data, &$errors_msg = array())
    {
        // related
        $model->save_hasMany = ['members'];
        $date_start = $model->edit_view->getField('date_start')->toDb($data['date_start'], $this->auth);
        $date_end = $model->edit_view->getField('date_end')->toDb($data['date_end'], $this->auth);
        $start = $model::getMoment($date_start);
        $data['duration'] = $start->from($date_end)->getMinutes();

        return parent::saveRecord($model, $data, $errors_msg);
    }

    public function calendar_eventsAction()
    {
        $this->view->disable();

        $events = array();

        $activityO = $this->getModel('Activities');
        $activities = $activityO->getMany();

        /* @var $activity \TrueCustomer\Models\Activities */
        foreach ($activities as $activity) {
            $start = \Moment\Moment::createFromFormat('Y-m-d H:i:s', $activity->date_start)->format('Y-m-d H:i:s');
            $start_tz = new \Moment\Moment($start, 'UTC');
            $start_tz->setTimezone($this->auth->timezone());

            $end = \Moment\Moment::createFromFormat('Y-m-d H:i:s', $activity->date_end)->format('Y-m-d H:i:s');
            $end_tz = new \Moment\Moment($end, 'UTC');
            $end_tz->setTimezone($this->auth->timezone());

            $events[] = array(
                'title' => $activity->subject,
                'start' => $start_tz->format(DATE_W3C),
                'end' => $end_tz->format(DATE_W3C),
                'allDay' => false,
                'backgroundColor' => "#f56954", //red
                'borderColor' => "#f56954" //red
            );
        }

        return $this->responseJson($events);
    }

    public function get_inviteAction()
    {
        $q = $this->request->getQuery('q');

        $this->responseJson([
            'total_count' => 4,
            'incomplete_results' => true,
            'items' => [
                ['id' => 'hungtran@up5.vn', 'text' => 'hungtran@up5.vn'],
                ['id' => 'hieunguyen@up5.vn', 'text' => 'hieunguyen@up5.vn'],
                ['id' => 'ngoctran@up5.vn', 'text' => 'ngoctran@up5.vn'],
                ['id' => 'phatvo@up5.vn', 'text' => 'phatvo@up5.vn'],
            ]
        ]);
    }

    public function calendarAction()
    {
        $this->setTitle('Calendar');
        $this->addCss(array(
            '/plugins/fullcalendar/fullcalendar.min.css'
        ));
        $this->addJs(array(
            '/plugins/fullcalendar/fullcalendar.min.js',
            '/js/pages/activities/calendar.js'
        ));
    }

    public function editAction($id = null)
    {
        $this->addJs([
            '/plugins/vuejs/vue.min.js',
            '/js/vuefunc.js',
            '/js/pages/activities/edit.js'
        ]);
        $this->view->form_partials = [
            'members' => [
                'partial' => 'activities/partials/invite_member'
            ]
        ];
        return parent::editAction($id);
    }

    public function detailAction($id)
    {
        $this->view->partials = [
            'comments' => [
                'partial' => 'comments/partials/subpanel_comments',
                'data' => [
                    'relate_type' => $this->model_name,
                    'relate_id' => $id
                ]
            ]
        ];
        return parent::detailAction($id);
    }
}