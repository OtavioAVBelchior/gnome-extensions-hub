<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtensionResource\Pages;
use App\Filament\Resources\ExtensionResource\RelationManagers;
use App\Models\Extension;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ExtensionResource extends Resource
{
    protected static ?string $model = Extension::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Project';
    protected static ?string $pluralModelLabel = 'Projects';
    protected static ?string $navigationLabel = 'Projects';
    protected static ?string $slug = 'projects';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => \Illuminate\Support\Facades\Auth::id())
                    ->required(),
                Forms\Components\Select::make('platform')
                    ->options([
                        'github' => 'GitHub',
                        'gitlab' => 'GitLab',
                    ])
                    ->required()
                    ->live(),
                Forms\Components\Select::make('repo_full_name')
                    ->label('Repository')
                    ->options(function (Forms\Get $get) {
                        $user = \Illuminate\Support\Facades\Auth::user();
                        $platform = $get('platform');
                        if (!$user || !$platform) return [];

                        if ($platform === 'github' && $user->github_token) {
                            $response = \Illuminate\Support\Facades\Http::withToken($user->github_token)
                                ->get('https://api.github.com/user/repos', [
                                    'per_page' => 100,
                                    'sort' => 'updated'
                                ]);
                            if ($response->successful()) {
                                return collect($response->json())->pluck('full_name', 'full_name');
                            }
                        }

                        if ($platform === 'gitlab' && $user->gitlab_token) {
                            $response = \Illuminate\Support\Facades\Http::withToken($user->gitlab_token)
                                ->get('https://gitlab.com/api/v4/projects', [
                                    'membership' => true,
                                    'per_page' => 100,
                                    'order_by' => 'updated_at'
                                ]);
                            if ($response->successful()) {
                                return collect($response->json())->pluck('path_with_namespace', 'path_with_namespace');
                            }
                        }

                        return [];
                    })
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('extension_name')
                    ->label('Project Name')
                    ->required(),
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                Forms\Components\Textarea::make('metadata')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('supported_versions')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('current_version'),
                Forms\Components\DateTimePicker::make('last_synced_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('platform')
                    ->searchable(),
                Tables\Columns\TextColumn::make('repo_full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('extension_name')
                    ->label('Project Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('current_version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_synced_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExtensions::route('/'),
            'create' => Pages\CreateExtension::route('/create'),
            'edit' => Pages\EditExtension::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }
}
