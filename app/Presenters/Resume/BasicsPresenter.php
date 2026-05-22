<?php

namespace App\Presenters\Resume;

use App\Components\Builders\FluxComponentBuilder;
use App\Enums\Network;
use App\Models\Basic;
use App\Models\Profile;
use App\Presenters\Contracts\PresenterTheme;
use App\Presenters\Resume\Concerns\CanComposeResumeComponents;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\CompoundComponent;
use Juaniquillo\BackendComponents\Enums\ComponentEnum;
use Juaniquillo\BackendComponents\Themes\LocalThemeManager;

final class BasicsPresenter
{
    use CanComposeResumeComponents;

    public function __construct(
        private ?Basic $basics,
        private PresenterTheme $theme,
    ) {}

    public function present(): BackendComponent|CompoundComponent|null
    {
        if (! $this->basics) {
            return null;
        }

        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->basicsContainerThemes())
            ->setContents([
                'image' => $this->compose(ComponentEnum::SPAN)
                    ->setContent(
                        $this->basics->image
                        ? $this->compose(ComponentEnum::IMG)
                            ->setThemes($this->theme->imageThemes())
                            ->setAttributes([
                                'src' => route('image.serve', $this->basics->uuid),
                                'alt' => $this->basics->name,
                            ])
                        : null,
                    ),
                'name' => $this->compose(ComponentEnum::H1)
                    ->setThemes($this->theme->nameThemes())
                    ->setContent($this->basics->name),
                'label' => $this->compose(ComponentEnum::H2)
                    ->setThemes($this->theme->labelThemes())
                    ->setContent($this->basics->label),
                'contact' => $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->contactContainerThemes())
                    ->setContents($this->basicsContactItems($this->basics)),
            ]);
    }

    /**
     * @return array<string, BackendComponent|CompoundComponent>
     */
    private function basicsContactItems(Basic $basics): array
    {
        $info = [];

        if ($basics->email) {
            $info['email'] = $this->compose(ComponentEnum::SPAN)
                ->setThemes($this->theme->emailThemes())
                ->setContents([
                    'icon' => FluxComponentBuilder::make('icon.envelope')
                        ->setThemeManager((new LocalThemeManager))
                        ->setThemes($this->theme->iconThemes())
                        ->setAttribute('variant', 'outline'),
                    'link' => $this->compose(ComponentEnum::LINK)
                        ->setThemes($this->theme->linkThemes())
                        ->setAttribute('href', "mailto:{$basics->email}")
                        ->setContent($basics->email),
                ]);
        }

        if ($basics->phone) {
            $info['phone'] = $this->compose(ComponentEnum::SPAN)
                ->setThemes($this->theme->phoneThemes())
                ->setContents([
                    'icon' => FluxComponentBuilder::make('icon.phone')
                        ->setThemeManager((new LocalThemeManager))
                        ->setThemes($this->theme->iconThemes())
                        ->setAttribute('variant', 'outline'),
                    'text' => $this->compose(ComponentEnum::SPAN)
                        ->setContent($basics->phone),
                ]);
        }

        if ($basics->url) {
            $info['url'] = $this->compose(ComponentEnum::SPAN)
                ->setThemes($this->theme->urlThemes())
                ->setContents([
                    'icon' => FluxComponentBuilder::make('icon.link')
                        ->setThemeManager((new LocalThemeManager))
                        ->setThemes($this->theme->iconThemes())
                        ->setAttribute('variant', 'outline'),
                    'link' => $this->compose(ComponentEnum::LINK)
                        ->setThemes($this->theme->linkThemes())
                        ->setAttribute('href', $basics->url)
                        ->setAttribute('target', '_blank')
                        ->setContent($basics->url),
                ]);
        }

        if ($basics->location) {
            $location = "{$basics->location->city}, {$basics->location->country_code}";
            $info['location'] = $this->compose(ComponentEnum::SPAN)
                ->setThemes($this->theme->locationThemes())
                ->setContents([
                    'icon' => FluxComponentBuilder::make('icon.map-pin')
                        ->setThemeManager((new LocalThemeManager))
                        ->setThemes($this->theme->iconThemes())
                        ->setAttribute('variant', 'outline'),
                    'text' => $this->compose(ComponentEnum::SPAN)
                        ->setContent($location),
                ]);
        }

        $profiles = [];
        /** @var Profile $profile */
        foreach ($basics->profiles as $profile) {
            $network = $profile->network;
            $enum = Network::tryFrom($network);
            $icon = $enum ? $enum->icon() : 'globe-alt';

            $profiles["profile_{$profile->id}"] = $this->compose(ComponentEnum::SPAN)
                ->setThemes($this->theme->profileThemes())
                ->setContent(
                    $this->compose(ComponentEnum::LINK)
                        ->setThemes(array_merge($this->theme->linkThemes(), $this->theme->badgeThemes()))
                        ->setAttribute('href', $profile->url)
                        ->setAttribute('target', '_blank')
                        ->setContents([
                            'icon' => FluxComponentBuilder::make("icon.{$icon}")
                                ->setThemeManager((new LocalThemeManager))
                                ->setThemes($this->theme->iconThemes())
                                ->setAttribute('variant', 'outline'),
                            'username' => $this->compose(ComponentEnum::SPAN)
                                ->setContent($network),
                        ])
                );
        }

        return [
            'info' => $this->compose(ComponentEnum::SPAN)
                ->setThemes($this->theme->contactContainerThemes())
                ->setContents($info),
            'profiles' => $this->compose(ComponentEnum::SPAN)
                ->setThemes($this->theme->contactContainerThemes())
                ->setContents($profiles),
        ];
    }
}
