<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Models\Subscribers;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Subscribers::select('id', 'name', 'carrier', 'status')
                ->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return ' <div class="btn-group btn-sm">
                                        <a href="' . route('users.view', ['id' => base64_encode($data->id)]) . '" class="btn btn-default btn-xs">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="' . route('users.edit', ['id' => base64_encode($data->id)]) . '" class="btn btn-default btn-xs">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="javascript:;" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-bars"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="active" data-old_status="' . $data->status . '" data-id="' . base64_encode($data->id) . '">Active</a></li>
                                            <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="inactive" data-old_status="' . $data->status . '" data-id="' . base64_encode($data->id) . '">Inactive</a></li>
                                            <li><a class="dropdown-item" href="javascript:;" onclick="change_status(this);" data-status="deleted" data-old_status="' . $data->status . '" data-id="' . base64_encode($data->id) . '">Delete</a></li>
                                        </ul>
                                    </div>';
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 'active')
                        return 1;
                    else if ($data->status == 'inactive')
                        return 2;
                })

                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view("users.index");
    }

    public function create()
    {
        return view('users.add');
    }

    public function insert(SubscriberRequest $request)
    {
        if ($request->ajax()) {
            return true;
        }
        $curd = [
            'name' => $request->name,
            'number' => $request->number,
            'carrier' => $request->carrier,
        ];
        try {
            $subscriber = Subscribers::create($curd);
            if ($subscriber) {
                return response()->json(['status' => 200, 'message' => 'Record Inserted Successfully']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 422, 'message' => 'Failed to insert record!']);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $subscriber = Subscribers::findOrFail($id);
        if ($subscriber) {
            return view('users.edit')->with('subscriber', $subscriber);
        }
        return redirect()->route('users')->with(['error' => 'User not found!']);
    }

    public function update(SubscriberRequest $request)
    {
        if ($request->ajax()) {
            return true;
        }

        if (!empty($request->all())) {
            $crud = [
                'name' => $request->name,
                'number' => $request->number,
                'carrier' => $request->carrier,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $update = Subscribers::where(['id' => $request->id])->update($crud);

            if ($update) {
                return redirect()->route('users')->with('success', 'Record updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update record')->withInput();
            }
        } else {
            return redirect()->back()->with('error', 'Something went wrong')->withInput();
        }
    }

    public function change_status(Request $request)
    {
        if (!$request->ajax()) {
            exit('No direct script access allowed');
        }

        if (!empty($request->all())) {
            $id = $request->id;
            $status = $request->status;

            $data = Subscribers::where(['id' => $id])->first();

            if (!empty($data)) {
                if ($status == 'deleted')
                    $update = Subscribers::where('id', $id)->delete();
                else
                    $update = Subscribers::where(['id' => $id])->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);

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
}
