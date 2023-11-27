<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 05/03/18
 * Time: 11:58
 */

namespace App\Librerias\Permissions;
use Silber\Bouncer\Contracts\Scope;


class Bouncer extends \Silber\Bouncer\Bouncer
{
    /**
     * Get an instance of the role model.
     *
     * @param  array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function role(array $attributes = [])
    {
        return Models::role($attributes);
    }

    /**
     * Get an instance of the ability model.
     *
     * @param  array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function ability(array $attributes = [])
    {
        return Models::ability($attributes);
    }

    /**
     * Register an attribute/callback to determine if a model is owned by a given authority.
     *
     * @param  string|\Closure      $model
     * @param  string|\Closure|null $attribute
     *
     * @return $this
     */
    public function ownedVia($model, $attribute = null)
    {
        Models::ownedVia($model, $attribute);

        return $this;
    }

    /**
     * Set the model to be used for abilities.
     *
     * @param  string $model
     *
     * @return $this
     */
    public function useAbilityModel($model)
    {
        Models::setAbilitiesModel($model);

        return $this;
    }

    /**
     * Set the model to be used for roles.
     *
     * @param  string $model
     *
     * @return $this
     */
    public function useRoleModel($model)
    {
        Models::setRolesModel($model);

        return $this;
    }

    /**
     * Set the model to be used for users.
     *
     * @param  string $model
     *
     * @return $this
     */
    public function useUserModel($model)
    {
        Models::setUsersModel($model);

        return $this;
    }

    /**
     * Set custom table names.
     *
     * @param  array $map
     *
     * @return $this
     */
    public function tables(array $map)
    {
        Models::setTables($map);

        return $this;
    }

    /**
     * Get the model scoping instance.
     *
     *
     * @param Scope|null $scope
     *
     * @return mixed
     */
    public function scope(Scope $scope = null)
    {
        return Models::scope($scope);
    }
}
