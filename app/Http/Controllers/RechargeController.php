<?php

namespace App\Http\Controllers;

use App\Http\Requests\RechargeRequest;
use App\Models\Recharge;
use App\Models\Subscribers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use \Illuminate\Http\JsonResponse as JsonResponse;

class RechargeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Recharge::getDataTableData($request);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('is_notification_active', function ($data) {
                    if ($data->is_notification_active == 'yes')
                        return 1;
                    else if ($data->is_notification_active == 'no')
                        return 2;
                })
                ->addColumn('action', function ($data) {
                    return '';
                })

                ->rawColumns(['action', 'is_notification_active'])
                ->make(true);
        }
        $subscriber = Subscribers::orderBy('id', 'desc')->get();
        return view("recharge.index")->with(["subscribers" => $subscriber]);
    }

    public function getUserDetails(Request $request)
    {
        $id = $request->id;
        if ($id != null) {
            $user = Subscribers::where("id", $id)->first();
            if ($user) {
                return response()->json(["code" => 200, "message" => "User found.", 'data' => $user], 200);
            } else {
                return response()->json(["code" => 404, "message" => "User not found!"], 404);
            }
        } else {
            return response()->json(["code" => 404, "message" => "User not found!"], 404);
        }
    }

    /**
     * Handle the insert or update request for recharge.
     *
     * @param RechargeRequest $request
     * @return JsonResponse
     */
    public function insert(RechargeRequest $request): JsonResponse
    {
        if ($request->ajax()) {
            return response()->json(['message' => 'Request is AJAX'], 200);
        }

        $dueDate = $this->calculateDueDate($request->validity);

        $subscriber = $this->getOrCreateSubscriber($request);

        if ($request->id) {
            return $this->updateRecharge($request, $subscriber, $dueDate);
        } else {
            return $this->createRecharge($request, $subscriber, $dueDate);
        }
    }

    /**
     * Calculate the due date based on the validity.
     *
     * @param int $validity
     * @return string
     */
    private function calculateDueDate(int $validity): string
    {
        return Carbon::now()->addDays($validity)->format('Y-m-d H:i:s');
    }

    /**
     * Get the existing subscriber or create a new one if not found.
     *
     * @param RechargeRequest $request
     * @return Subscribers
     */
    private function getOrCreateSubscriber(RechargeRequest $request): Subscribers
    {
        return Subscribers::firstOrCreate(
            ['id' => $request->user_id, 'number' => $request->number],
            [
                'name' => $request->user,
                'number' => $request->number,
                'carrier' => $request->carrier,
                'status' => 'active',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        );
    }

    /**
     * Update an existing recharge record.
     *
     * @param RechargeRequest $request
     * @param Subscribers $subscriber
     * @param string $dueDate
     * @return JsonResponse
     */
    private function updateRecharge(RechargeRequest $request, Subscribers $subscriber, string $dueDate): JsonResponse
    {
        $recharge = Recharge::find($request->id);

        if ($recharge) {
            $dueDate = Carbon::parse($recharge->created_at)->addDays($request->days)->format('Y-m-d H:i:s');
        }

        $data = $this->prepareCrudData($request, $subscriber, $dueDate, true);

        try {
            $updated = $recharge->update($data);
            if ($updated) {
                return response()->json(['message' => 'Record updated successfully'], 200);
            }
            return response()->json(['message' => 'Failed to update record!'], 403);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to update record!'], 422);
        }
    }

    /**
     * Create a new recharge record.
     *
     * @param RechargeRequest $request
     * @param Subscribers $subscriber
     * @param string $dueDate
     * @return JsonResponse
     */
    private function createRecharge(RechargeRequest $request, Subscribers $subscriber, string $dueDate): JsonResponse
    {
        $data = $this->prepareCrudData($request, $subscriber, $dueDate);

        try {
            $created = Recharge::create($data);
            if ($created) {
                return response()->json(['message' => 'Record inserted successfully'], 200);
            }
            return response()->json(['message' => 'Failed to insert record!'], 403);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to insert record!'], 422);
        }
    }

    /**
     * Prepare CRUD data for recharge insert or update.
     *
     * @param RechargeRequest $request
     * @param Subscribers $subscriber
     * @param string $dueDate
     * @param bool $isUpdate
     * @return array
     */
    private function prepareCrudData(RechargeRequest $request, Subscribers $subscriber, string $dueDate, bool $isUpdate = false): array
    {
        $timestamps = $isUpdate ? ['updated_at' => Carbon::now()->format('Y-m-d H:i:s')] : ['created_at' => Carbon::now()->format('Y-m-d H:i:s')];

        return array_merge([
            'subscriber_id' => $subscriber->id,
            'amount' => $request->amount,
            'validity' => $request->validity,
            'due_date' => $dueDate,
            'is_notification_active' => $request->activeNotification ? 'yes' : 'no',
        ], $timestamps);
    }


    public function edit($id = null)
    {
        if ($id) {
            $recharge = Recharge::findOrFail($id);
            if ($recharge) {
                return response()->json(['message' => 'Data found', 'data' => $recharge], 200);
            }
        }
        return response()->json([['message' => 'Please provide id']], 404);
    }

    public function changeStatus(Request $request)
    {
        if (!empty($request->all())) {
            $id = $request->id;
            $data = Recharge::where(['id' => $id])->first();

            if (!empty($data)) {
                $request->status == 1 ? $status = 'no' : $status = 'yes';
                $update = Recharge::where(['id' => $id])->update(['is_notification_active' => $status, 'updated_at' => date('Y-m-d H:i:s')]);

                if ($update)
                    return response()->json(['code' => 200]);
                else
                    return response()->json(['code' => 201]);
            } else {
                return response()->json(['code' => 202]);
            }
        } else {
            return response()->json(['code' => 203]);
        }
    }

    public function repeatRecharge(Request $request)
    {
        if ($request->id) {
            $recharge = Recharge::findOrFail($request->id);
            if ($recharge) {
                $currentDate = Carbon::now();
                // Add the number of days given in the request to the current date
                $dueDate = $currentDate->copy()->addDays($recharge->validity)->format('Y-m-d H:i:s');
                $crud = [
                    'subscriber_id' => $recharge->subscriber_id,
                    'amount' => $recharge->amount,
                    'validity' => $recharge->validity,
                    'due_date' => $dueDate,
                    'is_notification_active' => $recharge->is_notification_active,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s')
                ];

                $data = Recharge::insert($crud);
                if ($data) {
                    Recharge::where(['id' => $request->id])->update(['notification_status' => 'complete', 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]);
                    return response()->json(['message' => 'Recharged successfully.'], 200);
                }
                return response()->json(['message' => 'Failed to recharge, Please enter this record manually.'], 403);
            }

            return response()->json(['message' => 'Id not found!'], 404);
        } else {
            return response()->json(['message' => 'Id not found!'], 404);
        }
    }
}
