<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use DataTables;

class CompanyController extends Controller
{
    public function create()
    {
        $countries = DB::table('countries')->get();
        return view('company.create', compact('countries'));
    }
    public function getStates($countryId)
    {
        $states = DB::table('states')->where('country_id', $countryId)->get();
        return response()->json($states);
    }

    public function getCities($stateId)
    {
        $cities = DB::table('cities')->where('state_id', $stateId)->get();
        return response()->json($cities);
    }

    public function store(Request $request)
    {
        try {
            $model = new Company();
            $model->name = $request->post('companyname');
            $model->city = $request->post('city');
            $model->state = $request->post('state');
            $model->country = $request->post('country');
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $extenstion = $logo->getClientOriginalExtension();
                $logoname = time() . '.' . $extenstion;
                $logo->move('uploads/company/', $logoname);
                $model->logo = $logoname;
            }
            $model->save();
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
        // Fetch country, state, and city data
        $countries = DB::table('countries')->pluck('name', 'id')->toArray();
        $states = DB::table('states')->pluck('name', 'id')->toArray();
        $cities = DB::table('cities')->pluck('name', 'id')->toArray();

        if ($request->ajax()) {
            $data = Company::latest()->get();
            return Datatables::of($data)
                ->addColumn('companyname', function ($data) {
                    return $data->name;
                })
                ->addColumn('city', function ($data) use ($cities) {
                    return $cities[$data->city] ?? 'N/A'; // Map city ID to name
                })
                ->addColumn('state', function ($data) use ($states) {
                    return $states[$data->state] ?? 'N/A'; // Map state ID to name
                })
                ->addColumn('country', function ($data) use ($countries) {
                    return $countries[$data->country] ?? 'N/A'; // Map country ID to name
                })
                ->addColumn('logo', function ($data) {
                    return '<img src="/uploads/company/' . $data->logo . '" class="avatar-img rounded" style="padding:0px 2px 0px 2px; object-fit:contain" width="100px" height="80px">';
                })
                ->addColumn('action', function ($data) {
                    $actionBtn = '<a href="'.route('company.edit', [$data->id]).'" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="' . $data->id . '">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'logo'])
                ->make(true);
        }

        return view('company.index');
    } catch (\Exception $e) {
        Log::error($e);
    }
}
public function edit($id)
{
    $company = Company::findOrFail($id);
    $countries = DB::table('countries')->pluck('name', 'id')->toArray();
    $states = DB::table('states')->pluck('name', 'id')->toArray();
    $cities = DB::table('cities')->pluck('name', 'id')->toArray();
    
return view('company.edit', compact('company', 'countries', 'states', 'cities'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'companyname' => 'required|max:20',
        'country' => 'required',
        'state' => 'required',
        'city' => 'required',
        'logo' => 'nullable|mimes:jpeg,png,svg|max:2048', // 2MB max size
    ]);

    $company = Company::findOrFail($id);
    $company->name = $request->input('companyname');
    $company->country = $request->input('country');
    $company->state = $request->input('state');
    $company->city = $request->input('city');

    if ($request->hasFile('logo')) {
        $logo = $request->file('logo');
        $extenstion = $logo->getClientOriginalExtension();
        $logoname = time() . '.' . $extenstion;
        $logo->move('uploads/company/', $logoname);
        $company->logo = $logoname;
    }

    $company->save();

    return response()->json([
        'status' => 200,
        'message' => 'Company updated successfully.',
    ]);
}

public function destory($id){
    $delete_data = Company::find($id);
    $delete_data->delete();
    return response()->json([
      'message' => "Company deleted successfully",      
      "status" => 200,
      "data" => ['id' => $delete_data->id]
    ], 200);
  }
}