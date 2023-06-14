<?php
namespace Incodiy\Codiy\Models\Admin\System;

use Incodiy\Codiy\Models\Core\Model;

/**
 * Created on Jun 9, 2023
 * 
 * Time Created : 2:11:03 PM
 *
 * @filesource  UserActivity.php
 *
 * @author      wisnuwidi@gmail.com - 2023
 * @copyright   wisnuwidi@gmail.com,
 *              incodiy@gmail.com
 * @email       wisnuwidi@gmail.com
 */
class UserActivity extends Model {
	protected $table     = 'temp_user_activity_monthly';
	protected $guarded   = [];
	
	public function daily_activity() {
		$sql = "
			SELECT
				LEFT(a.created_at, 10) created_date,
				
				b.id user_id,
				c.group_info roles,
				c.group_alias role_location,
				
				a.username,
				a.user_fullname,
				a.user_email,
				
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', d.module_name) ORDER BY a.created_at DESC), ']') module_name,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', CONCAT(d.route_path, '.', a.page_info)) ORDER BY a.created_at DESC), ']') route_path,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', a.page_info) ORDER BY a.created_at DESC), ']') page_info,
				
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', a.ip_address) ORDER BY a.created_at DESC), ']') ip_address,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', a.user_agent) ORDER BY a.created_at DESC), ']') user_agent,
				
				MIN(a.created_at) datetime_start,
				MAX(a.created_at) datetime_end,
				
				MIN(RIGHT(a.created_at, 8)) time_start,
				MAX(RIGHT(a.created_at, 8)) time_end,
				COUNT(DISTINCT a.created_at) time_hits,
				
				TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)) AS time_length,
				
				CONCAT (
					FLOOR(MOD (TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)), 60) / 3600 / 24), ' Days, ',
					FLOOR(MOD (TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)), 3600*24) / 3600), ' Hours, ',
					FLOOR(MOD (TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)), 3600) / 60), ' Minutes, ',
					FLOOR(MOD (TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)), 60)), ' Seconds'
				) AS time_length_detail,
				
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', LEFT(a.created_at, 19)) ORDER BY a.created_at DESC), ']') datetime_refreshed,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', RIGHT(a.created_at, 8)) ORDER BY RIGHT(a.created_at, 8) DESC), ']') time_refreshed,
				
				b.active user_status,
				b.alias user_info,
				
				CURRENT_DATE insert_date,
				CURRENT_TIME insert_time
				
			FROM mantra_web.`log_activities` a
			
			LEFT JOIN mantra_web.users b ON a.user_id = b.id
			LEFT JOIN mantra_web.base_group c ON a.user_group_id = c.id AND c.id >= 4
			LEFT JOIN mantra_web.base_module d ON a.route_path = CONCAT(d.route_path, '.', a.page_info)
			
			WHERE c.group_alias IS NOT NULL AND b.id IS NOT NULL
			GROUP BY LEFT(a.created_at, 10), c.group_info, c.group_alias, b.id, b.username, b.fullname, d.module_name, a.page_info, d.route_path, a.ip_address, a.user_agent, b.email, b.active, b.alias
			ORDER BY LEFT(a.created_at, 10) DESC, c.group_info, c.group_alias DESC, b.username, a.module_name, a.page_info 
		";
		
		return diy_query($sql, 'SELECT');
	}
	
	public function montly_activity() {
		$sql = "
			SELECT
				LEFT(a.created_at, 7) period,
				
				b.id user_id,
				c.group_info roles,
				c.group_alias role_location,
				
				b.username,
				b.fullname,
				b.email user_email,
				
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', d.module_name) ORDER BY a.created_at DESC), ']') module_name,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', CONCAT(d.route_path, '.', a.page_info)) ORDER BY a.created_at DESC), ']') route_path,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', a.page_info) ORDER BY a.created_at DESC), ']') page_info,
				
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', a.ip_address) ORDER BY a.created_at DESC), ']') ip_address,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', a.user_agent) ORDER BY a.created_at DESC), ']') user_agent,
				
				MIN(a.created_at) start_access,
				MAX(a.created_at) last_access,
				
				MIN(RIGHT(a.created_at, 8)) time_start,
				MAX(RIGHT(a.created_at, 8)) time_end,
				COUNT(DISTINCT a.created_at) time_hits,
				
				TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)) AS time_length,
				
				CONCAT (
					FLOOR(MOD (TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)), 60) / 3600 / 24), ' Days, ',
					FLOOR(MOD (TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)), 3600*24) / 3600), ' Hours, ',
					FLOOR(MOD (TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)), 3600) / 60), ' Minutes, ',
					FLOOR(MOD (TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)), 60)), ' Seconds'
				) AS length_days,
				
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', LEFT(a.created_at, 19)) ORDER BY a.created_at DESC), ']') datetime_refreshed,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', RIGHT(a.created_at, 8)) ORDER BY RIGHT(a.created_at, 8) DESC), ']') time_refreshed,
				
				CASE
					WHEN b.active = 0 THEN 'Disabled'
					ELSE 'Active'
				END user_status,
				b.alias user_info,
				
				CURRENT_DATE insert_date,
				CURRENT_TIME insert_time
				
			FROM mantra_web.`log_activities` a
			
			LEFT JOIN mantra_web.users b ON a.user_id = b.id
			LEFT JOIN mantra_web.base_group c ON a.user_group_id = c.id AND c.id >= 4
			LEFT JOIN mantra_web.base_module d ON a.route_path = CONCAT(d.route_path, '.', a.page_info)
			
			WHERE c.group_alias IS NOT NULL AND b.id IS NOT NULL
			GROUP BY LEFT(a.created_at, 7), c.group_info, c.group_alias, b.id, b.username, b.fullname, b.email, b.active, b.alias
			ORDER BY LEFT(a.created_at, 7) DESC, c.group_info, c.group_alias DESC, b.username, a.module_name, a.page_info
		";
		
		diy_temp_table($this->table, $sql);
	}
}