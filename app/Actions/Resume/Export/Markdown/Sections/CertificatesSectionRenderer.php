<?php

namespace App\Actions\Resume\Export\Markdown\Sections;

use App\Actions\Resume\Export\Markdown\Concerns\FormatsMarkdown;
use App\Actions\Resume\Export\Markdown\Contracts\MarkdownSectionRenderer;

final class CertificatesSectionRenderer implements MarkdownSectionRenderer
{
    use FormatsMarkdown;

    public function section(): string
    {
        return 'certificates';
    }

    public function render(array $resume): array
    {
        $certificates = $resume['certificates'] ?? null;

        if (! is_array($certificates) || $certificates === []) {
            return [];
        }

        $lines = ['', '## Certificates', ''];

        foreach ($certificates as $cert) {
            $item = '**'.$this->text($cert['name'] ?? 'Certificate').'**';

            if (! empty($cert['date'])) {
                $item .= ' ('.$this->formatDate($cert['date']).')';
            }

            if (! empty($cert['website'])) {
                $item .= " [Verify]({$cert['website']})";
            }

            $lines[] = '- '.$item;
        }

        return $lines;
    }
}
