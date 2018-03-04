<div class="form-group {{$header_group_class}} {{ ($errors->first($name))?'has-error': '' }}" id="form-group-{{$name}}"
     style="{{@$formInput['style']}}">
    <label class="control-label col-sm-2">{{$label}} {!!($required)? '<span class="text-danger" title="This field is required">*</span>': '' !!}</label>

    <div class="{{$col_width?:'col-sm-10'}}">
        <input type="text" title="{{$label}}"
               {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} class="form-control inputMoney"
               name="{{$name}}" id="{{$name}}" value="{{$value}}">

        @include('crudbooster::default._form_body.underField', ['help' => $formInput['help'], 'error' => $errors->first($name)])
    </div>
</div>
