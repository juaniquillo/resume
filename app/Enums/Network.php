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

    public static function fromString(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if (strtolower($case->value) === strtolower($name)) {
                return $case;
            }
        }

        return null;
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
        };
    }

    public function hex(): string
    {
        return match ($this) {
            self::TWITTER => '1DA1F2',
            self::GITHUB => '181717',
            self::LINKEDIN => '0A66C2',
            self::FACEBOOK => '1877F2',
            self::INSTAGRAM => 'E4405F',
            self::YOUTUBE => 'FF0000',
            self::TIKTOK => '000000',
            self::STACK_OVERFLOW => 'F48024',
            self::REDDIT => 'FF4500',
            self::PERSONAL_WEBSITE => '4285F4',
            self::MASTODON => '6364FF',
            self::BLUESKY => '0085FF',
            self::DISCORD => '5865F2',
            self::WHATSAPP, self::WHATSAPP_BUSINESS => '25D366',
            self::TELEGRAM => '26A5E4',
            self::SNAPCHAT => 'FFFC00',
            self::PINTEREST => 'E60023',
            self::TUMBLR => '35465C',
            self::MEDIUM => '000000',
            self::GITLAB => 'FC6D26',
            self::BITBUCKET => '0052CC',
            self::DRIBBBLE => 'EA4C89',
            self::BEHANCE => '1769FF',
            self::FLICKR => '0063DC',
            self::VIMEO => '1AB7EA',
            self::QUORA => 'B92B27',
            self::SLACK => '4A154B',
            self::CLUBHOUSE => '241F20',
            self::SIGNAL => '3A76F0',
            self::WECHAT => '09B83E',
            self::LINE => '00C300',
            self::VIBER => '7360F2',
            self::SKYPE => '00AFF0',
            self::STARFLEET_DATABASE => '000000',
        };
    }

    public function slug(): string
    {
        return match ($this) {
            self::TWITTER => 'x',
            self::GITHUB => 'github',
            self::LINKEDIN => 'linkedin',
            self::FACEBOOK => 'facebook',
            self::INSTAGRAM => 'instagram',
            self::YOUTUBE => 'youtube',
            self::TIKTOK => 'tiktok',
            self::STACK_OVERFLOW => 'stackoverflow',
            self::REDDIT => 'reddit',
            self::PERSONAL_WEBSITE => 'googlechrome',
            self::MASTODON => 'mastodon',
            self::BLUESKY => 'bluesky',
            self::DISCORD => 'discord',
            self::WHATSAPP, self::WHATSAPP_BUSINESS => 'whatsapp',
            self::TELEGRAM => 'telegram',
            self::SNAPCHAT => 'snapchat',
            self::PINTEREST => 'pinterest',
            self::TUMBLR => 'tumblr',
            self::MEDIUM => 'medium',
            self::GITLAB => 'gitlab',
            self::BITBUCKET => 'bitbucket',
            self::DRIBBBLE => 'dribbble',
            self::BEHANCE => 'behance',
            self::FLICKR => 'flickr',
            self::VIMEO => 'vimeo',
            self::QUORA => 'quora',
            self::SLACK => 'slack',
            self::CLUBHOUSE => 'clubhouse',
            self::SIGNAL => 'signal',
            self::WECHAT => 'wechat',
            self::LINE => 'line',
            self::VIBER => 'viber',
            self::SKYPE => 'skype',
            self::STARFLEET_DATABASE => 'nasa',
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
