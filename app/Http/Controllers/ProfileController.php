<?php

namespace App\Http\Controllers;

use App\User;
use App\Traits\Base;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileFormRequest;
use App\Http\Requests\PasswordFormRequest;

class ProfileController extends Controller
{
    use UploadAble, Base;

    public function my_profile(){
        // authorized
        $this->setPageTitle('My Profile','My Profile');
        $breadcrumb = ['My Profile'=>''];
        return view('backend.profile.index',['breadcrumb'=>$breadcrumb]);
    }

    public function my_profile_update(ProfileFormRequest $request){
        if ($request->ajax()) {
            if (Gate::allows('app.profile.update')) {
                $collecion = collect($request->validated());
                $avatar = !empty($request->old_avatar) ? $request->old_avatar : null;
                if($request->hasFile('image')){
                    $avatar = $this->upload_file($request->file('image'),USER_AVATAR_PATH);
                }
                $collecion = $collecion->merge(compact('avatar'));
                $result = User::find(auth()->user()->id)->update($collecion->all());
                if($result){
                    if($request->hasFile('image')){
                        $this->delete_file($request->old_avatar,USER_AVATAR_PATH);
                    }
                    $output = ['status'=>'success','message'=>'Profile Data Updated Successfully'];
                }else{
                    if($request->hasFile('avatar')){
                        $this->delete_file($avatar,USER_AVATAR_PATH);
                    }
                    $output = ['status'=>'error','message'=>'Failed to Update Profile Data'];
                }
                return response()->json($output);
            }else{
                return response()->json(['status'=>'error','message'=>'Unauthorized Block!']);
            }
        }
    }

    public function change_password(PasswordFormRequest $request){
        if ($request->ajax()) {
            if (auth()->check()) {
                if (Gate::allows('app.password.update')) {
                    $user = Auth::user();
                    if(!Hash::check($request->current_password, $user->password)){
                        $output = ['status'=>'error','message'=>'Current Password Does Not Match!'];
                    }else{
                        $user->password = $request->password;
                        if($user->save()){
                            Auth::logout();
                            $output = ['status'=>'success','message'=>'Password Changed Successfully'];
                        }else{
                            $output = ['status'=>'error','message'=>'Failed to Change Password'];
                        }
                    }
                    return response()->json($output);
                }else{
                    return response()->json(['status'=>'error','message'=>'Unauthorized Block!']);
                }
            }else{
                Auth::logout();
                return response()->json(['status'=>'error','message'=>'Please! Login here.']);
            }
        }
    }
}
