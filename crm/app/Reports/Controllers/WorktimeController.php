<?php

namespace App\Reports\Controllers;

//use App\Reports\Repositories\WorkTimeRepository;
use App\Core\Traits\ApiResponder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class WorktimeController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    //private $workTimeRepository;

    public function __construct(/*WorkTimeRepository $workTimeRepository*/)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        //$this->workTimeRepository = $workTimeRepository;
    }

    public function index()
    {
        $data = request()->all();

        /* Set default time */
        if(!isset($data['date_from']) || !$data['date_from']){
            $data['date_from'] = new Carbon($data['date_to'] ?? Carbon::now());
            $data['date_from']->subWeek();
        }

        /* Test data */
        $testData = [
            'data'=>[
                0 => [
                    'fullname'  => 'Долгова Анна Владимировна',
                    'position'  => 'Главный менеджер',
                    'thumbnail' => 'path',
                    'work_day'  =>  23,
                    'work_hour_actual'  => 190,
                    'work_planned_hour' => 207,
                    'schedule' => [
                        0 => [
                            'date' => '2021-02-15',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        1 => [
                            'date' => '2021-02-16',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        2 => [
                            'date' => '2021-02-17',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '04:10',
                            'is_worked_out' => false,
                        ],
                        3 => [
                            'date' => '2021-02-18',
                            'from' => null,
                            'to'   => null,
                            'total' => null,
                            'is_worked_out' => false,
                        ],
                        4 => [
                            'date' => '2021-02-19',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        5 => [
                            'date' => '2021-02-20',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        6 => [
                            'date' => '2021-02-21',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        7 => [
                            'date' => '2021-02-22',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                    ]
                ],
                1 => [
                    'fullname'  => 'Долгова Анна Владимировна',
                    'position'  => 'Главный менеджер',
                    'thumbnail' => 'path',
                    'work_day'  =>  23,
                    'work_hour_actual'  => 190,
                    'work_planned_hour' => 207,
                    'schedule' => [
                        0 => [
                            'date' => '2021-02-15',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        1 => [
                            'date' => '2021-02-16',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        2 => [
                            'date' => '2021-02-17',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        3 => [
                            'date' => '2021-02-18',
                            'from' => null,
                            'to'   => null,
                            'total' => null,
                            'is_worked_out' => false,
                        ],
                        4 => [
                            'date' => '2021-02-19',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        5 => [
                            'date' => '2021-02-20',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        6 => [
                            'date' => '2021-02-21',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '03:10',
                            'is_worked_out' => false,
                        ],
                        7 => [
                            'date' => '2021-02-22',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                    ]
                ],
                2 => [
                    'fullname'  => 'Долгова Анна Владимировна',
                    'position'  => 'Главный менеджер',
                    'thumbnail' => 'path',
                    'work_day'  =>  23,
                    'work_hour_actual'  => 190,
                    'work_planned_hour' => 207,
                    'schedule' => [
                        0 => [
                            'date' => '2021-02-15',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        1 => [
                            'date' => '2021-02-16',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        2 => [
                            'date' => '2021-02-17',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        3 => [
                            'date' => '2021-02-18',
                            'from' => null,
                            'to'   => null,
                            'total' => null,
                            'is_worked_out' => false,
                        ],
                        4 => [
                            'date' => '2021-02-19',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        5 => [
                            'date' => '2021-02-20',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        6 => [
                            'date' => '2021-02-21',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '09:10',
                            'is_worked_out' => true,
                        ],
                        7 => [
                            'date' => '2021-02-22',
                            'from' => '09:00',
                            'to'   => '18:10',
                            'total' => '05:10',
                            'is_worked_out' => false,
                        ],
                    ]
                ],
            ],

        ];


        return $testData;
    }


}
