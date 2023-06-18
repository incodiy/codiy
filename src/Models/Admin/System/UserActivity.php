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
	
	public function __construct() {
		parent::__construct();
	}
	
	public function montly_activity() {
		$sql = "
			SELECT
				monthly_activity.monthly_activity,
				d.id group_id,
				b.id user_id,
				
				d.group_info role_group,
				d.group_alias role_location,
				
				b.username,
				b.fullname,
				monthly_activity.user_email,
				
				monthly_activity.first_access,
				monthly_activity.last_access,
				monthly_activity.online_duration,
				monthly_activity.offline_duration,
				monthly_activity.offline_duration_day,
				monthly_activity.login_counters,
				monthly_activity.logout_counters,
				monthly_activity.hit_activity,
				CASE
					WHEN b.active = 0 THEN 'Disabled'
					ELSE 'Active'
				END user_status
			FROM (

				# GET MONTHLY DATA ACTIVITY
				SELECT
					LEFT(daily_activity, 7) monthly_activity,
					group_id,
					user_id,
					user_email,
					MIN(first_access) first_access,
					MAX(last_access) last_access,
					CONCAT( 
						FLOOR(TIME_FORMAT(SEC_TO_TIME(SUM(online_duration)), '%H') / 24), ' Days ',
						FLOOR( MOD(SUM(online_duration), 3600*24) / 3600), ' Hours ',
						FLOOR( MOD(SUM(online_duration), 3600) / 60), ':',
						MOD( SUM(online_duration), 60) )
					online_duration,
					offline_duration,
					offline_duration_day,
					SUM(login_counters) login_counters,
					SUM(logout_counters) logout_counters,
					SUM(hit_activity) hit_activity
				FROM (

					# GET DAILY DATA ACTIVITY
					SELECT
						LEFT(a.created_at, 10) daily_activity,
						a.user_group_id group_id,
						a.user_id,
						a.user_email,
						MIN(a.created_at) first_access,
						MAX(a.created_at) last_access,
						TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)) AS online_duration,
						CASE
							WHEN LEFT(a.created_at, 10) >= LEFT(NOW(), 7)
								THEN 
									CONCAT (
										TIMESTAMPDIFF(DAY, MAX(a.created_at), NOW()), ' Days, ',
										FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), NOW()), 3600*24) / 3600), ' Hours, ',
										FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), NOW()), 3600) / 60), ':',
										FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), NOW()), 60))
									)
							ELSE
								CONCAT (
									TIMESTAMPDIFF(DAY, MAX(a.created_at), LAST_DAY(LEFT(a.created_at, 10)) ), ' Days, ',
									FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') ), 3600*24) / 3600), ' Hours, ',
									FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') ), 3600) / 60), ':',
									FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') ), 60))
								)
						END offline_duration,
						CASE
							WHEN LEFT(a.created_at, 10) >= LEFT(NOW(), 7) THEN TIMESTAMPDIFF(DAY, MAX(a.created_at), NOW())
							ELSE TIMESTAMPDIFF(DAY, MAX(a.created_at), LAST_DAY(LEFT(a.created_at, 10)) )
						END offline_duration_day,
						CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') from_last_date,
						(
							SELECT COUNT(n.page_info) 
							FROM log_activities n 
							WHERE n.page_info = 'login_processor'
								AND LEFT(n.created_at, 10) = LEFT(a.created_at, 10)
								AND n.user_id = a.user_id 
						) login_counters,
						(
							SELECT COUNT(n.page_info) 
							FROM log_activities n 
							WHERE n.page_info = 'logout'
								AND LEFT(n.created_at, 10) = LEFT(a.created_at, 10)
								AND n.user_id = a.user_id 
						) logout_counters,
					COUNT(a.page_info) hit_activity
					FROM `log_activities` a
					WHERE user_id > 1 #AND a.page_info IN ('login_processor', 'logout')
					GROUP BY LEFT(a.created_at, 10), a.user_id, a.user_group_id, a.user_email
					ORDER BY LEFT(a.created_at, 10) DESC, a.user_id, a.user_group_id
					# GET DAILY DATA ACTIVITY

				) monthly
				GROUP BY LEFT(daily_activity, 7), user_id, group_id, user_email
				ORDER BY LEFT(daily_activity, 7) DESC, user_id, group_id
				# GET MONTHLY DATA ACTIVITY

			) monthly_activity
			LEFT JOIN mantra_web.users b ON monthly_activity.user_email = b.email
			LEFT JOIN mantra_web.base_user_group c ON c.user_id = b.id
			LEFT JOIN mantra_web.base_group d ON c.group_id = d.id AND d.id >= 4

			WHERE d.group_info IS NOT NULL AND b.username IS NOT NULL

			GROUP BY LEFT(monthly_activity.monthly_activity, 7), b.id, d.id
			ORDER BY LEFT(monthly_activity.monthly_activity, 7) DESC, b.id, d.id;
		";
		diy_temp_table($this->table, $sql, false);
	}
	
	private function getQueryMonthActivities() {
		return "
			SELECT
				LEFT(a.created_at, 7) daily_activity,
				a.user_group_id group_id,
				a.user_id,
				MIN(a.created_at) first_access,
				MAX(a.created_at) last_access,
				CASE
					WHEN LEFT(a.created_at, 7) >= LEFT(NOW(), 7)
						THEN 
							CONCAT (
								TIMESTAMPDIFF(DAY, MAX(a.created_at), NOW()), ' Days, ',
								FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), NOW()), 3600*24) / 3600), ' Hours, ',
								FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), NOW()), 3600) / 60), ' Minutes, ',
								FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), NOW()), 60)), ' Seconds'
							)
						ELSE
							CONCAT (
								TIMESTAMPDIFF(DAY, MAX(a.created_at), LAST_DAY(LEFT(a.created_at, 10)) ), ' Days, ',
								FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') ), 3600*24) / 3600), ' Hours, ',
								FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') ), 3600) / 60), ' Minutes, ',
								FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') ), 60)), ' Seconds'
							)
					END offline_duration,
					CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') from_last_date,
				ROUND(COUNT(page_info)/2) login_counters
			FROM `log_activities` a
			WHERE a.page_info IN ('login_processor', 'logout') AND user_id = 1
			GROUP BY LEFT(a.created_at, 7), a.user_id, a.user_group_id
			ORDER BY LEFT(a.created_at, 7) DESC, a.user_id, a.user_group_id;
		";
	}
	
	private function baseActivityQuery($setDateNode = 10) {
		return "
			# GET DAILY ACTIVITY
			SELECT
				LEFT(log.created_at, {$setDateNode}) date_activity,
				log.user_group_id group_id,
				log.user_id,
				
				# MODULES INFO
				MAX(log.route_path) last_path_opened,
				MAX(log.page_info) last_page_opened,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', log.route_path) ORDER BY log.route_path DESC), ']') path_refreshed,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', log.page_info) ORDER BY log.page_info DESC), ']') page_refreshed,
				
				# CLIENT INFO
				MAX(log.ip_address) last_ip,
				MAX(log.user_agent) last_user_agent,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', log.ip_address) ORDER BY log.ip_address DESC), ']') ip_refreshed,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', log.user_agent) ORDER BY log.user_agent DESC), ']') user_agent_refreshed,
					
				# TIMING
				MIN(log.created_at) start_login,
				MAX(log.created_at) last_login,
				TIMESTAMPDIFF(SECOND, MIN(log.created_at), MAX(log.created_at)) AS active_duration,
				TIMESTAMPDIFF(SECOND, MAX(log.created_at), NOW()) AS offline_duration,
				
				# HITS
				COUNT(DISTINCT log.created_at) total_hits,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', LEFT(log.created_at, 19)) ORDER BY log.created_at DESC), ']') datetime_refreshed,
				CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', RIGHT(log.created_at, 8)) ORDER BY RIGHT(log.created_at, 8) DESC), ']') time_refreshed
				
			FROM (
				# BASE LOG ACTIVITY DATA
				SELECT
					a.user_group_id,
					a.user_id,
					a.created_at,
					a.route_path,
					a.page_info,
					a.ip_address,
					a.user_agent
				FROM `log_activities` a
			) log
			GROUP BY LEFT(log.created_at, {$setDateNode}), log.user_group_id, log.user_id
			ORDER BY LEFT(log.created_at, {$setDateNode}) DESC, log.user_group_id ASC, log.user_id ASC
		";
	}
	
	private function monthlyActivityQuery() {
		return "
			SELECT 
				monthly.date_activity,
				monthly.group_id,
				monthly.user_id,
				
				monthly.last_path_opened,
				monthly.last_page_opened,
				monthly.path_refreshed,
				monthly.page_refreshed,
				
				monthly.last_ip,
				monthly.last_user_agent,
				monthly.ip_refreshed,
				monthly.user_agent_refreshed,
				
				monthly.start_login,
				monthly.last_login,
				monthly.active_duration,
				monthly.offline_duration,
				CONCAT (
					TIMESTAMPDIFF(DAY, monthly.start_login, monthly.last_login), ' Days, ',
					FLOOR(MOD (monthly.active_duration, 3600*24) / 3600), ' Hours, ',
					FLOOR(MOD (monthly.active_duration, 3600) / 60), ' Minutes, ',
					FLOOR(MOD (monthly.active_duration, 60)), ' Seconds'
				) AS active_duration_info,
				CONCAT (
					TIMESTAMPDIFF(DAY, monthly.last_login, NOW()), ' Days, ',
					FLOOR(MOD (monthly.offline_duration, 3600*24) / 3600), ' Hours, ',
					FLOOR(MOD (monthly.offline_duration, 3600) / 60), ' Minutes, ',
					FLOOR(MOD (monthly.offline_duration, 60)), ' Seconds'
				) AS offline_duration_info,
				
				monthly.total_hits,
				COUNT(DISTINCT monthly.start_login) total_login,
				monthly.datetime_refreshed,
				monthly.time_refreshed
			FROM (
				# GET DAILY ACTIVITY
				SELECT
					LEFT(log.created_at, 7) date_activity,
					log.user_group_id group_id,
					log.user_id,
					
					# MODULES INFO
					MAX(log.route_path) last_path_opened,
					MAX(log.page_info) last_page_opened,
					CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', log.route_path) ORDER BY log.route_path DESC), ']') path_refreshed,
					CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', log.page_info) ORDER BY log.page_info DESC), ']') page_refreshed,
					
					# CLIENT INFO
					MAX(log.ip_address) last_ip,
					MAX(log.user_agent) last_user_agent,
					CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', log.ip_address) ORDER BY log.ip_address DESC), ']') ip_refreshed,
					CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', log.user_agent) ORDER BY log.user_agent DESC), ']') user_agent_refreshed,
						
					# TIMING
					MIN(log.created_at) start_login,
					MAX(log.created_at) last_login,
					TIMESTAMPDIFF(SECOND, MIN(log.created_at), MAX(log.created_at)) AS active_duration,
					TIMESTAMPDIFF(SECOND, MAX(log.created_at), NOW()) AS offline_duration,
					
					# HITS
					COUNT(DISTINCT log.created_at) total_hits,
					CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', LEFT(log.created_at, 19)) ORDER BY log.created_at DESC), ']') datetime_refreshed,
					CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT('data', RIGHT(log.created_at, 8)) ORDER BY RIGHT(log.created_at, 8) DESC), ']') time_refreshed
					
				FROM (
					# BASE LOG ACTIVITY DATA
					SELECT
						a.user_group_id,
						a.user_id,
						a.created_at,
						a.route_path,
						a.page_info,
						a.ip_address,
						a.user_agent
					FROM `log_activities` a
				) log
				GROUP BY LEFT(log.created_at, 7), log.user_group_id, log.user_id
				ORDER BY LEFT(log.created_at, 7) DESC, log.user_group_id ASC, log.user_id ASC
			) monthly 
		";
	}
	
	public function resetLogActivityAddFlag() {
		$sql = "
		# LOGIN
SELECT
	log_activities.user_id,
	log_activities.username,
	log_activities.user_fullname,
	log_activities.user_email,
	log_activities.user_group_id,
	log_activities.user_group_name,
	log_activities.user_group_info,
	'login_processor' route_path,
	'Login' module_name,
	'login_processor' page_info,
	'https://mantra.smartfren.com/login' urli,
	log_activities.method,
	log_activities.ip_address,
	log_activities.user_agent,
	log_activities.sql_dump,
	MIN(log_activities.created_at) created_at,
	log_activities.updated_at
FROM `log_activities`
#WHERE user_id = 1
GROUP BY LEFT(created_at, 10), user_id
#ORDER BY LEFT(created_at, 10) ASC, user_id

# LOGOUT
UNION ALL
SELECT
	log_activities.user_id,
	log_activities.username,
	log_activities.user_fullname,
	log_activities.user_email,
	log_activities.user_group_id,
	log_activities.user_group_name,
	log_activities.user_group_info,
	'logout' route_path,
	'Logout' module_name,
	'logout' page_info,
	'https://mantra.smartfren.com/logout' urli,
	log_activities.method,
	log_activities.ip_address,
	log_activities.user_agent,
	log_activities.sql_dump,
	MAX(log_activities.created_at) created_at,
	log_activities.updated_at
FROM `log_activities`
#WHERE user_id = 1
GROUP BY LEFT(created_at, 10), user_id
ORDER BY LEFT(created_at, 10) ASC, user_id;
		";
		
		$sql = "
			SELECT
				LEFT(monthly_activity.daily_activity, 7) daily_activity,
				monthly_activity.group_id,
				monthly_activity.user_id,
				
				c.group_info role_group,
				c.group_alias role_location,
				
				b.username,
				b.fullname,
				b.email,
				
				monthly_activity.first_access,
				monthly_activity.last_access,
				monthly_activity.online_duration,
				monthly_activity.offline_duration,
				monthly_activity.login_counters
			FROM (
				SELECT
					daily_activity,
					group_id,
					user_id,
					MIN(first_access) first_access,
					MAX(last_access) last_access,
					CONCAT( 
						FLOOR(TIME_FORMAT(SEC_TO_TIME(SUM(online_duration)), '%H') / 24), ' Days ',
						FLOOR( MOD((SUM(online_duration)), 3600*24) / 3600), ' Hours ',
						FLOOR( MOD((SUM(online_duration)), 3600) / 60), ' Minutes ',
						MOD( (SUM(online_duration)), 60),' Seconds')
					online_duration,
					offline_duration,
					SUM(login_counters) login_counters
				FROM (
					# GET DAILY DATA ACTIVITY
					SELECT
						LEFT(a.created_at, 10) daily_activity,
						a.user_group_id group_id,
						a.user_id,
						MIN(a.created_at) first_access,
						MAX(a.created_at) last_access,
						TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)) AS online_duration,
						CASE
							WHEN LEFT(a.created_at, 10) >= LEFT(NOW(), 7)
								THEN 
									CONCAT (
										TIMESTAMPDIFF(DAY, MAX(a.created_at), NOW()), ' Days, ',
										FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), NOW()), 3600*24) / 3600), ' Hours, ',
										FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), NOW()), 3600) / 60), ' Minutes, ',
										FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), NOW()), 60)), ' Seconds'
									)
								ELSE
									CONCAT (
										TIMESTAMPDIFF(DAY, MAX(a.created_at), LAST_DAY(LEFT(a.created_at, 10)) ), ' Days, ',
										FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') ), 3600*24) / 3600), ' Hours, ',
										FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') ), 3600) / 60), ' Minutes, ',
										FLOOR(MOD (TIMESTAMPDIFF(SECOND, MAX(a.created_at), CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') ), 60)), ' Seconds'
									)
							END offline_duration,
							CONCAT(LAST_DAY(LEFT(a.created_at, 10)), ' 23:59:59') from_last_date,
						ROUND(COUNT(page_info)/2) login_counters
					FROM `log_activities` a
					WHERE a.page_info IN ('login_processor', 'logout') #AND user_id = 1
					GROUP BY LEFT(a.created_at, 10), a.user_id, a.user_group_id
					ORDER BY LEFT(a.created_at, 10) DESC, a.user_id, a.user_group_id
				) monthly
				GROUP BY LEFT(daily_activity, 7), user_id, group_id
				ORDER BY LEFT(daily_activity, 7) DESC, user_id, group_id
			) monthly_activity
			LEFT JOIN mantra_web.users b ON monthly_activity.user_id = b.id
			LEFT JOIN mantra_web.base_group c ON monthly_activity.group_id = c.id AND c.id >= 4
			GROUP BY LEFT(monthly_activity.daily_activity, 7), monthly_activity.user_id, monthly_activity.group_id
			ORDER BY LEFT(monthly_activity.daily_activity, 7) DESC, monthly_activity.user_id, monthly_activity.group_id
		";
		diy_temp_table($this->table, $sql);
	}
	
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
	
	public function montly_activityx() {
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
				#	FLOOR(MOD (TIMESTAMPDIFF(SECOND, MIN(a.created_at), MAX(a.created_at)), 60) / 3600 / 24), ' Days, ',
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