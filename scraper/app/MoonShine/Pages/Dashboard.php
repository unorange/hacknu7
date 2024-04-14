<?php

declare(strict_types=1);

namespace App\MoonShine\Pages;

use MoonShine\Decorations\Block;
use MoonShine\Pages\Page;
use MoonShine\Fields\Text;

class Dashboard extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Dashboard';
    }

    public function components(): array
    {
        return [
            Block::make('', [
                Text::make()->default('ТУТ НИЧЕГО НЕТ')
            ])
        ];
    }
}
