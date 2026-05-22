<?php

namespace App\Enums;

enum Network: string
{
    case TWITTER = 'Twitter';
    case GITHUB = 'Github';
    case LINKEDIN = 'Linkedin';
    case FACEBOOK = 'Facebook';
    case INSTAGRAM = 'Instagram';
    case YOUTUBE = 'Youtube';
    case TIKTOK = 'Tiktok';
    case STACK_OVERFLOW = 'Stackoverflow';
    case REDDIT = 'Reddit';
    case PERSONAL_WEBSITE = 'Personal website';
    case MASTODON = 'Mastodon';
    case BLUESKY = 'Bluesky';
    case DISCORD = 'Discord';
    case WHATSAPP = 'Whatsapp';
    case TELEGRAM = 'Telegram';
    case SNAPCHAT = 'Snapchat';
    case PINTEREST = 'Pinterest';
    case TUMBLR = 'Tumblr';
    case MEDIUM = 'Medium';
    case GITLAB = 'Gitlab';
    case BITBUCKET = 'Bitbucket';
    case DRIBBBLE = 'Dribbble';
    case BEHANCE = 'Behance';
    case FLICKR = 'Flickr';
    case VIMEO = 'Vimeo';
    case QUORA = 'Quora';
    case SLACK = 'Slack';
    case CLUBHOUSE = 'Clubhouse';
    case WHATSAPP_BUSINESS = 'Whatsapp business';
    case SIGNAL = 'Signal';
    case WECHAT = 'Wechat';
    case LINE = 'Line';
    case VIBER = 'Viber';
    case SKYPE = 'Skype';
    case STARFLEET_DATABASE = 'Starfleet Database';

    public function colors(): string
    {
        return match ($this) {
            self::TWITTER => 'blue',
            self::GITHUB => 'zinc',
            self::LINKEDIN => 'blue',
            self::FACEBOOK => 'blue',
            self::INSTAGRAM => 'pink',
            self::YOUTUBE => 'red',
            self::TIKTOK => 'zinc',
            self::STACK_OVERFLOW => 'orange',
            self::REDDIT => 'orange',
            self::PERSONAL_WEBSITE => 'indigo',
            self::MASTODON => 'purple',
            self::BLUESKY => 'blue',
            self::DISCORD => 'indigo',
            self::WHATSAPP, self::WHATSAPP_BUSINESS => 'green',
            self::TELEGRAM => 'blue',
            self::SNAPCHAT => 'yellow',
            self::PINTEREST => 'red',
            self::TUMBLR => 'blue',
            self::MEDIUM => 'zinc',
            self::GITLAB => 'orange',
            self::BITBUCKET => 'blue',
            self::DRIBBBLE => 'pink',
            self::BEHANCE => 'blue',
            self::FLICKR => 'pink',
            self::VIMEO => 'blue',
            self::QUORA => 'red',
            self::SLACK => 'purple',
            self::CLUBHOUSE => 'orange',
            self::SIGNAL => 'blue',
            self::WECHAT => 'green',
            self::LINE => 'green',
            self::VIBER => 'purple',
            self::SKYPE => 'blue',
            self::STARFLEET_DATABASE => 'cyan',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::GITHUB, self::GITLAB, self::BITBUCKET => 'code-bracket',
            self::TWITTER, self::LINKEDIN, self::FACEBOOK, self::INSTAGRAM, self::TIKTOK, self::REDDIT, self::MASTODON, self::BLUESKY => 'users',
            self::YOUTUBE, self::VIMEO => 'play',
            self::STACK_OVERFLOW, self::QUORA => 'question-mark-circle',
            self::PERSONAL_WEBSITE => 'globe-alt',
            self::DISCORD, self::WHATSAPP, self::WHATSAPP_BUSINESS, self::TELEGRAM, self::SLACK, self::SIGNAL, self::WECHAT, self::LINE, self::VIBER, self::SKYPE => 'chat-bubble-left-right',
            self::SNAPCHAT => 'camera',
            self::PINTEREST => 'bookmark',
            self::TUMBLR, self::MEDIUM => 'document-text',
            self::DRIBBBLE, self::BEHANCE, self::FLICKR => 'photo',
            self::CLUBHOUSE => 'microphone',
            self::STARFLEET_DATABASE => 'command-line',
        };
    }
}
