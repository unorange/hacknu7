<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApiToken;

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use Illuminate\Support\Str;
use MoonShine\Handlers\ExportHandler;
use MoonShine\Handlers\ImportHandler;

/**
 * @extends ModelResource<ApiToken>
 */
class ApiTokenResource extends ModelResource
{
    protected string $model = ApiToken::class;

    protected string $title = 'API токены';

    public function getActiveActions(): array
    {
        return ['delete', 'create', 'update'];
    }

    public function search(): array
    {
        return [];
    }

    public function import(): ?ImportHandler
    {
        return null;
    }

    public function export(): ?ExportHandler
    {
        return null;
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make('uuid', 'uuid')->sortable(),
                Text::make('Токен', 'api_token')
                    ->default(Str::random(32))
                    ->readonly()
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
