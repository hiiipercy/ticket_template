<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Traits\Base;
use App\Traits\UploadAble;
use App\Imports\UserImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\UserFormRequest;
use App\Http\Requests\ImportFormRequest;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use UploadAble, Base;

    /**
     * store or update request data
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_or_update($request){
        $collection = collect($request->validated())->except(['password','password_confirmation']);
        $created_at = $updated_at = Carbon::now();
        $created_by = $updated_by = auth()->user()->name;
        $image = $request->old_image;
        if($request->hasFile('image')){
            $image = $this->upload_file($request->file('image'),USER_AVATAR_PATH);
            if(!empty($request->old_image)){
                $this->delete_file($request->old_image,USER_AVATAR_PATH);
            }
        }

        if($request->update_id){
            $collection = $collection->merge(compact('image','updated_by','updated_at'));
        }else{
            $collection = $collection->merge(compact('image','created_by','created_at'));
        }

        if($request->password){
            $collection = $collection->merge(with(['password' => Hash::make($request->password)]));
        }
        return User::updateOrCreate(['id'=>$request->update_id],$collection->all());
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        if($request->ajax()){
            $getData = User::orderBy('id','desc');
            return DataTables::eloquent($getData)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if (!empty($request->search)) {
                        $query->where('name', 'LIKE', "%$request->search%")
                            ->orWhere('email', 'LIKE', "%$request->search%")
                            ->orWhere('mobile_no', 'LIKE', "%$request->search%");
                            // ->orWhere(function($query) use ($request) {
                            //     $query->whereHas('role_type', function($query) use ($request) {
                            //         $query->where('name', 'LIKE', "%$request->search%");
                            //     });
                            // });
                    }
                })
                ->addColumn('role_type', function($row){
                    return $row->role_type;
                })
                ->addColumn('image', function($row){
                    return table_image(USER_AVATAR_PATH,$row->image,$row->name);
                })
                ->addColumn('created_at', function($row){
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('bulk_check', function($row){
                    return table_checkbox($row->id);;
                })
                ->addColumn('action', function($row){
                    $action = '<div class="d-flex align-items-center justify-content-end">';
                    // $action .= '<button type="button" class="btn-style btn-style-view view_data ml-1" data-id="' . $row->id . '"><i class="fa fa-eye"></i></button>';
                    $action .= '<button type="button" class="btn-style btn-style-edit edit_data ml-1" data-id="' . $row->id . '"><i class="fa fa-edit"></i></button>';
                    $action .= '<button type="button" class="btn-style btn-style-danger delete_data ml-1" data-id="' . $row->id . '" data-name="' . $row->role_name . '"><i class="fa fa-trash"></i></button>';
                    
                    $action .= '</div>';

                    return $action;
                })
                ->rawColumns(['bulk_check','status','role_type','action','image'])
                ->make(true);
        }

        $this->setPageTitle('Users','User List');
        $breadcrumb = ['Users'=>''];
        return view('backend.user.index', ['breadcrumb'=>$breadcrumb]);
    }

    /**
     * store or update user data
     *
     * @return \App\Http\Requests\UserFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrStore(UserFormRequest $request){
        if ($request->ajax()) {
            $result = $this->store_or_update($request);
            if($result){
                return $this->store_message($result,$request->update_id);
            }else{
                return $this->response_json('error','Data Cannot Save',null,204);
            }
        }
    }

    /**
     * spacified user view
     *
     * @return \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request){
        if ($request->ajax()) {
            $user = User::find($request->id);
            return view('user.details',compact('user'));
        }else{
            return $this->response_json('error',null,null,401);
        }
    }

    /**
     * spacified user edit resource
     *
     * @return \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request){
        if ($request->ajax()) {
            $data = User::find($request->id);
            if($data->count()){
                return $this->response_json('success',null,$data,201);
            }else{
                return $this->response_json('error','No Data Found',null,204);
            }
        }

    }

    public function import(){
        $this->setPageTitle('User Import','User Import');
        $breadcrumb = ['User List'=>route('app.user.index'),'Import'=>''];
        return view('user.import',compact('breadcrumb'));
    }

    /**
     * branch import resource from storage.
     *
     * @param  \App\Http\Requests\ImportFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function importData(ImportFormRequest $request)
    {
        try {
            $file = $request->file('import_file');
            Excel::import(new UserImport($file), $file);
            return $this->response_json('success','Data has been imported successfull.',null,200);
        } catch (\Exception $e) {
            return $this->response_json('error','Server Error!',null,204);
        }
    }

    /**
     * spacified user delete resource
     *
     * @return \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request){
        if ($request->ajax()) {
            $result = User::find($request->id);
            if($result){
                if ($result->avatar) {
                    $this->delete_file($result->avatar,USER_AVATAR_PATH);
                }
                $result->delete();
                return $this->delete_message($result);
            }else{
                return $this->response_json('error','Data Cannot Delete',null,204);
            }
            
        }else{
            return $this->response_json('error',null,null,401);
        }
    }

    /**
     * multiple user destroy resource
     *
     * @return \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request){
        if ($request->ajax()) {
            $result = User::whereIn('id',$request->ids)->select('image')->get();
            if($result){
                foreach ($result as $value) {
                    $this->delete_file($value->image,USER_AVATAR_PATH);
                }
                User::destroy($request->ids);
                return $this->bulk_delete_message($result);
            }else{
                return $this->response_json('error','Data Cannot Delete',null,204);
            }
        }else{
            return $this->response_json('error',null,null,401);
        }
    }

    /**
     * spacified user status update
     *
     * @return \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function statusChange(Request $request){
        if ($request->ajax()) {
            $result = User::find($request->id);
            if ($result) {
                $result->update(['status'=>$request->status]);
                return $this->status_message($result);
            }else{
                return $this->response_json('error','Failed to change status',null,204);
            }
        }
    }
}
