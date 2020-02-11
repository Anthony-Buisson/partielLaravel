<?php
namespace App;
use DB;
class Rights {
    public static function is($user_id, $role_name){
        $roles = DB::table('roles')
            ->join('role_user', 'role_user.role_id', '=', 'roles.id')
            ->where([['role_user.user_id', '=', $user_id],['roles.name','=', $role_name]])
            ->get();
        return count($roles) > 0;
    }
    public static function can($user_id, $permission_name, $user = null){
        $user = $user ?? User::find($user_id)->roles();
         foreach ($user->roles as $role){
            foreach ($role->permissions as $permission) {
                if ($permission->name === $permission_name) return true;
            }
        }
        return false;
    }
    public static function canAll($user_id, $permissions_names){
        $user = User::find($user_id);
        foreach ($permissions_names as $permission){
            if(!Rights::can($user_id, $permission, $user)) return false;
        }
        return true;
    }
    public static function canAtLeast($user_id, $permissions_names){
        $user = User::find($user_id);
        foreach ($permissions_names as $permission){
            if(Rights::can($user_id, $permission, $user)) return true;
        }
        return false;
    }
    public static function authIs($role_name){
        if(!\Auth::check()) return false;
        $user = \Auth::user();
        foreach ($user->roles as $role){
            if($role->name === $role_name) return true;
        }
        return false;
    }
    public static function authCan($permission_name, $user = null){
        if(!\Auth::check()) return false;
        $user = $user ?? \Auth::user();
        foreach ($user->roles as $role){
            foreach ($role->permissions as $permission) {
                if ($permission->name === $permission_name) return true;
            }
        }
        return false;
    }
    public static function authCanAll($permissions_names){
        if(!\Auth::check()) return false;
        $user = \Auth::user();
        foreach ($permissions_names as $permission){
            if(!Rights::authCan($permission, $user)) return false;
        }
        return true;
    }
    public static function authCanAtLeast($permissions_names){
        if(!\Auth::check()) return false;
        $user = \Auth::user();
        foreach ($permissions_names as $permission){
            if(Rights::authCan($permission, $user)) return true;
        }
        return false;
    }
}
