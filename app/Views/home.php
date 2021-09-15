<?php $this->extend('layouts/main') ?>
<?php $this->section('content') ?>

<div class="container d-grid gap-2">
	<h1 class="text-center">Zengo teszt</h1>
	<div class="row">
		<div class="col-mt-12 col-md-6">
			<label class="form-label" for="county-select">Megye:</label>
			<select class="form-select" name="county-select" id="county-select">
				<option value="0">Válasszon</option>
			</select>
		</div>
	</div>
	<div id="hide">
		<div class="row">
			<div class="col-mt-12 col-md-6">
				<form id="insert-city">
					<label class="form-label" for="city">Új város: </label>
					<input class="form-control" type="text" id="city" name="city"><br>
					<input id="add-city" class="btn btn-primary" type="submit" value="Felvesz">
				</form>
			</div>
		</div>
		<div>
		</div>
		<div class="row">
			<div class="col-mt-12 col-md-6">
				<p id="county-name"></p>
				<p>Városok:</p>
				<ul id="city-list">
				</ul>
				<p id="emptyMessage">Még nincsenek városok</p>
			</div>
		</div>
	</div>
</div>
<script>
$('document').ready(function(){
    listCounties();
});

$("#county-select").change(function() {
	listCities();
});

$("#insert-city").submit(function(e) {
	e.preventDefault();
	insertCity();
});

$("#city-list").on('click', 'li', function() {
	if ( $(this).children().length == 0 ) {
		var id = $(this).val();
		var city_name = $(this).text();
		$(this).replaceWith('<li value="' + id + '"><input id="' + id + '" value="' + city_name + '">' + 
		'<a onclick="deleteCity(' + id + ');" href="javascript:void(0);">Törlés</a>' + 
		'<a onclick="updateCity(' + id + ');" href="javascript:void(0);">Módosít</a> ' +
		'<a onclick="undo(' + id + ');" href="javascript:void(0);">Mégse</a></li>');
	}
});

function undo(id){
	var city_name = $('#city-list li #' + id).val();
	$('#city-list li[value="' + id + '"]').replaceWith('<li value="' + id + '">' + city_name + '</li>');
}

function listCounties(){
	$.ajax({
		url: "<?php echo base_url(); ?>/counties",
		type: "post",
		dataType: "json",
		success: function(data){
			$.each(data, function(i, value) {
            $('#county-select').append($('<option>').text(value.county_name).attr('value', value.id));
        });
		}
	});
}

function listCities(){
	$("#city-list").empty();
    $.ajax({
		url: "<?php echo base_url(); ?>/cities",
		type: "post",
		dataType: "json",
		data: {
			id: jQuery('#county-select').val(),
		},
		success: function(data){
			$("#county-name").html('Megye: <strong>' + $('#county-select').find(":selected").text() + '</strong>');
			if(isEmpty(data)) {
				$("#emptyMessage").show();
			}else{
				$("#emptyMessage").hide();
				$.each(data, function(i, value) {
            	$('#city-list').append($('<li>').text(value.city_name).attr('value', value.id));
       	 	  });
			}
		}
	});
	if(isSelectedCounty()){
		$("#hide").show();
	}else{
		$("#hide").hide();
	}
}

function insertCity(){
	var city = $("#city").val();
	var county_id = $('#county-select').find(":selected").val();

	if(county_id == 0) {
		alert("Válasszon egy megyét!");
	}else if(city === "") {
		alert("Írjon be egy városnevet!");
	}else{
		$.ajax({
			url: "<?php echo base_url(); ?>/insert",
			type: "post",
			dataType: "json",
			data: {
				city: city,
				county_id: county_id
			},
			success: function(){

			}	
		});
		listCities();
		$("#city").val("");
	}
}

function updateCity(id){
	var city_name = $('#city-list li #' + id).val();
	$.ajax({
		url: "<?php echo base_url(); ?>/update",
		type: "post",
		dataType: "json",
		data: {
			id: id,
			city_name: city_name
		},
		success: function(){
			
		}
	});
	listCities();
}

function deleteCity(id){
	$.ajax({
		url: "<?php echo base_url(); ?>/delete",
		type: "post",
		dataType: "json",
		data: {
			id: id
		},
		success: function(){
			
		}
	});
	listCities();
}

function isSelectedCounty(){
	return $('#county-select').find(":selected").val() != 0;
}

function isEmpty(obj){
    return Object.keys(obj).length === 0;
}

</script>
<?php $this->endSection() ?>