<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Recharge;
use App\Models\Subscribers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use File;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $thisMonthRecharge = Recharge::geThisMonthRechargeTotal()->count();
        $upcomingRecharge = Recharge::getActiveNotificationData()->count();
        $totalCustomer = Subscribers::getTotalSubscribers();
        $currentTime = Carbon::now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');

        return view("dashboard")->with([
            'thisMonthRecharge' => $thisMonthRecharge,
            'upcomingRecharge'=> $upcomingRecharge,
            'totalCustomer'=> $totalCustomer,
            'currentTime'=> $currentTime,
        ]);
    }

    public function getUpcomingRecharges(Request $request)
    {
        if ($request->ajax()) {
            $data = Recharge::getActiveNotificationData()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($data) {
                    return '';
                })

                ->rawColumns(['action', 'due_date'])
                ->make(true);
        }
    }

    public function profile()
    {
        $user = User::findOrFail(auth()->user()->id);
        return view('profile')->with('user', $user);
    }

    public function profileUpdate(ProfileRequest $request)
    {
        if ($request->ajax()) {
            return true;
        }

        $data = User::findOrFail($request->id);
        if ($data) {
            $filenameToStore = null;
            $crud = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if (!empty($request->password)) {
                $crud['password'] = bcrypt($request->password);
            }

            if (!empty($request->file('customFile'))) {
                $file = $request->file('customFile');
                $filenameWithExtension = $request->file('customFile')->getClientOriginalName();
                $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                $extension = $request->file('customFile')->getClientOriginalExtension();
                $filenameToStore = time() . "_" . $filename . '.' . $extension;

                $folderToUpload = public_path() . '/uploads/users/';

                if (!File::exists($folderToUpload)) {
                    File::makeDirectory($folderToUpload, 0777, true, true);
                }
            }
            $crud["profile"] = $filenameToStore;

            try {
                $user = User::where('id', $request->id)->update($crud);
                if ($user) {
                    if (!empty($request->file('customFile'))) {
                        $file->move($folderToUpload, $filenameToStore);
                    }

                    $existingFile = public_path() . '/uploads/users/' . $data->profile;

                    if (File::exists($existingFile) && $data->profile != $filenameToStore) {
                        @unlink($existingFile);
                    }

                    return response()->json(['message' => 'Profile updated successfully'], 200);
                }

                return response()->json(['message' => 'Failed to update profile'], 403);
            } catch (\Throwable $th) {
                dd($th);
                return response()->json(['message' => 'Failed to update profile'], 403);
            }
        }
    }

    public function setTheme(Request $request)
    {
        $theme = $request->theme;
        $user = User::where('id', Auth::user()->id)->first();
        if ($user) {
            if ($user->theme != $theme) {
                User::where('id', Auth::user()->id)->update(['theme' => $theme]);
            }
        }
    }
}
