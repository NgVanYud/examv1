<?php
/**
 * Created by PhpStorm.
 * User: ngduy
 * Date: 2/14/19
 * Time: 9:15 PM
 */

return [

    /*
     * Application captcha specific settings
     */
    'captcha' => [
        /*
         * Whether the registration captcha is on or off
         */
        'registration' => env('REGISTRATION_CAPTCHA_STATUS', false),
    ],

    /*
     * Whether or not registration is enabled
     */
    'registration' => env('ENABLE_REGISTRATION', true),

    /*
     * Table names for access tables
     */
    'table_names' => [
        'password_histories' => 'password_histories',
        'users' => 'users',
    ],

    /*
     * Configurations for the user
     */
    'users' => [
        /*
         * Whether or not the user has to confirm their email when signing up
         */
//        'confirm_email' => env('CONFIRM_EMAIL', false),

        /*
         * Whether or not the users email can be changed on the edit profile screen
         */
//        'change_email' => env('CHANGE_EMAIL', false),

        /*
         * The name of the super administrator role
         */
//        'admin_role' => 'administrator',

        /*
         * The default role all new registered users get added to
         */
        'default_role' => 'student',

        /*
         * Whether or not new users need to be approved by an administrator before logging in
         * If this is set to true, then confirm_email is not in effect
         */
//        'requires_approval' => env('REQUIRES_APPROVAL', false),

        /*
         * Login username to be used by the controller.
         */
        'username' => 'username',

        /*
         * Session Database Driver Only
         * When active, a user can only have one session active at a time
         * That is all other sessions for that user will be deleted when they log in
         * (They can only be logged into one place at a time, all others will be logged out)
         */
        'single_login' => true,

        /*
         * How many days before users have to change their passwords
         * false is off
         */
//        'password_expires_days' => env('PASSWORD_EXPIRES_DAYS', 30),

        /*
         * The number of most recent previous passwords to check against when changing/resetting a password
         * false is off which doesn't log password changes or check against them
         */
//        'password_history' => env('PASSWORD_HISTORY', 3),
    ],

    /*
    * Configuration for roles
    */
    'roles' => [
        /*
         * Whether a role must contain a permission or can be used standalone as a label
         */
        'role_must_contain_permission' => true,
    ],

    /*
     * Socialite session variable name
     * Contains the name of the currently logged in provider in the users session
     * Makes it so social logins can not change passwords, etc.
     */
    'socialite_session_name' => 'socialite_provider',

    /*
     * List roles in app
     */
    'roles_list' => [
        'exams_maker'   => 'Giáo Viên Ra Đề',
        'curator'       => 'Cán Bộ Khảo Thí', //Khảo thí
        'protor'        => 'Giám Thị', //Coi thi
        'admin'         => 'Quản Trị Hệ Thống',
        'student'	    => 'Sinh Viên',
    ],

    /*
     * List permissions with corresponding roles
     *
     * Format: permission_name => role_name
     */

    'permissions_list' => [
        'định_dạng_đề_thi'                  => 'exams_maker',
        'thiết_lập_thông_tin_đợt_thi'       => 'curator',
        'xuất_danh_sách_thí_sinh'           => 'curator',
        'kích_hoạt_bài_thi'                 => 'protor',
        'xuất_kết_quả_bài_thi'              => 'protor',
        'làm_bài_thi'                       => 'student',
        'quản_lý_danh_sách_môn_thi'         => 'admin',
        'quản_lý_tài_khoản'                 => 'admin',
    ],
];
