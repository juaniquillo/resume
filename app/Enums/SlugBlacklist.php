<?php

namespace App\Enums;

enum SlugBlacklist: string
{
    case ADMIN = 'admin';
    case API = 'api';
    case DASHBOARD = 'dashboard';
    case LOGIN = 'login';
    case REGISTER = 'register';
    case LOGOUT = 'logout';
    case AUTH = 'auth';
    case SETTINGS = 'settings';
    case PROFILE = 'profile';
    case TELESCOPE = 'telescope';
    case HORIZON = 'horizon';
    case PULSE = 'pulse';
    case PHPINFO = 'phpinfo';
    case SERVER_STATUS = 'server-status';
    case WWW = 'www';
    case MAIL = 'mail';
    case ROOT = 'root';
    case TEST = 'test';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}



