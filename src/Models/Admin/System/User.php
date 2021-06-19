<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable {
	use Notifiable;
	use SoftDeletes;
	
	public $groupInfo;
	
	/**
	 * The attributes that are mass assignable.
	 * 
	 * @var array
	 *//* 
	protected $fillable = [ 
		'name', 
		'email', 
		'password'
	];
	 */
	/**
	 * Bypassing all fields can be insert with data
	 * 
	 * @var array
	 */
	protected $guarded	= [];
	
	/**
	 * The attributes that should be hidden for arrays.
	 * 
	 * @var array
	 */
	protected $hidden	= [ 
		'password', 
		'remember_token'
	];
	
	protected $dates	= ['deleted_at'];
		
	/**
	 * Get Data Relation Group From User Group Table [ base_user_group ]
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function group() {
		return $this->belongsToMany(Group::class, 'base_user_group');
	}
	
	public function relation() {
		return $this->belongsToMany(Group::class, 'base_user_group');
	}
	
	/* 
	public function relations() {
		return $this->belongsToMany(Group::class, 'base_user_group');
	}
	 */
	
	/**
	 * Print Out Group Info Logged In
	 * 
	 * @return array
	 */
	public function groupInfo() {
		foreach ($this->group as $groupInfo) {
			return $groupInfo->getAttributes();
		}
	}
	
	/**
	 * Check Group Name From User Logged In
	 * 
	 * @param string $group_name
	 * 
	 * @return boolean
	 */
	public function hasGroup($group_name) {
		foreach ($this->group as $group) {
			if ($group->group_name === $group_name) {
				return true;
			}
			
			return false;
		}
	}
	
	public function getUserInfo($filter = false, $get = true) {
		$platform_key = get_config('settings.platform_key');
		$f1 = 'users.id';
		$f2 = '!=';
		$f3 = 0;
		
		if (false !== $filter) {
			$filter_count = count($filter);
			if ($filter_count < 3) {
				$f1 = $filter[0];
				$f2 = $filter[1];
			} else {
				$f1 = $filter[0];
				$f2 = $filter[1];
				$f3 = $filter[2];
			}
		}
		
		$user_info = DB::table('users')
			->select('users.*', 'base_user_group.group_id', 'base_group.group_name', 'base_group.group_info', "base_group.{$platform_key}")
			->join('base_user_group', 'users.id', '=', 'base_user_group.user_id')
			->join('base_group', 'base_group.id', '=', 'base_user_group.group_id')
			->where($f1, $f2, $f3);
		
		if (true === $get) {
			return $user_info->get();
		} else {
			return $user_info;
		}
	}
}
