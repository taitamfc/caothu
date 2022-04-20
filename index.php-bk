<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link href="style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <title>Tool</title>
  </head>
  <body>

    <div class="p-3">


      <div class="table-responsive" id="app_odds">

        <div class="dntable-filter d-sm-flex align-items-center pt-3 mb-3">
          <div class="me-3 mb-2">
            <span>Thời gian đá:</span>
            <select name="" id="">
              <option value="">1/4/2022</option>
              <option value="">2/4/2022</option>
              <option value="">3/4/2022</option>
            </select>
          </div>

          <div class="me-3 mb-2">
            <span>Thời gian biến động kèo:</span>
            <select name="" id="">
              <option value="">3h</option>
              <option value="">6h</option>
              <option value="">12h</option>
              <option value="">1 Ngày</option>
              <option value="">2 Ngày</option>
              <option value="">3 Ngày</option>
            </select>
          </div>

          <div class="me-3 mb-2">
            <span>Giá trị biến động:</span>
            <div class="form-check form-check-inline me-2">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
              <label class="form-check-label" for="inlineCheckbox1">0,25</label>
            </div>
            <div class="form-check form-check-inline me-2">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
              <label class="form-check-label" for="inlineCheckbox2">0,5</label>
            </div>
            <div class="form-check form-check-inline me-2">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
              <label class="form-check-label" for="inlineCheckbox3">0,75</label>
            </div>
            <div class="form-check form-check-inline me-2">
              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
              <label class="form-check-label" for="inlineCheckbox3">1</label>
            </div>

          </div>

          <div class="mb-2">
            <button type="submit" class="btn btn-primary px-5">Lọc</button>
          </div>

        </div>

        <table class="dntable-list table table-bordered align-middle ">
          <thead>
            <tr>
              <th class="th-time" rowspan="2">Time</th>
              <th class="th-timestart" rowspan="2">Thời gian<br>mở kèo</th>
              <th class="th-league" rowspan="2">League</th>
              <th class="th-match" rowspan="2">Match</th>
              <th class="th-result" rowspan="2">Kết quả</th>
              <th class="th-ftht" rowspan="2">FT/HT</th>
              <th class="th-handicap" colspan="7">Kèo Handicap</th>
              <th class="th-overunder" colspan="7">Over/Under</th>
              <th class="th-view" rowspan="2"></th>
            </tr>
            <tr>
              <th class="th-handicap-live">Kèo Live</th>
              <th class="th-handicap-odds-live">Odds Live</th>
              <th class="th-handicap-slot-open">Mở kèo</th>
              <th class="th-handicap-odds-open">Odds Mở</th>
              <th class="th-handicap-bd-live">Biến động<br>kèo(Live)</th>
              <th class="th-handicap-odds-bd">Odds<br>biến động</th>
              <th class="th-handicap-tips">Tips</th>
              <th class="th-handicap-keo-live">Kèo Live</th>
              <th class="th-overunder-odds">Odds</th>
              <th class="th-overunder-slot-open">Mở kèo </th>
              <th class="th-overunder-odds">Odds</th>
              <th class="th-overunder-bd-live">Biến động<br>Live</th>
              <th class="th-overunder-odds-bd">Odds<br>biến động</th>
              <th class="th-overunder-tip">Tips</th>
            </tr>
          </thead>
          <tbody>

            <!-- Start row -->
              <tr class="tr__start">
                <td class="td-time" rowspan="4">19/03/2022<br>03:00</td>
                <td class="td-timestart" rowspan="4">10/03/2022<br>20:54</td>
                <td class="td-league" rowspan="4">English Premier League</td>
                <td class="td-match" rowspan="4">
                  <div>
                    <p class="home-team">Wolves</p>
                    <p class="away-team">Leeds United</p>
                  </div>
                </td>
                <td class="td-result" rowspan="2">2</td>
                <td class="td-ftht" rowspan="2">FT</td>
                <td class="el--down">- 0.25</td>
                <td class="el--up">0.95</td>
                <td class="el--down">-0.5</td>
                <td class="el--down">-0.85</td>
                <td class="el--down">0.25 <i class="icon-down"></i></td>
                <td class="el--down">0.3 <i class="icon-down"></i></td>
                <td rowspan="2">Leeds United +0.25</td>
                <td rowspan="2">2/2.5</td>
                <td>O 0.88</td>
                <td rowspan="2" class="el--up">2.5</td>
                <td>O 0,96</td>
                <td rowspan="2" class="el--up">0.25 <i class="icon-up"></i></td>
                <td class="el--up">O 0.07 <i class="icon-up"></i></td>
                <td rowspan="2">Over 2.75</td>
                <td class="td-viewfull" rowspan="4"><a href="" class="el--view">View<i class="icon-view"></i></a></td>
              </tr>
              <tr>
                <td class="el--up">0.25</td>
                <td class="el--up">0.95</td>
                <td class="el--up">0.5</td>
                <td class="el--up">0.75</td>
                <td class="el--up">0.25 <i class="icon-up"></i></td>
                <td class="el--up">0.3 <i class="icon-up"></i></td>
                <td>U - 0.98</td>
                <td>U 0,94</td>
                <td class="el--down">U 0.07 <i class="icon-down"></i></td>
              </tr>
              <tr>
                <td rowspan="2">3</td>
                <td rowspan="2">HT</td>
                <td>0</td>
                <td class="el--up">0.68</td>
                <td>0</td>
                <td class="el--up">0,65</td>
                <td>0</td>
                <td class="el--up">0.55 <i class="icon-up"></i></td>
                <td rowspan="2">Ko có tips HT</td>
                <td rowspan="2">1</td>
                <td>O 0.98</td>
                <td rowspan="2">1</td>
                <td>O 0.93</td>
                <td rowspan="2">0</td>
                <td class="el--up">O 0.06 <i class="icon-up"></i></td>
                <td rowspan="2"></td>
              </tr>
              <tr>
                <td>0</td>
                <td class="el--down">-0.8</td>
                <td>0</td>
                <td class="el--down">-0.77</td>
                <td>0</td>
                <td class="el--down">0.55 <i class="icon-down"></i></td>
                <td>U 0.88</td>
                <td>U 0.93</td>
                <td class="el--down">U 0.05 <i class="icon-down"></i></td>
              </tr>
            <!-- End row -->


            <!-- Start row -->
              <tr class="tr__start">
                <td class="td-time" rowspan="4">19/03/2022<br>03:00</td>
                <td class="td-timestart" rowspan="4">10/03/2022<br>20:54</td>
                <td class="td-league" rowspan="4">English Premier League</td>
                <td class="td-match" rowspan="4">
                  <div>
                    <p class="home-team">Wolves</p>
                    <p class="away-team">Leeds United</p>
                  </div>
                </td>
                <td class="td-result" rowspan="2">2</td>
                <td class="td-ftht" rowspan="2">FT</td>
                <td class="el--down">- 0.25</td>
                <td class="el--up">0.95</td>
                <td class="el--down">-0.5</td>
                <td class="el--down">-0.85</td>
                <td class="el--down">0.25 <i class="icon-down"></i></td>
                <td class="el--down">0.3 <i class="icon-down"></i></td>
                <td rowspan="2">Leeds United +0.25</td>
                <td rowspan="2">2/2.5</td>
                <td>O 0.88</td>
                <td rowspan="2" class="el--up">2.5</td>
                <td>O 0,96</td>
                <td rowspan="2" class="el--up">0.25 <i class="icon-up"></i></td>
                <td class="el--up">O 0.07 <i class="icon-up"></i></td>
                <td rowspan="2">Over 2.75</td>
                <td class="td-viewfull" rowspan="4"><a href="" class="el--view">View<i class="icon-view"></i></a></td>
              </tr>
              <tr>
                <td class="el--up">0.25</td>
                <td class="el--up">0.95</td>
                <td class="el--up">0.5</td>
                <td class="el--up">0.75</td>
                <td class="el--up">0.25 <i class="icon-up"></i></td>
                <td class="el--up">0.3 <i class="icon-up"></i></td>
                <td>U - 0.98</td>
                <td>U 0,94</td>
                <td class="el--down">U 0.07 <i class="icon-down"></i></td>
              </tr>
              <tr>
                <td rowspan="2">3</td>
                <td rowspan="2">HT</td>
                <td>0</td>
                <td class="el--up">0.68</td>
                <td>0</td>
                <td class="el--up">0,65</td>
                <td>0</td>
                <td class="el--up">0.55 <i class="icon-up"></i></td>
                <td rowspan="2">Ko có tips HT</td>
                <td rowspan="2">1</td>
                <td>O 0.98</td>
                <td rowspan="2">1</td>
                <td>O 0.93</td>
                <td rowspan="2">0</td>
                <td class="el--up">O 0.06 <i class="icon-up"></i></td>
                <td rowspan="2"></td>
              </tr>
              <tr>
                <td>0</td>
                <td class="el--down">-0.8</td>
                <td>0</td>
                <td class="el--down">-0.77</td>
                <td>0</td>
                <td class="el--down">0.55 <i class="icon-down"></i></td>
                <td>U 0.88</td>
                <td>U 0.93</td>
                <td class="el--down">U 0.05 <i class="icon-down"></i></td>
              </tr>
            <!-- End row -->

            <!-- Start row -->
              <tr class="tr__start">
                <td class="td-time" rowspan="4">19/03/2022<br>03:00</td>
                <td class="td-timestart" rowspan="4">10/03/2022<br>20:54</td>
                <td class="td-league" rowspan="4">English Premier League</td>
                <td class="td-match" rowspan="4">
                  <div>
                    <p class="home-team">Wolves</p>
                    <p class="away-team">Leeds United</p>
                  </div>
                </td>
                <td class="td-result" rowspan="2">2</td>
                <td class="td-ftht" rowspan="2">FT</td>
                <td class="el--down">- 0.25</td>
                <td class="el--up">0.95</td>
                <td class="el--down">-0.5</td>
                <td class="el--down">-0.85</td>
                <td class="el--down">0.25 <i class="icon-down"></i></td>
                <td class="el--down">0.3 <i class="icon-down"></i></td>
                <td rowspan="2">Leeds United +0.25</td>
                <td rowspan="2">2/2.5</td>
                <td>O 0.88</td>
                <td rowspan="2" class="el--up">2.5</td>
                <td>O 0,96</td>
                <td rowspan="2" class="el--up">0.25 <i class="icon-up"></i></td>
                <td class="el--up">O 0.07 <i class="icon-up"></i></td>
                <td rowspan="2">Over 2.75</td>
                <td class="td-viewfull" rowspan="4"><a href="" class="el--view">View<i class="icon-view"></i></a></td>
              </tr>
              <tr>
                <td class="el--up">0.25</td>
                <td class="el--up">0.95</td>
                <td class="el--up">0.5</td>
                <td class="el--up">0.75</td>
                <td class="el--up">0.25 <i class="icon-up"></i></td>
                <td class="el--up">0.3 <i class="icon-up"></i></td>
                <td>U - 0.98</td>
                <td>U 0,94</td>
                <td class="el--down">U 0.07 <i class="icon-down"></i></td>
              </tr>
              <tr>
                <td rowspan="2">3</td>
                <td rowspan="2">HT</td>
                <td>0</td>
                <td class="el--up">0.68</td>
                <td>0</td>
                <td class="el--up">0,65</td>
                <td>0</td>
                <td class="el--up">0.55 <i class="icon-up"></i></td>
                <td rowspan="2">Ko có tips HT</td>
                <td rowspan="2">1</td>
                <td>O 0.98</td>
                <td rowspan="2">1</td>
                <td>O 0.93</td>
                <td rowspan="2">0</td>
                <td class="el--up">O 0.06 <i class="icon-up"></i></td>
                <td rowspan="2"></td>
              </tr>
              <tr>
                <td>0</td>
                <td class="el--down">-0.8</td>
                <td>0</td>
                <td class="el--down">-0.77</td>
                <td>0</td>
                <td class="el--down">0.55 <i class="icon-down"></i></td>
                <td>U 0.88</td>
                <td>U 0.93</td>
                <td class="el--down">U 0.05 <i class="icon-down"></i></td>
              </tr>
            <!-- End row -->

             <!-- Start row -->
              <tr class="tr__start">
                <td class="td-time" rowspan="4">19/03/2022<br>03:00</td>
                <td class="td-timestart" rowspan="4">10/03/2022<br>20:54</td>
                <td class="td-league" rowspan="4">English Premier League</td>
                <td class="td-match" rowspan="4">
                  <div>
                    <p class="home-team">Wolves</p>
                    <p class="away-team">Leeds United</p>
                  </div>
                </td>
                <td class="td-result" rowspan="2">2</td>
                <td class="td-ftht" rowspan="2">FT</td>
                <td class="el--down">- 0.25</td>
                <td class="el--up">0.95</td>
                <td class="el--down">-0.5</td>
                <td class="el--down">-0.85</td>
                <td class="el--down">0.25 <i class="icon-down"></i></td>
                <td class="el--down">0.3 <i class="icon-down"></i></td>
                <td rowspan="2">Leeds United +0.25</td>
                <td rowspan="2">2/2.5</td>
                <td>O 0.88</td>
                <td rowspan="2" class="el--up">2.5</td>
                <td>O 0,96</td>
                <td rowspan="2" class="el--up">0.25 <i class="icon-up"></i></td>
                <td class="el--up">O 0.07 <i class="icon-up"></i></td>
                <td rowspan="2">Over 2.75</td>
                <td class="td-viewfull" rowspan="4"><a href="" class="el--view">View<i class="icon-view"></i></a></td>
              </tr>
              <tr>
                <td class="el--up">0.25</td>
                <td class="el--up">0.95</td>
                <td class="el--up">0.5</td>
                <td class="el--up">0.75</td>
                <td class="el--up">0.25 <i class="icon-up"></i></td>
                <td class="el--up">0.3 <i class="icon-up"></i></td>
                <td>U - 0.98</td>
                <td>U 0,94</td>
                <td class="el--down">U 0.07 <i class="icon-down"></i></td>
              </tr>
              <tr>
                <td rowspan="2">3</td>
                <td rowspan="2">HT</td>
                <td>0</td>
                <td class="el--up">0.68</td>
                <td>0</td>
                <td class="el--up">0,65</td>
                <td>0</td>
                <td class="el--up">0.55 <i class="icon-up"></i></td>
                <td rowspan="2">Ko có tips HT</td>
                <td rowspan="2">1</td>
                <td>O 0.98</td>
                <td rowspan="2">1</td>
                <td>O 0.93</td>
                <td rowspan="2">0</td>
                <td class="el--up">O 0.06 <i class="icon-up"></i></td>
                <td rowspan="2"></td>
              </tr>
              <tr>
                <td>0</td>
                <td class="el--down">-0.8</td>
                <td>0</td>
                <td class="el--down">-0.77</td>
                <td>0</td>
                <td class="el--down">0.55 <i class="icon-down"></i></td>
                <td>U 0.88</td>
                <td>U 0.93</td>
                <td class="el--down">U 0.05 <i class="icon-down"></i></td>
              </tr>
            <!-- End row -->

          </tbody>
        </table>
      </div>
    </div>
    <script type="text/javascript">
		
		var app_odds = new Vue({
			el: '#app_odds',
			data: {
				isRunning  				: false,
				isRunSchedule 			: false,
				isRunningOdds  			: false,
				isRunningScore  		: false,
				isRunningEuropeanHalf   : false,
				canRunningEuropeanHalf   : false,
				items 			: null,
				matchIds 		: null,
				matches 		: null,
				leagues 		: null,
				matches_odds 	: null,
				matches_european_half_odds 	: null,
				label_dates : [],
				filter_leagues : [],
				options : '',
				api_url : 'http://localhost/PHP/caothu/api/',
			},
			methods: {
				get_matches(){
					this.isRunSchedule = true;
					
					axios
					.get(this.api_url + '?c=Schedule'+this.options)
					.then( response => {
						this.isRunSchedule = false;
						this.matchIds = response.data.matchIds;
						this.matches = response.data.matches;
						this.leagues = response.data.leagues;

						//this.get_matches_odds();
					});
				},
				get_matches_odds(){
					this.isRunningOdds = true;
					axios
					.get(this.api_url + '?c=Odds&matchIds='+this.matchIds+this.options)
					.then( response => {
						this.matches_odds = response.data.items;
						console.log('league_matches');
						this.get_matches_odds_european_half();
						//update get_matches_odds_european_half 
						this.canRunningEuropeanHalf = true;
					});
				},
				get_matches_odds_european_half(){
					if( this.matchIds.length == 0 ){
						return true;
					}
					this.isRunningEuropeanHalf = true;
					axios
					.get(this.api_url + '?c=Odds&a=european_half&&matchIds='+this.matchIds+this.options)
					.then( response => {
						this.matches_european_half_odds = response.data.items;
						this.isRunningEuropeanHalf = false;
						
						jQuery('#tyle_bong_da_hom_nay .leagueGroup').each( function(key,val){
							if( jQuery(val).find('.oddsContent').length == 0 ){
								jQuery(val).hide();
							}
						});
						
					});
				},

				get_next_days(){
					var startDay = new Date();
					//var thisDay = new Date();
					for(var i=0; i<4; i++) {
						let the_date = new Date( Date.now() + i * 24 * 60 * 60 * 1000)
						let dd = String(the_date.getDate()).padStart(2, '0');
						let mm = String(the_date.getMonth() + 1).padStart(2, '0'); //January is 0!
						let yyyy = the_date.getFullYear();

						let format_date = dd + '/' + mm;
						let filter_date = yyyy + '-' + mm + '-' + dd;
						let label_date = {
							'label': (i == 0) ? 'Hôm nay' : (i == 1) ? 'Ngày mai' : format_date,
							'checked': (i == 0) ? true : false,
							'format_date' : format_date,
							'filter_date' : filter_date,
						};
						this.label_dates.push( label_date );
					}
				},
				app_filter_date(date){
					this.options = '&date='+date;
					this.live_matches = null;
					this.live_matchIds = null;
					this.matchIds 		= null,
					this.matches 		= null,
					this.get_matches();
				},
				app_filter_league(event){
					this.filter_leagues = [event.target.value];
					this.options = this.options + '&leagues=' +  this.filter_leagues.join(',');
					this.matchIds 		= null,
					this.matches 		= null,
					this.get_matches();
				}
			},
			created() {
			
			},
			mounted () {
				this.get_matches();
				this.get_next_days();
			},
			beforeUpdate() {
			}
		});
	</script>
  </body>
</html>