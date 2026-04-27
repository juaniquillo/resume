<?php

namespace App\Enums;

enum Network: string
{
    case TWITTER = 'twitter';
    case GITHUB = 'github';
    case LINKEDIN = 'linkedin';
    case FACEBOOK = 'facebook';
    case INSTAGRAM = 'instagram';
    case YOUTUBE = 'youtube';
    case TIKTOK = 'tiktok';
    case STACK_OVERFLOW = 'stackoverflow';
    case REDDIT = 'reddit';
    case PERSONAL_WEBSITE = 'personal_website';
    case OTHER = 'other';
    case MASTODON = 'mastodon';
    case BLUESKY = 'bluesky';
    case DISCORD = 'discord';
    case WHATSAPP = 'whatsapp';
    case TELEGRAM = 'telegram';
    case SNAPCHAT = 'snapchat';
    case PINTEREST = 'pinterest';
    case TUMBLR = 'tumblr';
    case MEDIUM = 'medium';
    case GITLAB = 'gitlab';
    case BITBUCKET = 'bitbucket';
    case DRIBBBLE = 'dribbble';
    case BEHANCE = 'behance';
    case FLICKR = 'flickr';
    case VIMEO = 'vimeo';
    case QUORA = 'quora';
    case SLACK = 'slack';
    case CLUBHOUSE = 'clubhouse';
    case WHATSAPP_BUSINESS = 'whatsapp_business';
    case SIGNAL = 'signal';
    case WECHAT = 'wechat';
    case LINE = 'line';
    case VIBER = 'viber';
    case SKYPE = 'skype';
    case STARFLEET_DATABASE = 'Starfleet Database';

    public function label(): string
    {
        return match ($this) {
            self::TWITTER => 'Twitter',
            self::GITHUB => 'GitHub',
            self::LINKEDIN => 'LinkedIn',
            self::FACEBOOK => 'Facebook',
            self::INSTAGRAM => 'Instagram',
            self::YOUTUBE => 'YouTube',
            self::TIKTOK => 'TikTok',
            self::STACK_OVERFLOW => 'Stack Overflow',
            self::REDDIT => 'Reddit',
            self::PERSONAL_WEBSITE => 'Personal Website',
            self::OTHER => 'Other',
            self::MASTODON => 'Mastodon',
            self::BLUESKY => 'Bluesky',
            self::DISCORD => 'Discord',
            self::WHATSAPP => 'WhatsApp',
            self::TELEGRAM => 'Telegram',
            self::SNAPCHAT => 'Snapchat',
            self::PINTEREST => 'Pinterest',
            self::TUMBLR => 'Tumblr',
            self::MEDIUM => 'Medium',
            self::GITLAB => 'GitLab',
            self::BITBUCKET => 'Bitbucket',
            self::DRIBBBLE => 'Dribbble',
            self::BEHANCE => 'Behance',
            self::FLICKR => 'Flickr',
            self::VIMEO => 'Vimeo',
            self::QUORA => 'Quora',
            self::SLACK => 'Slack',
            self::CLUBHOUSE => 'Clubhouse',
            self::WHATSAPP_BUSINESS => 'WhatsApp Business',
            self::SIGNAL => 'Signal',
            self::WECHAT => 'WeChat',
            self::LINE => 'Line',
            self::VIBER => 'Viber',
            self::SKYPE => 'Skype',
            self::STARFLEET_DATABASE => 'Starfleet Database',
        };
    }

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
            default => 'zinc',
        };
    }
}
