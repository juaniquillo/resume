<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class BasicsSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'basics';
    }

    public function render(array $resume): array
    {
        $basics = $resume['basics'] ?? [];

        if (! is_array($basics)) {
            return [];
        }

        $lines = ['# '.$this->text($basics['name'] ?? 'Resume')];

        if (! empty($basics['label'])) {
            $lines[] = '';
            $lines[] = '*'.$this->text($basics['label']).'*';
        }

        $contact = [];

        if (! empty($basics['email'])) {
            $email = $basics['email'];
            $contact[] = "[{$email}](mailto:{$email})";
        }

        if (! empty($basics['phone'])) {
            $contact[] = $this->text($basics['phone']);
        }

        if (! empty($basics['website'])) {
            $contact[] = "[{$basics['website']}]({$basics['website']})";
        }

        if (! empty($basics['location']) && is_array($basics['location'])) {
            $location = implode(', ', array_filter([
                $basics['location']['address'] ?? null,
                $basics['location']['city'] ?? null,
                $basics['location']['region'] ?? null,
                $basics['location']['postalCode'] ?? null,
                $basics['location']['countryCode'] ?? null,
            ]));

            if ($location !== '') {
                $contact[] = $this->text($location);
            }
        }

        if ($contact !== []) {
            $lines[] = '';
            $lines[] = implode(' | ', $contact);
        }

        $profiles = $basics['profiles'] ?? [];

        if (is_array($profiles) && $profiles !== []) {
            $lines[] = '';
            foreach ($profiles as $profile) {
                $network = $this->text($profile['network'] ?? 'Profile');
                $username = ! empty($profile['username']) ? ' (@'.$this->text($profile['username']).')' : '';
                $url = $profile['url'] ?? null;

                $lines[] = $url ? "- [{$network}{$username}]({$url})" : "- {$network}{$username}";
            }
        }

        return $lines;
    }
}
