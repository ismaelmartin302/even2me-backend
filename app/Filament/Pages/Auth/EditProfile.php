<?php
 
namespace App\Filament\Pages\Auth;
 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
 
class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('username')
                    ->required()
                    ->alphaDash()
                    ->maxLength(255),
                $this->getNickNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
    protected function getNickNameFormComponent() {
        return TextInput::make('nickname')
        ->label('Nickname')
        ->ascii()
        ->maxLength(255)
        ->unique(ignoreRecord: true);
    }
}