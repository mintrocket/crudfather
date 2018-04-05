	@push('bottom')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>


    <script type="text/javascript">

        $(function() {
						setTimeout(()=> { location.reload(); }, 60000);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).ajaxStart(function() {
                $('.btn-save-statistic').html("<i class='fa fa-spin fa-spinner'></i>");
            })
            $(document).ajaxStop(function() {
                $('.btn-save-statistic').html("<i class='fa fa-save'></i> Auto Save Ready");
            })

            $('.btn-show-sidebar').click(function(e)  {
                e.stopPropagation();
            })
            $('html,body').click(function() {
                $('.control-sidebar').removeClass('control-sidebar-open');
            })
        })
    </script>
    @endpush
    @push('head')
    <style type="text/css">
        .control-sidebar ul {
            padding:0 0 0 0;
            margin:0 0 0 0;
            list-style-type:none;
        }
        .control-sidebar ul li {
            text-align: center;
            padding: 10px;
            border-bottom: 1px solid #555555;
        }
        .control-sidebar ul li:hover {
            background: #555555;
        }
        .control-sidebar ul li .title {
            text-align: center;
            color: #ffffff;
        }
        .control-sidebar ul li img {
            width: 100%;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #000000;
        }

        ::-webkit-scrollbar-thumb {
            background: #333333;
        }
    </style>
    @endpush

    @push('bottom')
	<!-- ADDITION FUNCTION FOR BUTTON -->
	<script type="text/javascript">
        var id_cms_statistics = '{{$id_cms_statistics}}';

        function addWidget(id_cms_statistics,area,component) {
            var id = new Date().getTime();
            $('#'+area).append("<div id='"+id+"' class='area-loading'><i class='fa fa-spin fa-spinner'></i></div>");

            var sorting = $('#'+area+' .border-box').length;
            $.post("{{CRUDBooster::mainpath('add-component')}}",{component_name:component,id_cms_statistics:id_cms_statistics,sorting:sorting,area:area},function(response) {
                $('#'+area).append(response.layout);
                $('#'+id).remove();
            })
        }

	</script>
	<!--END HERE-->
    @endpush


    @push('head')
	<!-- jQuery UI 1.11.4 -->
    <style type="text/css">
        .sort-highlight {
            border:3px dashed #cccccc;
        }
        .layout-grid {
            border:1px dashed #cccccc;
            min-height: 150px;
        }
        .layout-grid + .layout-grid {
            border-left:1px dashed transparent;
        }
        .border-box {
        	position: relative;
        }
        .border-box .action {
        	font-size: 20px;
        	display: none;
        	text-align: center;
        	display: none;
        	padding:3px 5px 3px 5px;
        	background:#DD4B39;
        	color:#ffffff;
        	width: 70px;
        	-webkit-border-bottom-right-radius: 5px;
			-webkit-border-bottom-left-radius: 5px;
			-moz-border-radius-bottomright: 5px;
			-moz-border-radius-bottomleft: 5px;
			border-bottom-right-radius: 5px;
			border-bottom-left-radius: 5px;
			position: absolute;
			margin-top: -20px;
			right: 0;
			z-index: 999;
			opacity: 0.8;
        }
        .border-box .action a {
        	color: #ffffff;
        }

        .border-box:hover {
        	/*border:2px dotted #BC3F30;*/
        }

        @if(CRUDBooster::getCurrentMethod() == 'getBuilder')
        .border-box:hover .action {
        	display: block;
        }
        .panel-heading, .inner-box, .box-header, .btn-add-widget {
            cursor: move;
        }
        @endif

        .connectedSortable {
        	position: relative;
        }
        .area-loading {
        	position: relative;
        	width: 100%;
        	height: 130px;
        	background: #dedede;
        	border: 4px dashed #cccccc;
        	font-size: 50px;
        	color: #aaaaaa;
        	margin-bottom: 20px;
        }
        .area-loading i {
        	position: absolute;
        	left:45%;
        	top:30%;
        	transform: translate(-50%, -50%);
        }
    </style>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    @endpush

    @push('bottom')
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript">
    $(function() {
        var cloneSidebar = $('.control-sidebar').clone();

        @if(CRUDBooster::getCurrentMethod() == 'getBuilder')
            createSortable();
        @endif

        function createSortable() {
            $(".connectedSortable").sortable({
                placeholder: "sort-highlight",
                connectWith: ".connectedSortable",
                handle: ".panel-heading, .inner-box, .box-header, .btn-add-widget",
                forcePlaceholderSize: true,
                zIndex: 999999,
                stop: function(event, ui) {
                    console.log(ui.item.attr('class'));
                    var className = ui.item.attr('class');
                    var idName = ui.item.attr('id');
                    if(className == 'button-widget-area') {
                        var areaname = $('#'+idName).parent('.connectedSortable').attr('id');
                        var component = $('#'+idName+' > a').data('component');
                        console.log(areaname);
                        $('#'+idName).remove();
                        addWidget(id_cms_statistics,areaname,component);
                        $('.control-sidebar').html(cloneSidebar);
                        cloneSidebar = $('.control-sidebar').clone();

                        createSortable();
                    }
                },
                update: function(event, ui){
                    if(ui.sender){
                        var componentID = ui.item.attr('id');
                        var areaname = $('#'+componentID).parent('.connectedSortable').attr("id");
                        var index = $('#'+componentID).index();


                        $.post("{{CRUDBooster::mainpath('update-area-component')}}",{componentid:componentID,sorting:index,areaname:areaname},function(response) {

                        })
                    }
                }
              });
        }

    })

    </script>

    <script type="text/javascript">
        $(function() {
					return; // off

        	$('.connectedSortable').each(function() {
        		var areaname = $(this).attr('id');

        		$.get("{{CRUDBooster::mainpath('list-component')}}/"+id_cms_statistics+"/"+areaname,function(response) {
        			if(response.components) {

        				$.each(response.components,function(i,obj) {
        					$('#'+areaname).append("<div id='area-loading-"+obj.componentID+"' class='area-loading'><i class='fa fa-spin fa-spinner'></i></div>");
        					$.get("{{CRUDBooster::mainpath('view-component')}}/"+obj.componentID,function(view) {
        						console.log('View For CID '+view.componentID);
        						$('#area-loading-'+obj.componentID).remove();
        						$('#'+areaname).append(view.layout);

        					})
        				})
        			}
        		})
        	})


            $(document).on('click','.btn-delete-component',function() {
            	var componentID = $(this).data('componentid');
            	var $this = $(this);

            	swal({
				  title: "Are you sure?",
				  text: "You will not be able to recover this widget !",
				  type: "warning",
				  showCancelButton: true,
				  confirmButtonColor: "#DD6B55",
				  confirmButtonText: "Yes",
				  closeOnConfirm: true
				},
				function(){

	            	$.get("{{CRUDBooster::mainpath('delete-component')}}/"+componentID,function() {
	            		$this.parents('.border-box').remove();

	            	});
				});

            })
            $(document).on('click','.btn-edit-component',function() {
				var componentID = $(this).data('componentid');
				var name        = $(this).data('name');

            	$('#modal-statistic .modal-title').text(name);
            	$('#modal-statistic .modal-body').html("<i class='fa fa-spin fa-spinner'></i> Please wait loading...");
            	$('#modal-statistic').modal('show');

            	$.get("{{CRUDBooster::mainpath('edit-component')}}/"+componentID,function(response) {
                    $('#modal-statistic .modal-body').html(response);
                })
            })

            $('#modal-statistic .btn-submit').click(function() {

                $('#modal-statistic form .has-error').removeClass('has-error');

                var required_input = [];
                $('#modal-statistic form').find('input[required],textarea[required],select[required]').each(function() {
                    var $input = $(this);
                    var $form_group = $input.parent('.form-group');
                    var value = $input.val();

                    if(value == '') {
                        required_input.push($input.attr('name'));
                    }
                })

                if(required_input.length) {
                    setTimeout(function() {
                        $.each(required_input,function(i,name) {
                            $('#modal-statistic form').find('input[name="'+name+'"],textarea[name="'+name+'"],select[name="'+name+'"]').parent('.form-group').addClass('has-error');
                        })
                    },200);

                    return false;
                }

            	var $button = $(this).text('Saving...').addClass('disabled');

            	$.ajax({
            		data:$('#modal-statistic form').serialize(),
            		type:'POST',
            		url:"{{CRUDBooster::mainpath('save-component')}}",
            		success:function() {

            			$button.removeClass('disabled').text('Save Changes');
            			$('#modal-statistic').modal('hide');
            			window.location.href = "{{Request::fullUrl()}}";
            		},
            		error:function() {
            			alert('Sorry something went wrong !');
            			$button.removeClass('disabled').text('Save Changes');
            		}
            	})
            })
        })
    </script>
    @endpush

    <div id='modal-statistic' class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Modal title</h4>
	      </div>
	      <div class="modal-body">
	        <p>One fine body&hellip;</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn-submit btn btn-primary" data-loading-text="Saving..." autocomplete="off">Save changes</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

    <div id='statistic-area'>

			<?php
				$components = DB::table('cms_statistic_components')->where('id_cms_statistics',$id_cms_statistics)->orderBy('sorting')->get();
				$smallboxes = $components->where('component_name','smallbox')->sortBy('sorting');
				$chartareas = $components->where('component_name','chartarea')->sortBy('sorting');
				// dd($smallboxes);
			?>

			<?php //use Carbon\Carbon; dd(Carbon::now()); //dd(Carbon::parse("21.01.2018")); ?>

        <div class="statistic-row row">
						@foreach($smallboxes as $smallbox)
							<?php $value = eval($smallbox->config); ?>
							<div class="col-sm-3">
								<div id='{{$componentID}}' class='border-box'>

									<div class="small-box {{$value['color']}}">
									    <div class='inner inner-box'>
									      <h3>{{$value['data']}}</h3>
									      <p>{{$value['name']}}</p>
									    </div>
									    <div class="icon">
									      <i class="ion {{$value['icon']}}"></i>
									    </div>
									    <a href="{{$value['link']}}" class="small-box-footer">Подробнее... <i class="fa fa-arrow-circle-right"></i></a>
									</div>

								</div>
							</div>
						@endforeach
        </div>

				<?php


				 ?>

        <div class='statistic-row row'>
            <div id='area5' class="col-sm-12 connectedSortable">
							@foreach($chartareas as $chartarea)
								<?php $value = eval($chartarea->config); ?>
								<div class='border-box'>

									<div class="panel panel-default">
											<div class="panel-heading" style="display:flex; flex-direction: row; align-items:center; justify-content: space-between;" id="charttitle{{$chartarea->id}}">
												<label>{{$value['name']}}</label>
												@if($value['count'])
													<label>Всего заказов: {{$value['count']}}</label>
												@endif
											</div>
								      <div class="panel-body">
												<div id="chartContainer-{{$chartarea->id}}" style="height: 250px;"></div>
												@if($value['ranges'])
												<form id="chartForm{{$chartarea->id}}" action="{{CRUDBooster::mainpath('view-component')."/".$chartarea->id}}" method="GET" style="display:flex; flex-direction: row; align-items:center; justify-content: center;">

													<div style="display:flex; flex-direction: row; align-items:center; justify-content: start;">
														<label>От</label>
													</div>
													<div class="col-sm-3">
														<div class="input-group">
															<span class="input-group-addon"><a href="javascript:void(0)" onclick="$(&quot;#pre_ordered_at&quot;).data(&quot;daterangepicker&quot;).toggle()"><i class="fa fa-calendar"></i></a></span>
															<input type="text" title="От" readonly="" class="form-control notfocus datepicker active" name="date_from" id="date_from{{$chartarea->id}}" value="{{Carbon\Carbon::today()->subMonth()->addDay()->toDateString()}}">
														</div>
													</div>

													<div style="display:flex; flex-direction: row; align-items:center; justify-content: start;">
														<label>До</label>
													</div>
													<div class="col-sm-3">
														<div class="input-group">
															<span class="input-group-addon"><a href="javascript:void(0)" onclick="$(&quot;#pre_ordered_at&quot;).data(&quot;daterangepicker&quot;).toggle()"><i class="fa fa-calendar"></i></a></span>
															<input type="text" title="До" readonly="" class="form-control notfocus datepicker active" name="date_to" id="date_to{{$chartarea->id}}" value="{{Carbon\Carbon::today()->toDateString()}}">
														</div>
													</div>
													<button type="submit" class="btn btn-success">Перестроить</button>

												</form>
												@endif

								      </div>
								    </div>

								</div>
								@push('bottom')
								<script type="text/javascript">
								$(function() {
									chartarea{{$chartarea->id}} = new Morris.Area({
										element: 'chartContainer-{{$chartarea->id}}',
										data: $.parseJSON("{!! addslashes($value['data']) !!}"),
										xkey: 'y',
										ykeys: {!! json_encode(['x']) !!},
										labels: {!! json_encode([$value['label']]) !!},
										parseTime: false,
										resize: true,
										@if($config->goals)
											goals: [{{$config->goals}}],
										@endif
										behaveLikeLine:true,
											hideHover: 'auto'
									});
								})
								$('#chartForm{{$chartarea->id}}').on('submit', e => {
									e.preventDefault();

									let date_from = $('#date_from{{$chartarea->id}}').val();
									let date_to 	= $('#date_to{{$chartarea->id}}').val();

									$.get(e.target.action+'?date_from='+date_from+'&date_to='+date_to, response => {
										console.log(response);
										$('#charttitle{{$chartarea->id}}').html(response.name);
										chartarea{{$chartarea->id}}.setData(response.data);
									});
								});
								</script>
								@endpush

							@endforeach
            </div>
        </div>

    </div><!--END STATISTIC AREA-->
