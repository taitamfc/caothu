<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TestSoiKeo</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<style type="text/css">
		img {
			width: 50px;
		}
	</style>

</head>
<body>
	<div id="app_tool_filter" class="app_fiter_soikeo">
	   <!----> 
	   <div class="ng-scope">
	      <div class="container apt ng-scope">
	         <div class="main-functional">
	            <div class="main-fiter-soikeo">
	               <table class="table table_lsdd tbsoikeo">
						<tr>
							<th>Time</th>
							<th>League</th>
							<th class="align_center">Match</th>
							<th></th>
						</tr>
						<tr v-for="item in items">
							<td class="time">{{ item.game_date }}</td>
							<td class="league">{{ item.league_name }}</td>
							<td class="td_dau">
								<div class="row">
									<div class="col-lg-5">
										<p>{{ item.home_team }}</p> 
										<span><img v-bind:src="item.home_logo"></span>
									</div> 
									<div class="col-lg-2"><span>vs</span></div> 
									<div class="col-lg-5">
										<span><img v-bind:src="item.away_logo"></span> 
										<p>{{ item.away_team }}</p>
									</div>
								</div>
							</td>
							<td char="btnsoikeoi">
								<a v-on:click="app_soikeo_make_link(item)" class="soi-keo">
									<div class="img-before img-sk"></div>
									Soi kèo
								</a>
							</td>
						</tr>				
					</table>
	            </div>
	         </div>
	      </div>
	   </div>
	</div>





	<script src="//cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
	<script src="//unpkg.com/axios/dist/axios.min.js"></script>
	<?php
	$ajax_url 	=  'http://localhost:8080/PHP/vaoboapi/?c=SoiKeo';
	?>
	<script type="text/javascript">
    var crawl_app = new Vue({
      	el: '#app_tool_filter',
      	data: {
	      	got_items 	  	: null,
	      	items 	  		: null,
	      	leagues 	  	: null,
	      	ajax_url  		: '<?= $ajax_url; ?>'
      	},
      	methods : {
	      	app_soikeo_make_link(item){
				this.isRunning = true;
				jQuery.ajax({
					dataType:'json',
					url: this.ajax_url+'&task=ajax_make_link',
					data : item
				})
				.done( function(response) {
					//window.location.href = response.link;
					window.open(response.link);
				});
	        },
	        get_items(){
	          axios
	          .get(this.ajax_url+'&task=ajax_get_items')
	          .then( response => {
	            this.items  = response.data.items;
	          })
	        }
      	},
      	computed: {
			  
	  	},
      	mounted () {
	      	this.get_items();
      	}
    });
</script>
</body>
</html>


