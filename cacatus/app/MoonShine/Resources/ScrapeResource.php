<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scrape;
use App\Scraper\Banks;
use App\Scraper\Category;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Text;
use MoonShine\Fields\Number;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Textarea;
use MoonShine\Fields\Enum;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Handlers\ImportHandler;

/**
 * @extends ModelResource<Scrape>
 */
class ScrapeResource extends ModelResource
{
    protected string $model = Scrape::class;

    protected string $title = 'Акции';

    public function search(): array
    {
        return ['title', 'bank', 'franchise', 'category'];
    }

    public function import(): ?ImportHandler
    {
        return null;
    }

    public function export(): ?ExportHandler
    {
        return null;
    }

    public function getActiveActions(): array
    {
        return ['update', 'delete'];
    }

    public function filters(): array
    {
        return [
            Checkbox::make('Модерирован', 'moderated'),
            Enum::make('Банк', 'bank')->attach(Banks::class)->nullable(),
            Enum::make('Категория', 'category')->attach(Category::class)->nullable(),
        ];
    }

    public function formFields(): array
    {
        return [
            Block::make([
                Tabs::make([
                    Tab::make('Атрибуты', [
                        Grid::make(fields: [
                            Column::make('Характеристики', [
                                Text::make('Франшиза', 'franchise')->nullable(),
                                Text::make('Город', 'city')->nullable(),
                                Enum::make('Категория', 'category')->nullable()->attach(Category::class),
                            ])->columnSpan(6),
                            Column::make('', [
                                Number::make('кэшбэк', 'cashback'),
                                Text::make('Тайтл', 'title'),
                                Checkbox::make('Модерирован', 'moderated')
                            ])->columnSpan(6),
                        ])
                    ]),
                    Tab::make('Текстовая информация', [
                        Textarea::make('Raw', 'raw')->setAttribute('rows', '12'),
                        Textarea::make('Условие', 'condition')->setAttribute('rows', '8'),
                        Textarea::make('Ограничение', 'limitation')->setAttribute('rows', '8'),
                    ])
                ])
            ])
        ];
    }

    public function indexFields(): array
    {
        return [
            Block::make([
                Text::make('Банк', 'bank'),
                Number::make('Кэшбэк', 'cashback', function ($model) {
                    return $model->cashback . "%";
                }),
                Text::make('Франшиза', 'franchise'),
                Text::make('Title', 'title'),
                Text::make('Категория', 'category'),
                Text::make('URL', 'url', function ($model) {
                    return 'click';
                })->link(function ($item) {
                    return $item;
                }),
                Checkbox::make('Модерирован', 'moderated')
                    ->offValue('Нет')
                    ->onValue('Да')
            ]),
        ];
    }

    protected $fillable = [
        'hash',
        'bank',
        'cashback',
        'raw',
        'title',
        'url',
        'image_url',
        'limitation',
        'condition',
        'category',
        'franchise',
        'city',
        'created_at',
        'time_end',
        'time_start',
    ];

    public function rules(Model $item): array
    {
        return [];
    }
}
