@if($groups->count()>0)
    @foreach($groups as $group)
        @if($group_abilities=$group->abilities->filter(function ($ability) use($entity_type){
        return $ability->entity_type===$entity_type;
        }) )
            @if($group_abilities->count()>0)
                <p>{{__($group->name)}}</p>
                <div class="row">
                    @foreach($group_abilities as $ability)
                        <div class="input-field">
                            <input type="checkbox"
                                   id="brand-general-permission-{{$ability->id}}"
                                   name="ability[{{$ability->id}}]" {{isset($permissions) && in_array($ability->id,$permissions->map(function($permiso){return $permiso->ability_id;})->toArray()) ? 'checked="checked"' : ''}}/>
                            <label for="brand-general-permission-{{$ability->id}}">{{__($ability->title)}} </label>
                        </div>
                    @endforeach
                </div>
                <br/>
            @endif
        @endif
    @endforeach
@endif
