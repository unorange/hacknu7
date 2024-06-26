<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\ApiToken;
use App\Models\Scrape;
use App\MoonShine\Resources\ApiTokenResource;
use App\MoonShine\Resources\ScrapeResource;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function resources(): array
    {
        return [];
    }

    protected function pages(): array
    {
        return [];
    }

    protected function menu(): array
    {
        return [
            MenuGroup::make(static fn () => __('moonshine::ui.resource.system'), [
               MenuItem::make(
                   static fn () => __('moonshine::ui.resource.admins_title'),
                   new MoonShineUserResource()
               ),
               MenuItem::make(
                   static fn () => __('moonshine::ui.resource.role_title'),
                   new MoonShineUserRoleResource()
               ),
            ]),
            MenuItem::make('Акции', new ScrapeResource())
               ->badge(fn () => Scrape::query()->where('moderated', false)->count()),
            MenuItem::make('Api токены', new ApiTokenResource())
               ->badge(fn () => ApiToken::query()->count()),
        ];
    }

    /**
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
