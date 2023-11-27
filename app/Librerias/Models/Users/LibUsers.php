<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 10/04/2018
 * Time: 12:04 PM
 */

namespace App\Librerias\Models\Users;

use App\Http\Requests\AdminRequest;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\LibCatalogsTable;
use App\Librerias\Catalog\Tables\GafaFit\CatalogUser;
use App\Models\Company\Company;
use App\Models\User\UserProfile;
use App\User;
use Illuminate\Http\Request;

class LibUsers
{
    /**
     * Regresa el perfil del usuario en una cierta compañía
     * Si no encuentra el perfil, pero sí el usuario, solamente
     * crea un perfil sin crear un usuario y si no encuentra
     * ninguno, crea un usuario y un perfil
     *
     * @param Request $request
     * @param string  $email
     * @param Company $company
     * @param string  $password
     *
     * @return UserProfile
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function createProfileByEmailAndCompany(Request $request, string $email, Company $company, string $password)
    {
        $company_id = $company->id;

        $profile = UserProfile::where([
            ['email', $email],
            ['companies_id', $company_id],
        ])->whereNull('deleted_at')
            ->first();

        if ($profile !== null) {
            return $profile;
        } else {
            $user = User::where('email', $email)->first();

            if ($user === null) {
                $request->merge(['password' => $password]);
                $request->merge(['name' => $email]);
                $request->merge(['status' => 'on']);
                $request->merge(['email' => $email]);
                $user = CatalogFacade::save($request, CatalogUser::class);

                $user->status = 'active';
                $user->save();
            }

            $user_profile = $user->profiles()->where('companies_id', $company_id)->whereNull('deleted_at')->first();
            if ($user_profile !== null) {
                return $user_profile;
            }

            $newProfile = new UserProfile();
            $newProfile->email = $email;
            $newProfile->users_id = $user->id;
//            $newProfile->status       = 'active';
            $newProfile->companies_id = $company_id;
            $newProfile->password = $password;

            if (
                $newProfile
                &&
                (
                    $request->has('first_name')
                    ||
                    $request->has('last_name')
                )
            ) {
                $newProfile->first_name = $request->get('first_name', null);
                $newProfile->last_name = $request->get('last_name', null);
                $newProfile->birth_date = $request->get('birth_date', null);
                $newProfile->gender = $request->get('gender', null);
            }

            //Need save to process specialTexts
            // But no additional verification is needed!
            $newProfile->verified = true;
            $newProfile->token = null;
            $newProfile->save();
            $newProfile->password = $password;
            $newProfile->save();

            //Save SpecialTexts
            LibCatalogsTable::processSaveInModel(
                $newProfile,
                LibCatalogsTable::Section_Register,
                $request->get('custom_fields', []),
                $request->file('custom_fields', []),
                $company_id
            );

            return $newProfile;
        }
    }

    /**
     * @param AdminRequest $request
     * @param Company      $company
     *
     * @return UserProfile
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function saveLocationUser(AdminRequest $request, Company $company)
    {
        if ($request->has('email') && $request->get('email')) {
            $user = self::createProfileByEmailAndCompany($request, $request->get('email'), $company, str_random(10));

            return $user;
        }
    }
}
