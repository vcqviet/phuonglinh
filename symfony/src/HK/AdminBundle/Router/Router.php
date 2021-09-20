<?php
namespace HK\AdminBundle\Router;

class Router
{

    public static $_REAL_PATH_PREFIX = '/admin';

    public static $_PREFIX = 'hk_admin.';

    public static $LOGIN = 'hk_admin_login';

    public static $FORGOT_PASSWORD = 'hk_admin_forgot';

    public static $RESET_PASSWORD = 'hk_admin_reset';

    public static $LOGOUT = 'hk_admin_logout';

    public static $DASHBOARD = 'hk_admin_dashboard';
}