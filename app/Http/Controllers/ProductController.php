<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Traits\Base;
use App\Models\Product;
use App\Models\Category;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ImportFormRequest;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    use UploadAble, Base;

    /**
     * store or update request data
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_or_update($request){
        $collection = collect($request->validated());
        $created_at = $updated_at = Carbon::now();
        $created_by = $updated_by = auth()->user()->name;

        if($request->update_id){
            $collection = $collection->merge(compact('updated_by','updated_at'));
        }else{
            $collection = $collection->merge(compact('created_by','created_at'));
        }

        return Product::updateOrCreate(['id'=>$request->update_id],$collection->all());
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $user = User::where('status', 1)->pluck('name', 'id');
        $category = Category::where('status',1)->pluck('name','id');

        if($request->ajax()){
            $getData = Product::with('category')->orderBy('id','desc');
            return DataTables::eloquent($getData)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if (!empty($request->search)) {
                        $query->where('name', 'LIKE', "%$request->search%");
                            // ->orWhere('phone_number', 'LIKE', "%$request->search%");
                    }
                    if(!empty($request->category_id)){
                        $query->where('category_id',$request->category_id);
                    }
                    if(!empty($request->user_id)){
                        $query->where('user_id',$request->created_by);
                    }
                    if(!empty($request->status)){
                        $query->where('status',$request->status);
                    }
                    if(!empty($request->name)){
                        $query->where('name',$request->name);
                    }
                    if(!empty($request->start_date) && !empty($request->end_date)){
                        $query->whereDate('created_at','>=',$request->start_date)
                        ->whereDate('created_at','<=',$request->end_date);
                    }
                })

                ->addColumn('category_id', function($row){
                    return $row->category->name ?? '';
                })
                ->addColumn('status', function($row){
                    return change_status($row->id,$row->status,$row->name);
                })
                ->addColumn('created_at', function($row){
                    return date('Y-m-d', strtotime($row->created_at));
                })
                ->addColumn('bulk_check', function($row){
                    return table_checkbox($row->id);
                })
                ->addColumn('action', function($row){
                    $action = '<div class="d-flex align-items-center justify-content-end">';
                    $action .= '<button type="button" class="btn-style btn-style-edit edit_data ml-1" data-id="' . $row->id . '"><i class="fa fa-edit"></i></button>';
                    $action .= '<button type="button" class="btn-style btn-style-danger delete_data ml-1" data-id="' . $row->id . '" data-name="' . $row->role_name . '"><i class="fa fa-trash"></i></button>';
                    $action .= '</div>';
                    return $action;
                })
                ->rawColumns(['bulk_check','status','action'])
                ->make(true);
        }

        $this->setPageTitle('Product','Product List');
        $breadcrumb = ['Product'=>''];
        return view('backend.product.index', ['breadcrumb'=>$breadcrumb,'category'=>$category,'user'=>$user]);
    }

    /**
     * store or update user data
     *
     * @return \App\Http\RequestsCategoryFormRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrStore(ProductRequest $request){
       
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
            $user = Product::find($request->id);
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
            $data = Product::find($request->id);
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
            $result = Product::find($request->id);
            if($result){
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
            $result = Product::whereIn('id',$request->ids);
            if($result){
                Product::destroy($request->ids);
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
            $result = Product::find($request->id);
            if ($result) {
                $result->update(['status'=>$request->status]);
                return $this->status_message($result);
            }else{
                return $this->response_json('error','Failed to change status',null,204);
            }
        }
    }
}
