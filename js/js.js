function getToday(){
	var mydate=new Date();
	var year=mydate.getYear();
	if (year < 1000)
	year+=1900;
	var day=mydate.getDay();
	var month=mydate.getMonth();
	var daym=mydate.getDate();
	if (daym<10)
	daym="0"+daym;
	var dayarray=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
	return(montharray[month]+" "+daym+", "+year);
}
function updateClock ( )
{
  var currentTime = new Date ( );

  var currentHours = currentTime.getHours ( );
  var currentMinutes = currentTime.getMinutes ( );
  var currentSeconds = currentTime.getSeconds ( );

  // Pad the minutes and seconds with leading zeros, if required
  currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
  currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

  // Choose either "AM" or "PM" as appropriate
  var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

  // Convert the hours component to 12-hour format if needed
  currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

  // Convert an hours component of "0" to "12"
  currentHours = ( currentHours == 0 ) ? 12 : currentHours;

  // Compose the string for display
  var currentTimeString = currentHours + ":" + currentMinutes ;

  // Update the time display
  document.getElementById("clock").innerHTML = "asdasdasd";
}
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}
$(window).load(function(){
	$("#right_content").css("min-height", $("#left_content").css("height"));
	$(".file-select-option").change(function(){
		$target_container=jQuery("#"+$(this).attr('data-selected-file-display'));
		console.log($target_container);
		$src=jQuery(this).find('option:selected').attr('data-img-src');
		if($target_container.length && $src)
			$target_container.html('<img src="'+$src+'" />');
	});
	$(".file-select-option").change();
	$(".file-select-button").click(function(){
		$target_container=jQuery("#"+$(this).attr('data-selected-file-display'));
		$target_input=jQuery("#"+$(this).attr('data-input-field-id'));
		$target_input.unbind('change').change(function(){
			$target_container.html(jQuery(this).val());
		});
		$target_input.click();
	});
});
$(document).ready(function(){
	$(".file-select-option").change(function(){
		$target_container=jQuery("#"+$(this).attr('data-selected-file-display'));
		console.log($target_container);
		$src=jQuery(this).find('option:selected').attr('data-img-src');
		if($target_container.length && $src)
			$target_container.html('<img src="'+$src+'" />');
	});
	$(".file-select-option").change();
	$(".file-select-button").click(function(){
		$target_container=jQuery("#"+$(this).attr('data-selected-file-display'));
		$target_input=jQuery("#"+$(this).attr('data-input-field-id'));
		$target_input.unbind('change').change(function(){
			$target_container.html(jQuery(this).val());
		});
		$target_input.click();
	});
	if($(".select_multiple").length>0) $(".select_multiple").chosen();
	
	$("#select_all").change(function(){
		var chckedstatus=this.checked;
		$(".list :checkbox").each(function(){
			this.checked=chckedstatus;
		});
	});
	$("#apply_bulk_action").click(function(){
			$slectedvalue=$('#bulk_action option:selected').val();
			if($slectedvalue != "null"){
				$IDs='';
				$(".list :checkbox").each(function(){
					if(this.checked)
						if(this.value!=0)
							$IDs+=this.value+',';
				});
				$IDs=$IDs.substring(0,$IDs.length-1);
				var pagename= window.location.pathname;
				pagename=pagename.substring(pagename.lastIndexOf("/") + 1);
				window.location.href=pagename+'?tab=bulk_action&action='+$slectedvalue+'&Ids='+$IDs+'&pid='+getUrlVars()["pid"]+'&ppid='+getUrlVars()["ppid"];
			}
	});
	if($("#seo_url")){
		$("#seo_url").keyup(function(){
			$new=$(this).attr('value').toLowerCase();							  
			$(this).attr('value',$new.replace(" ", "-"));
		});
	}
	$(".dropdown.link").click(function(){
		$(".dropdown-menu").toggle();
	});
	$("#topstats.btn").click(function(){
		$(".topstats.search_filter").slideToggle();
	});
	$(".sidebar-open-button").click(function(){
		$(".sidebar").toggle()
		if($(window).width()>768){
			if($(".sidebar").hasClass('closed')){
				$(".sidebar").removeClass('closed')
				$(".content").css("margin-left","250px");
			}
			else{
				$(".sidebar").addClass('closed')
				$(".content").css("margin-left","0px");
			}
		}
	});
	$('.nav > li > a').click(function(){
		if ($(this).attr('class') != 'active'){
		  	$('.nav li ul').slideUp();
		  	$(this).next().slideToggle();
		  	$('.nav li a').removeClass('active');
		  	$(this).addClass('active');
		}
		else{
			$(this).next().slideToggle();
		  	$(this).removeClass('active');
		}
	});
	
	if($(".item_select").length>0){
		$clone_container = $(".sale_item");
		if( $clone_container.length == 0 ){
			$clone_container = $(".purchase_item");
		}
		$(".item_select").change(function(){
			$val=$(this).find('option:selected').val();
			var $container=$(this).parents(".sale_item");
			if( $container.length > 0 ) {
				//$container.find(".unit_price").val(0);
				if (($('#transaction_id').length > 0)){
					$transId=$('#transaction_id').val();
				}
				else
					$transId=0;
				if($val>0){
					$.get("sales_manage.php", {"tab":"get_unit_price","id":$val ,'transcationid':$transId}, function($unit_price){
						//if(Number($container.find(".unit_price").val()) <= 0){
							$container.find(".unit_price").val($unit_price);
						//}
						update_total($container);
					});
					$.get("sales_manage.php", {"tab":"get_quantity","id":$val ,"transcationid":$transId }, function($quantity){
						$container.find(".qty").html($quantity);
						update_total($container);
						$(".quantity").change(function(){
							$tot_itm = parseInt($container.find(".quantity").val());
							if($quantity < $tot_itm){
								console.log($quantity);
								console.log($tot_itm);
								$container.addClass('bg-danger');
							}
							else{
								$container.removeClass('bg-danger');
							}
						})
						
						if($quantity==0){
							$container.addClass('bg-danger');
						}
						else{
							$container.removeClass('bg-danger');
						}
					});
					$.get("stock_movement_manage.php", {"tab":"get_unit_price","id":$val ,'transcationid':$transId}, function($unit_price){
						//if(Number($container.find(".unit_price").val()) <= 0){
							$container.find(".unit_price1").val($unit_price);
						//}
						update_total($container);
					});
					// $.get("stock_movement_manage.php", {"tab":"get_quantity","id":$val ,"transcationid":$transId }, function($quantity){
					// 	$container.find(".qty").html($quantity);
					// 	update_total($container);
					// });
				}
				else{
					update_total($container);
				}
			}
			else{
				var $container=$(this).parents(".purchase_item");
				update_total($container);
			}
		}).change();
		$(".sale_item .unit_price, .sale_item .unit_price1, .sale_item .quantity, .sale_item .unit, .sale_item .total_kg, .purchase_item .quantity, .purchase_item .unit, .purchase_item .total_kg, .purchase_item .hundred_pieces_kg, .purchase_item .unit_price").change(function(){
			$container=$(this).parents(".sale_item");
			//$('.sale_item .quantity')		
			if( $container.length > 0 ) {
				$id=$container.find(".item_select option:selected").val();
				$val=parseInt($(this).val());
				if($id>0 && $val>0){
					// if edit recrd
					if (($('#transaction_id').length > 0)){
						$transId=$('#transaction_id').val();
						}
					else
						$transId=0;
					$.get("sales_manage.php", {"tab":"get_quantity","id":$id ,"transcationid":$transId}, function($quantity){
						// if($val>$quantity){
						// 	$container.find(".quantity").val($quantity);
						// }
						update_total($container);		
					});
				}
				else{
					update_total($container);
				}
			}
			else{
				$container=$(this).parents(".purchase_item");
				update_total($container);
			}
		});
		$("#discount").change(function(){
			$this = $(this);
			update_total($("."+$this.data("container_class")+":first-child"));
		});
		
		$("#cash_receive").change(function(){
			$this = $(this);
			$return = $this.val() - $grand_total_price;
			$("#cash_return").val($return);
		});
		$(".add_list_item").click(function(e){
			e.preventDefault();
			$this = $(this);
			
			
			$container=$this.parents("."+$this.data("container_class")).last();
			
			$new_container = $clone_container.clone(true);
			$new_container.find( '.quantity' ).val(1);
			$new_container.find( '.unit_price, .total_price' ).val(0);
			//$new_container.find('.chosen-container').remove();
			//$new_container.find("select.item_select").show();
			$new_container.insertAfter($container);
			$("."+$this.data("container_class")).last().find("select.item_select").val('').chosen({"width": "100%"});
			update_total($container);
		});
		$(".delete_list_item").click(function(e){
			e.preventDefault();
			$this = $(this);
			if($(".delete_list_item").length>1){
				$container = $this.parents("."+ $this.data("container_class") );
				$temp_container = $container.prev() || $container.next();
				$container.remove();
				update_total($temp_container);
			}
			else{
				alert("There must an an item in a list.");
			}
		});
		$clone_container = $clone_container.last().clone(true);
		$("select.item_select").chosen({width: "100%"});
	}
});
function update_total($container){
	if($container.hasClass("sale_item"))
		$class="sale_item";
	else
		$class="purchase_item";
		total_kg = $container.find(".unit").val();
		console.log(total_kg);
		if(total_kg==1){
			$container.find(".total_price").val(parseFloat($container.find(".unit_price").val())*parseFloat($container.find(".total_kg").val()));
			if($class=="purchase_item"){
				$container.find(".quantity").val(parseFloat($container.find(".total_kg").val())/parseFloat($container.find(".hundred_pieces_kg").val())*100);
			}

			
		}
		else{
			$container.find(".total_price").val(parseFloat($container.find(".unit_price").val())*parseFloat($container.find(".quantity").val()));
			$container.find(".total_price1").val(parseFloat($container.find(".unit_price1").val())*parseFloat($container.find(".quantity").val()));
		}
	
	$container=$container.parent();
	$grand_total_item=$grand_total_price=$grand_total_price1=0;
	$sn=1;
	$container.find("."+$class).each(function(){
		$(this).find(".serial_number").html($sn);
		$grand_total_item+=parseFloat($(this).find(".quantity").val());
		$grand_total_price+=parseFloat($(this).find(".total_price").val());
		$grand_total_price1+=parseFloat($(this).find(".total_price1").val());
		$sn++;
	});
	$grand_total_price -= parseFloat($("#discount").val());
	$container.find('.grand_total_item').html($grand_total_item);
	
	//if( $("input[name='id']").length == 0 && $('#payment_amount').length > 0 && $('#payment_account_id').val() != ''  ){
	if($('#payment_amount').length > 0 && $('#payment_account_id').val() != '' ){
		
		$("#type").change(function(){
			$typeval=$(this).find('option:selected').val();
			console.log($typeval);
			if($typeval==1){
				$('.payment_amount').val(0);
				$('#payment_account_id').val(0);
				$('.payment_amount').prop('disabled', true);
			}
			else{
				$('.payment_amount').prop('disabled', false);
				$container.find('.payment_amount').val($grand_total_price);
			}
			
		});
		$container.find('.payment_amount').val($grand_total_price);
		
	}
	
	if( $grand_total_price >= 0 || $grand_total_price1 >= 0 ) {
		$container.find('.grand_total_price').html("Rs. "+(Math.round($grand_total_price* 100) / 100));
		$container.find('.grand_total_price1').html("Rs. "+(Math.round($grand_total_price1* 100) / 100));
	}
	else{
		$container.find('.grand_total_price').html("Rs. "+"("+(Math.round(Math.abs($grand_total_price)* 100) / 100)+")");
		
	}
}
