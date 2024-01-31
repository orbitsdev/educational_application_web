<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Course;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\CourseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use App\Filament\Resources\CourseResource\RelationManagers;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('')
                    ->columns([
                        'sm' => 3,
                        'xl' => 6,
                        '2xl' => 9,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->maxLength(191)
                            ->columnSpanFull(),

                        MarkdownEditor::make('description')->columnSpanFull()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('attachments'),
                    ]),



                Group::make()
                    ->relationship('image')
                    ->schema([
                        FileUpload::make('path')
                            ->required()
                            ->columnSpanFull()
                             ->image()
                            // ->preserveFilenames()
                            ->maxSize(200000)
                            ->label('File')
                            ->disk('public')
                            ->directory('course-preview-images')
                    ])
                    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
                        // $data['user_id'] = auth()->id();

                        return $data;
                    })
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                        // $filePath = storage_path('app/public/' . $data['file ']);

                        $filePath = storage_path('app/public/' . $data['path']);

                        dd($data['path']);

                        $originalName = basename($filePath);
                        $fileInfo = [
                            'path' => $data['path'],
                            'file_name' =>$originalName,
                            'file_type' => mime_content_type($filePath),
                            'file_size' => call_user_func(function ($bytes) {
                                $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                                $i = 0;

                                while ($bytes >= 1024 && $i < count($units) - 1) {
                                    $bytes /= 1024;
                                    $i++;
                                }

                                return round($bytes, 2) . ' ' . $units[$i];
                            }, filesize($filePath)),
                        ];

                        return $fileInfo;
                        // $data['user_id'] = auth()->id();

                        // return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {


                        $filePath = storage_path('app/public/' . $data['path']);
                        $originalName = basename($filePath);
                        $fileInfo = [
                            'path' => $data['path'],
                            'file_name' => $originalName,
                            'file_type' => mime_content_type($filePath),
                            'file_size' => call_user_func(function ($bytes) {
                                $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                                $i = 0;

                                while ($bytes >= 1024 && $i < count($units) - 1) {
                                    $bytes /= 1024;
                                    $i++;
                                }

                                return round($bytes, 2) . ' ' . $units[$i];
                            }, filesize($filePath)),
                        ];

                        // dd($fileInfo);
                        // dd($data);

                        return $fileInfo;
                    })
                    ->columnSpanFull()




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ->description(fn (Course $record): string => $record->description,)
                // ->wrap()
                // ->weight(FontWeight::Bold)
                //     ->size(TextColumnSize::Large)
                //     ->markdown()
               ImageColumn::make('image.path')
               ->size(200)
               ->url(''),




                Tables\Columns\TextColumn::make('title')
                    // ->weight(FontWeight::Bold)
                    // ->size(TextColumnSize::Large)
                    ->searchable(),

                Panel::make([
                    Stack::make([
                        Tables\Columns\TextColumn::make('description')
                            // ->dateTime()
                            // ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true)

                            ->markdown(),
                    ]),
                ])->collapsible(),
                // Stack::make([

                //     ,
                // Tables\Columns\TextColumn::make('description')
                //     // ->dateTime()
                //     // ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true)

                //     ->markdown()
                //     ,
                // ]),

                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(null)
            // ->contentGrid([
            //     'md' => 2,
            //     'xl' => 3,
            // ])
        ;
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
