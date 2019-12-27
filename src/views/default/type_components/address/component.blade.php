<div class='form-group {{$header_group_class}} {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
	<label class='control-label col-sm-2'>{{$form['label']}} {!!($required)?"<span class='text-danger' title='This field is required'>*</span>":"" !!}</label>

	<div class="{{$col_width?:'col-sm-10'}}">
	<input type='text' title="{{$form['label']}}" {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} {{$validation['max']?"maxlength=$validation[max]":""}} class='form-control' name="{{$name}}" id="{{$name}}" value='{{$value}}'/>

	<div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
	<p class='help-block'>{{ @$form['help'] }}</p>

	</div>
</div>
@push('head')
	<link rel="stylesheet" href="{{ URL::asset('css/suggestions.min.css') }}" />
@endpush
@push('bottom')
	<script type="text/javascript" src="{{ URL::asset('js/jquery.suggestions.min.js') }}"></script>
	<script>
	  let address;
	  function showSelected(suggestion) {
	    console.log($('#{{$name}}').val())
	  }

    $(() => {
        console.log('init suggestions')
        $('#{{$name}}').suggestions({
            addon: "spinner",
            token: "08e783f05fe1d3f7b81850052f6e93ea3701fc69",
            type: "ADDRESS",
            count: 10,
            hink: "Выберите вариант или продолжите ввод",
            initializeInterval: 100,
            geoLocation: false,
            restrict_value: true,
            constraints: {
                locations: {
                    kladr_id: "3500000000000",
                },
            },
            onSelect: showSelected
        });
    });
	</script>
@endpush
