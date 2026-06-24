<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CachedIssueResource\Pages;
use App\Filament\Resources\CachedIssueResource\RelationManagers;
use App\Models\CachedIssue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CachedIssueResource extends Resource
{
    protected static ?string $model = CachedIssue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('extension_id')
                    ->relationship('extension', 'extension_name', fn (Builder $query) => $query->where('user_id', \Illuminate\Support\Facades\Auth::id()))
                    ->required(),
                Forms\Components\TextInput::make('platform')
                    ->required(),
                Forms\Components\TextInput::make('issue_iid')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('state')
                    ->required(),
                Forms\Components\TextInput::make('author')
                    ->required(),
                Forms\Components\Textarea::make('labels')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('opened_at')
                    ->required(),
                Forms\Components\DateTimePicker::make('last_updated_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('extension.extension_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('platform')
                    ->searchable(),
                Tables\Columns\TextColumn::make('issue_iid')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->searchable(),
                Tables\Columns\TextColumn::make('opened_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_updated_at')
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
            'index' => Pages\ListCachedIssues::route('/'),
            'create' => Pages\CreateCachedIssue::route('/create'),
            'edit' => Pages\EditCachedIssue::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('extension', function (Builder $query) {
            $query->where('user_id', Auth::id());
        });
    }
}
