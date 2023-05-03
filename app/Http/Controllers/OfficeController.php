<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\IDP;
use App\Models\Doptor;
use App\Jobs\Doptor\SyncDepartments;
use App\Jobs\Doptor\SyncHR;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offices = Doptor::paginate(10);
        return view('settings.office.list', compact('offices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function create()
    {
        $office_layers = IDP::get('/organogram/layers/');

        $office_layers = $office_layers->collect()
            ->mapWithKeys(function ($layer) {
                return [$layer['id'] => $layer['name']['bn']];
            })
            ->toArray();

        // return view('settings.office.on_board', compact('office_layers'));
        return $office_layers;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function getOfficeListByLayers($officeId = null)
    {
        $office_layers = IDP::get('/organogram/layers/' . $officeId . '/offices');

        return response($office_layers->collect()
            ->map(function ($office) {
                return [
                    'id' => $office['id'],
                    'name' => $office['name']['bn']
                ];
            })
            ->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOfficeUnitsByOffice($officeId = null)
    {
        $units = IDP::get('/organogram/office/' . $officeId . '/units');
        //
        return response($units->collect()
            ->map(function ($office) {
                return [
                    'id' => $office['id'],
                    'name' => $office['name']['bn']
                ];
            })
            ->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Doptor $doptor)
    {
        if ($request->office_id) {
            $units = IDP::get('/organogram/office/' . $request->office_id . '/units');
            $units = collect($units->json())
                ->where('office.id', $request->office_id)
                ->first();

            if ($units && isset($units['office'])) {
                $office = (object)$units['office'];
                //
                $doptor = $doptor->updateOrCreate(['id' => $office->id], [
                    "id"      => $office->id,
                    "name_en" => $office->name['en'],
                    "name_bn" => $office->name['bn']
                ]);
                $this->syncHR($doptor);
            }
        }
        // return redirect()->route('admin.settings.office.list');
        return redirect('/admin/doptors');
    }

    //helper functions
    public function addDefaultRoles($doptor)
    {
        $defaultsRoles = SpatieRole::defaultRoles();
        $roles = collect($defaultsRoles)
            ->map(function ($role) use ($doptor) {
                return [
                    'name' => $role,
                    'label' => $role,
                    'guard_name' => 'web'
                ];
            })
            ->toArray();
        // todo: need to convert to create or update as upsert not fire any event
        SpatieRole::upsert($roles, ['name', 'guard_name', 'doptor_id']);
    }

    public static function syncHR($doptor)
    {
        SyncHR::dispatch($doptor);
    }

    public function sync(Request $request)
    {
        $doptor = Doptor::find($request->id);
        $this->syncHR($doptor);
        return back();
    }
}