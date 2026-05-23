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

        $image = $this->basics->image ?? null;
        $imageUrl = $image ? route('image.serve', $this->basics->uuid).'?v='.($this->basics->updated_at->timestamp ?? now()->timestamp) : null;

        return $this->compose(ComponentEnum::DIV)
            ->setThemes($this->theme->basicsContainerThemes())
            ->setContents(array_filter([
                'image' => $image ? $this->compose(ComponentEnum::SPAN)
                    ->setContent(
                        $this->compose(ComponentEnum::IMG)
                            ->setThemes($this->theme->imageThemes())
                            ->setAttributes([
                                'src' => $imageUrl,
                                'alt' => $this->basics->name,
                            ])
                    ) : null,
                'name' => $this->compose(ComponentEnum::H1)
                    ->setThemes($this->theme->nameThemes())
                    ->setContent($this->basics->name),
                'label' => $this->compose(ComponentEnum::H2)
                    ->setThemes($this->theme->labelThemes())
                    ->setContent($this->basics->label),
                'contact' => $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->contactContainerThemes())
                    ->setContents($this->basicsContactItems($this->basics)),
            ]));
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
            $locationParts = array_filter([
                $basics->location->address,
                $basics->location->city,
                $basics->location->region,
                $basics->location->postal_code,
                $basics->location->country_code,
            ]);
            $location = implode(', ', $locationParts);

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
            $enum = Network::fromString($network);

            $icon = $enum
                ? $this->compose(ComponentEnum::DIV)
                    ->setThemes($this->theme->iconThemes())
                    ->setAttributes([
                        'style' => sprintf(
                            'background-color: #%s; mask-image: url(%s); -webkit-mask-image: url(%s); mask-size: contain; mask-repeat: no-repeat; mask-position: center;',
                            $enum->hex(),
                            asset("images/networks/{$enum->slug()}.svg"),
                            asset("images/networks/{$enum->slug()}.svg")
                        ),
                        'title' => $network,
                    ])
                : FluxComponentBuilder::make('icon.globe-alt')
                    ->setThemeManager((new LocalThemeManager))
                    ->setThemes($this->theme->iconThemes())
                    ->setAttribute('variant', 'outline');

            $profiles["profile_{$profile->id}"] = $this->compose(ComponentEnum::LINK)
                ->setThemes($this->theme->socialBadgeThemes())
                ->setAttribute('href', $profile->url)
                ->setAttribute('target', '_blank')
                ->setContents([
                    'icon' => $this->compose(ComponentEnum::DIV)
                        ->setThemes($this->theme->iconThemes())
                        ->setAttributes([
                            'style' => sprintf(
                                '--brand-color: #%s; background-color: var(--brand-color); mask-image: url(%s); -webkit-mask-image: url(%s); mask-size: contain; mask-repeat: no-repeat; mask-position: center;',
                                $enum ? $enum->hex() : '000000',
                                asset('images/networks/'.($enum ? $enum->slug() : 'globe-alt').'.svg'),
                                asset('images/networks/'.($enum ? $enum->slug() : 'globe-alt').'.svg')
                            ),
                            'title' => $network,
                        ]),
                    'name' => $this->compose(ComponentEnum::SPAN)
                        ->setContent($network),
                ]);
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
