<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 09/07/2018
 * Time: 05:10 PM
 */

namespace App\Librerias\Users;


use App\Events\UserProfile\ProfileUpdated;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\ApiRequest;
use App\Librerias\Models\Users\LibUsers;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Company\Company;
use App\Models\Log\ResendVerificationRequest;
use App\Models\User\UserProfile;
use App\Notifications\User\NotificationWelcomeResend;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LibUserProfiles
{
    /**
     * Crear un token encriptando el email y la fecha
     *
     * @param UserProfile $profile
     *
     * @return string
     */
    public static function createToken(UserProfile $profile)
    {
        $token = $profile->email . '_|_' . Carbon::now()->toDateTimeString() . '_|_' . $profile->companies_id;

        return encrypt($token);
    }

    /**
     * Desencripta el token y regresa un arreglo con el email y la fecha
     *
     * @param string $token
     *
     * @return array|null
     */
    public static function decryptToken(string $token)
    {
        try {
            $original = decrypt($token);

            $decrypted = explode('_|_', $original);
//            dd($decrypted,count($decrypted),!filter_var($decrypted[0],FILTER_VALIDATE_EMAIL),!Carbon::createFromFormat('Y-m-d H:i:s',$decrypted[1]),!$decrypted[2]);

            if (count($decrypted) !== 3) {
                return null;
            }
            if (!filter_var($decrypted[0], FILTER_VALIDATE_EMAIL)) {
                return null;
            }
            if (!Carbon::createFromFormat('Y-m-d H:i:s', $decrypted[1])) {
                return null;
            }
            if (!$decrypted[2]) {
                return null;
            }

            return $decrypted;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Obtiene el usuario que concuerda con el token, se desencripta
     * y se obtiene la fecha en que se emitió y el email. Luego se
     * comprueba si son datos correctos y que no se exceda de 24
     * horas desde la fecha de emisión. Si alguno de los datos es
     * inválido, se regresa un array con un mensaje de error y con
     * la url incorrecta guardada en mails_welcomes. Si no hay errores
     * se actualiza el perfil de usuario y se regresa un mensaje de
     * verificación correcta con la url correct_url de mails_welcomes
     *
     * @param string $token
     *
     * @param string $request_email
     *
     * @return EmailVerifiedResponse
     */
    public static function verifyToken(string $token, string $request_email = null): EmailVerifiedResponse
    {
        $response = new EmailVerifiedResponse();
        $data = self::decryptToken($token);
        $user = UserProfile::where('token', $token)->first();
        if (!$data && !$user) {
            abort(403);
        }

        $user_email = UserProfile::where([
            ['email', $data[0] ?? null],
            ['companies_id', $data[2] ?? null],
        ])->first();

        if (!$user && !$user_email) {
            abort(403, __('welcome.ErrorNo', ['error' => '001']) . __('welcome.MessageUserNotFound') . __('welcome.MessageContactAdmin'));
        }

        $company = $user ? $user->company : ($user_email->company ?? null);

        $info = $company->mailWelcomeInfo ?? null;

//        dd( $company->mailWelcomeInfo);

        if (!$info || !$info->incorrect_url || !$info->correct_url) {
            abort(403, __('welcome.ErrorNo', ['error' => '002']) . __('welcome.MessageNoRedirectInfo'));
        }

        $resend_url = route('api.resend', [
            'company' => $company->id,
            'email'   => $request_email ?? ($user->email ?? $user_email->email),
        ]);

        $resend_message = __('welcome.MessageResendEmail', [
            'url' => $resend_url,
        ]);

        if (($user_email && $user_email->isVerified())) {
            $user_email->verified = true;
            $user_email->token = null;
            $user_email->save();

            $response->setVerified(true);
            $response->setMessage(__('welcome.MessageUserAlreadyVerified'));
            $response->setUser($user_email);
            $response->setUrl($info->correct_url ?? '');

            return $response;
        } else if ($user && $user->isVerified()) {
            $user->verified = true;
            $user->token = null;
            $user->save();

            $response->setVerified(true);
            $response->setMessage(__('welcome.MessageUserAlreadyVerified'));
            $response->setUser($user);
            $response->setUrl($info->correct_url ?? '');
        } else {
            if (!$data) {
                $response->setVerified(false);
                $response->setMessage(__('welcome.ErrorNo', ['error' => '003']) . __('welcome.MessageInvalidFormat') . $resend_message);
                $response->setUrl($info->incorrect_url ?? '');

                return $response;
            }

            if (!$user && $user_email && !$user_email->isVerified()) {
                $response->setVerified(false);
                $response->setMessage(__('welcome.ErrorNo', ['error' => '006']) . __('welcome.MessageRenewedToken') . $resend_message);
                $response->setUrl($info->incorrect_url ?? '');

                return $response;
            }

            $email = $data[0];
            $date_string = $data[1];

            if (!isset($user->email) || $user->email !== $email) {
                $response->setVerified(false);
                $response->setMessage(__('welcome.ErrorNo', ['error' => '004']) . __('welcome.MessageEmailNotMatch') . $resend_message);
                $response->setUrl($info->incorrect_url ?? '');

                return $response;
            }

            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date_string);
            $now = Carbon::now();

            if ($now->gt($date->addHours(24))) {
                $response->setVerified(false);
                $response->setMessage(__('welcome.ErrorNo', ['error' => '005']) . __('welcome.MessageExpiredToken') . $resend_message);
                $response->setUrl($info->incorrect_url ?? '');

                return $response;
            }

            $prev_user = clone $user;
            $user->verified = true;
            $user->token = null;
            $user->save();
            event(new ProfileUpdated($prev_user, $user->profileCatalog));

            $response->setVerified(true);
            $response->setUser($user);
            $response->setUrl($info->correct_url ?? '');

            return $response;
        }
    }

    /**
     * Verifica que la petición para crear un nuevo perfil de usuario contenga el email,
     * la contraseña y la clave secreta del cliente. Además comprueba que la clave esté
     * dada de alta en la tabla oauth_clients y que la compañía asociada sea la misma
     * que se pasó en la cabecera. Después manda a llamar a la función
     * createProfileByEmailAndCompany, la cual crea el nuevo registro y finalmente regresa
     * un json con el nuevo perfil
     *
     * @param ApiRequest   $request
     * @param Company|null $company
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function verifyCreateRequest(ApiRequest $request, Company $company = null)
    {

        if (!$company) {
            abort(403);
        } else if (!$request->has('username')) {
            abort(403, __('api-user.MessageMissingUsername'));
        } else if (!$request->has('password') || !$request->has('password_confirmation')) {
            abort(403, __('api-user.MessageMissingPassword'));
        } else if (!$client_id = $request->get('client_id')) {
            abort(403, __('api-user.MessageMissingClientID'));
        } else if (!$secret = $request->get('client_secret')) {
            abort(403, __('api-user.MessageMissingSecret'));
        } else if (!$client = DB::table('oauth_clients')->where('secret', $secret)->first()) {
            abort(403, __('api-user.MessageIncorrectClientInfo'));
        } else if ($client->companies_id !== $company->id) {
            abort(403, __('api-user.MessageIncorrectClientInfo'));
        }

        $email = $request->get('username');
        $password = $request->get('password');
        //Compatibilidad a crecion de perfil
        $request->request->add([
            'email' => $email,
        ]);

        $profile = LibUsers::createProfileByEmailAndCompany($request, $email, $company, $password);
        // Fire off the internal request.
        $profile->token = null;//Hide token
//        $tokenRequest = Request::create(
//            '/oauth/token',
//            'post'
//        );
//
//        return Route::dispatch($tokenRequest);
        return response()->json($profile);
    }

    public static function resendVerificationEmail(string $email, Company $company)
    {
        $response = new EmailVerifiedResponse();
        $info = $company->mailWelcomeInfo;

        if (!$info || !$info->incorrect_url || !$info->correct_url) {
            abort(403, __('welcome.ErrorNo', ['error' => '007']) . __('welcome.MessageNoRedirectInfo'));
        }

        if (!$email || $email === '') {
            $response->setVerified(false);
            $response->setUrl($info->incorrect_url);
            $response->setMessage(__('welcome.MessageNoEmail'));

            return $response;
        }

        $user = UserProfile::where([
            ['email', $email],
            ['companies_id', $company->id],
        ])->first();

        if (!$user) {
            $response->setVerified(false);
            $response->setUrl($info->incorrect_url);
            $response->setMessage(__('welcome.ErrorNo', ['error' => '008']) . __('welcome.MessageUserNotFound'));

            return $response;
        }

        if ($user->isVerified()) {
            $response->setVerified(true);
            $response->setUrl($info->correct_url);
            $response->setMessage(__('welcome.MessageUserAlreadyVerified'));

            return $response;
        }

        $resend_request = ResendVerificationRequest::where([
            ['companies_id', $company->id],
            ['email', $email],
        ])->whereDate('date', Carbon::now()->toDateString())->first();

        if (!$resend_request) {
            $user->unVerify();
            $user->save();
            $notification = new NotificationWelcomeResend($user, $user->token);
            $user->notify($notification);

            $response->setMessage(__('welcome.MessageResentCorrectly'));
            $response->setVerified(true);
            $response->setUrl($info->correct_url);
        } else {
            $response->setMessage(__('welcome.MessageAlreadySent'));
            $response->setVerified(false);
            $response->setUrl($info->incorrect_url);
        }

        return $response;
    }
}
