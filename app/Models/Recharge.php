<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Recharge extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscriber_id',
        'amount',
        'validity',
        'due_date',
        'is_notification_active',
        'notification_status'
    ];

    public static function getDataTableData($request)
    {
        $data = self::select('recharges.id', 'subscribers.name', 'subscribers.carrier', 'recharges.amount', 'recharges.is_notification_active')
            ->join('subscribers', 'subscribers.id', '=', 'recharges.subscriber_id')
            ->orderBy('recharges.id', 'desc');
        $data->get();
        return $data;
    }

    public static function geThisMonthRechargeTotal()
    {
        // Get the start and end dates of the current month
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $subQuery = self::select('subscriber_id', DB::raw('MAX(id) as last_recharge_id'))
            ->groupBy('subscriber_id');

        $data = self::select('recharges.id', 'subscribers.name', 'subscribers.carrier', 'recharges.amount', 'recharges.is_notification_active', 'recharges.due_date')
            ->join('subscribers', 'subscribers.id', '=', 'recharges.subscriber_id')
            ->joinSub($subQuery, 'last_recharges', function ($join) {
                $join->on('recharges.id', '=', 'last_recharges.last_recharge_id');
            })
            ->whereBetween('recharges.due_date', [$startDate, $endDate]);

        return $data;
    }

    public static function getActiveNotificationData()
    {
        // Get the start and end dates of the current month
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        $subQuery = self::select('subscriber_id', DB::raw('MAX(id) as last_recharge_id'))
            ->groupBy('subscriber_id');

        $data = self::select('recharges.id', 'subscribers.name', 'subscribers.carrier', 'recharges.amount', 'recharges.is_notification_active', 'recharges.due_date')
            ->join('subscribers', 'subscribers.id', '=', 'recharges.subscriber_id')
            ->joinSub($subQuery, 'last_recharges', function ($join) {
                $join->on('recharges.id', '=', 'last_recharges.last_recharge_id');
            })
            ->where('is_notification_active', '=', 'yes')
            ->where('notification_status', '!=', 'complete')
            ->whereBetween('recharges.due_date', [$startDate, $endDate])
            ->orderBy('recharges.id', 'desc');

        return $data;
    }
}
