<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FormData;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendUserCreatedMail;
use DataTables;

class UserController extends Controller
{
  public function create()
  {
    $companies = Company::all();
    return view('create',['companies' => $companies]);
  }
  public function store(Request $request)
  {
    try {
      $model = new FormData();
      $model->fullname = $request->post('fullname');
      $model->company_id = $request->post('company_id');
      $model->mobile = $request->post('mobile');
      $model->role = $request->post('role');
      $model->email = $request->post('email');
      $model->password = Hash::make($request->post('password'));
      if ($request->hasFile('inputFile')) {
        $file = $request->file('inputFile');
        $extenstion = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extenstion;
        Log::info('File Upload', [$filename]);
        $file->move('uploads/students/', $filename);
        $model->image = $filename;
      }
      $model->save();
      $password = $request->post('password');
      SendUserCreatedMail::dispatch($model,$password);

      return response()->json([
        'message' => "Data inserted successfully",
        "status" => 200,
        "data" => ['id' => $model->id]
      ], 200);
    } catch (\Exception $e) {
      Log::error($e);
    }
  }
  public function index(Request $request)
  {
    try {

      if ($request->ajax()) {
        if (!empty($request->get('role'))) {
          $data = FormData::where('role', $request->get('role'))->get();
        } else {
          $data = FormData::latest()->get();
        }
        return Datatables::of($data)

          ->addColumn('fullname', function ($data) {
            return $data->fullname;
          })
          ->addColumn('company', function ($data) {
            return $data->company->name; 
          })
          ->addColumn('mobile', function ($data) {
            return $data->mobile;
          })
          ->addColumn('role', function ($data) {
            return $data->role;
          })
          ->addColumn('email', function ($data) {
            return $data->email;
          })
          ->addColumn('image', function ($data) {
            if (empty($data->image)) {
              return 'NA';
            } else {
              return '<img src="/uploads/students/' . $data->image . '" class="avatar-img rounded" style="padding:0px 2px 0px 2px; object-fit:contain" width="100px" height="40px">';
            }
          })
          ->addColumn('action', function ($data) {
            $actionBtn = '<a href="'.route('user.edit', [$data->id]).'" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="' . $data->id . '">Delete</a>';
            return $actionBtn;
          })
          ->rawColumns(['action', 'image'])
          ->make(true);

      }
      return view('index');
    } catch (\Exception $e) {
      Log::error($e);
    }
  }
  public function edit($id){
    $edit_data = FormData::find($id);
    return view('edit', ['edit_data' => $edit_data]);
    }
    public function update($id, Request $request)
    {
      Log::info('Check Data',[$request->all()]);
      // $validator = Validator::make(
      //   $request->all(),
      //   [
      //     'fullname' => 'required | regex : /^[a-zA-Z ]*$/',
      //     'mobile' => 'required',
      //     'email' => 'required',
      //   ],
  
      //   [
      //     'fullname.required' => 'Only letter is accepted.',
      //   ]
      // );
      // if ($validator->fails()) {
  
      //   return back()->withErrors($validator->errors())->withInput();
      // } else {
      try {
      
      $update_data = FormData::find($id);
      $update_data->fullname = $request->post('fullname');
      $update_data->mobile = $request->post('mobile');;
      $update_data->role = $request->post('role');
      $update_data->email = $request->post('email');

      if ($request->hasFile('inputFile')) {
        $file = $request->file('inputFile');
        $extenstion = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extenstion;
        Log::info('File Upload', [$filename]);
        $file->move('uploads/students/', $filename);
        $update_data->image = $filename;
      }
      if ($update_data->isDirty()) {
        $update_data->update(['fullname' => $update_data['fullname'], 'mobile' => $update_data['mobile'], 'role' => $update_data['role'], 'email' => $update_data['email'], 'image' => $update_data['image']]);
        return response()->json([
          'message' => "Data updated successfully",
          "status" => 200,
          "data" => ['id' => $update_data->id]
        ], 200);
      } else {
        return response()->json([
          'message' => "No Changed",
          "status" => 200,  
          "data" => ['id' => $update_data->id]
        ], 200);
      }
    }
      catch (\Exception $e) {
        Log::error($e);
      }
    // }
  }
  public function destory($id){
    Log::info("Id",[$id]);
    $delete_data = FormData::find($id);
    $delete_data->delete();
    return response()->json([
      'message' => "Data deleted successfully",
      "status" => 200,
      "data" => ['id' => $delete_data->id]
    ], 200);
  }
  
}
