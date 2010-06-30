		<script src="script.js" type="text/javascript"></script>
		<script type="text/javascript">
			function calc_swimpace() 
			{
				swimtotal = document.getElementById ('swimtotal').value;
				swimdist = document.getElementById ('swimdist').value;
				var element = document.getElementById('swimpace');
				xmlhttp.open("GET", 'http://www.vanhlebarsoftware.com/sandbox/fitlogpace.php?time=' + swimtotal + '&distance=' + swimdist + '&activity=' + 'swim');
			
				xmlhttp.onreadystatechange = function()
				{
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
					{
						element.value = xmlhttp.responseText;
					}
				}
			
				xmlhttp.send(null);
			}

			function calc_bikepace() 
			{
				biketotal = document.getElementById ('biketotal').value;
				bikedist = document.getElementById ('bikedist').value;
				var element = document.getElementById('bikepace');
				xmlhttp.open("GET", 'http://www.vanhlebarsoftware.com/sandbox/fitlogpace.php?time=' + biketotal + '&distance=' + bikedist + '&activity=' + 'bike');
			
				xmlhttp.onreadystatechange = function()
				{
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
					{
						element.value = xmlhttp.responseText;
					}
				}
			
				xmlhttp.send(null);
			}
			function calc_runpace() 
			{
				runtotal = document.getElementById ('runtotal').value;
				rundist = document.getElementById ('rundist').value;
				var element = document.getElementById('runpace');
				xmlhttp.open("GET", 'http://www.vanhlebarsoftware.com/sandbox/fitlogpace.php?time=' + runtotal + '&distance=' + rundist + '&activity=' + 'run');
			
				xmlhttp.onreadystatechange = function()
				{
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
					{
						element.value = xmlhttp.responseText;
					}
				}
			
				xmlhttp.send(null);
			}
		</script>
	  <div id="wrap">
		<h2>Fitness Training Log</h2>
		<!-- BEGIN MESSAGEAREA -->
		<p>{MESSAGE}</p>	
		<!-- END MESSAGEAREA -->
		<!-- BEGIN FORM -->
	  	<form action="{ACTION}" method="post" class="swimbikerun">
		<!-- END FORM -->
		<!-- BEGIN WORKOUT -->
		  <div>
			<label for="date">Date: <span class="small">(YYYY-MM-DD)</span>:</label>
			<input type="text" name="date" id="date" value="{WORKOUTDATE}"/>
		  </div>
		<!-- END WORKOUT -->
		<div id="sbr">
		<!-- BEGIN SWIMAREA -->
		  <div id="swim">
		  <div id="heading">
			<h4>Swimming</h4>
		  </div>
		  <div>
			<label for="swimtime">Workout time <span class="small">(hh:mm:ss)</span>:</label>
			<input type="text" name="swimtime" id="swimtime" value="{TIMEOFDAY}"/>
		  </div>
		  <div>
			<label for="swimtotal">Total Time <span class="small">(hh:mm:ss)</span>:</label>
			<input type="text" name="swimtotal" id="swimtotal" value="{DURATION}"/>
		  </div>
		  <div>
			<label for="swimdist">Distance:</label>
			<input type="text" name="swimdist" id="swimdist" value="{DISTANCE}"/>
		  </div>
		  <div>
			<label for="swimpace">Pace:</label>
			<input type="text" name="swimpace" id="swimpace" value="{PACE}"/>
			<label for="swimtype"> </label>
			<select name="swimtype" id="swimtype" value="{TYPE}">
				<option>Min/100yds</option>
				<option>Min/100m</option>
			</select>
			<input type="button" value="Calc" OnClick="calc_swimpace();" />
		  </div>
		  <div>
			<label for="swimnotes">Notes:</label>
			<textarea id="swimnotes" name="swimnotes" value="{NOTES}" cols="26" rows="10"></textarea>
		  </div>
		  <div>
			<label for="swimhrmin">Min Hr:</label>
			<input type="text" name="swimhrmin" id="swimhrmin" value="{MINHR}"/>
		  </div>
		  <div>
			<label for="swimhravg">Avg Hr:</label>
			<input type="text" name="swimhravg" id="swimhravg" value="{AVGHR}"/>
		  </div>
		  <div>
			<label for="swimhrmax">Max Hr:</label>
			<input type="text" name="swimhrmax" id="swimhrmax" value="{MAXHR}"/>
		  </div>
		  <div>
			<label for="swimcalsburned">Calories Burned:</label>
			<input type="text" name="swimcalsburned" id="swimcalsburned" value="{CALORIES}"/>
		  </div>
		  </div>
		<!-- END SWIMAREA -->
		<!-- BEGIN BIKEAREA -->
		  <div id="bike">
		  <div id="heading">
			<h4>Biking</h4>
		  </div>
		  <div>
			<label for="biketime">Workout time <span class="small">(hh:mm:ss)</span>:</label>
			<input type="text" name="biketime" id="biketime" value="{TIMEOFDAY}"/>
		  </div>
		  <div>
			<label for="biketotal">Total Time <span class="small">(hh:mm:ss)</span>:</label>
			<input type="text" name="biketotal" id="biketotal" value="{DURATION}"/>
		  </div>
		  <div>
			<label for="bikedist">Distance:</label>
			<input type="text" name="bikedist" id="bikedist" value="{DISTANCE}"/>
		  </div>
		  <div>
			<label for="bikepace">Pace:</label>
			<input type="text" name="bikepace" id="bikepace" value="{PACE}"/>
			<label for="biketype"> </label>
			<select name="biketype" id="biketype" value="{TYPE}">
				<option>Miles/Hr</option>
				<option>KMs/Hr</option>
			</select>
			<input type="button" value="Calc" OnClick="calc_bikepace();" />
		  </div>
		  <div>
			<label for="bikenotes">Notes:</label>
			<textarea id="bikenotes" name="bikenotes" cols="26" rows="10" value="{NOTES}"></textarea>
		  </div>
		  <div>
			<label for="bikehrmin">Min Hr:</label>
			<input type="text" name="bikehrmin" id="bikehrmin" value="{MINHR}"/>
		  </div>
		  <div>
			<label for="bikehravg">Avg Hr:</label>
			<input type="text" name="bikehravg" id="bikehravg" value="{AVGHR}"/>
		  </div>
		  <div>
			<label for="bikehrmax">Max Hr:</label>
			<input type="text" name="bikehrmax" id="bikehrmax" value="{MAXHR}"/>
		  </div>
		  <div>
			<label for="bikerpms">Avg Rpms:</label>
			<input type="text" name="bikerpms" id="bikerpms" value="{RPMS}"/>
		  </div>
		  <div>
			<label for="bikecalsburned">Calories Burned:</label>
			<input type="text" name="bikecalsburned" id="bikecalsburned" value="{CALORIES}"/>
		  </div>
		  </div>
		<!-- END BIKEAREA -->
		<!-- BEGIN RUNAREA -->
		  <div id="run">
		  <div id="heading">
			<h4>Running</h4>
		  </div>
		  <div>
			<label for="runtime">Workout time <span class="small">(hh:mm:ss)</span>:</label>
			<input type="text" name="runtime" id="runtime" value="{TIMEOFDAY}"/>
		  </div>
		  <div>
			<label for="runtotal">Total Time <span class="small">(hh:mm:ss)</span>:</label>
			<input type="text" name="runtotal" id="runtotal" value="{DURATION}"/>
		  </div>
		  <div>
			<label for="rundist">Distance:</label>
			<input type="text" name="rundist" id="rundist" value="{DISTANCE}"/>
		  </div>
		  <div>
			<label for="runpace">Pace:</label>
			<input type="text" name="runpace" id="runpace" value="{PACE}"/>
			<label for="runtype"> </label>
			<select name="runtype" id="runtype" value="{TYPE}">
				<option>Min/Mile</option>
				<option>Min/KMs</option>
			</select>
			<input type="button" value="Calc" OnClick="calc_runpace();"/>
		  </div>
		  <div>
			<label for="runnotes">Notes:</label>
			<textarea id="runnotes" name="runnotes" cols="26" rows="10" value="{NOTES}"></textarea>
		  </div>
		  <div>
			<label for="runhrmin">Min Hr:</label>
			<input type="text" name="runhrmin" id="runhrmin" value="{MINHR}"/>
		  </div>
		  <div>
			<label for="runhravg">Avg Hr:</label>
			<input type="text" name="runhravg" id="runhravg" value="{AVGHR}"/>
		  </div>
		  <div>
			<label for="runhrmax">Max Hr:</label>
			<input type="text" name="runhrmax" id="runhrmax" value="{MAXHR}"/>
		  </div>
		  <div>
			<label for="runcalsburned">Calories Burned:</label>
			<input type="text" name="runcalsburned" id="runcalsburned" value="{CALORIES}"/>
		  </div>
		  </div>
		<!-- END RUNAREA -->
		</div>		<!-- END OF "sbr" div -->
		<!-- BEGIN BUTTONAREA -->
		<div id="submit_bottom">
			<input type="submit" value="{SUBMITVALUE}">
			<input type="reset" value="Reset" />
		</div>
		<!-- END BUTTONAREA -->
		</form>
	  </div> <!-- End of bodycontent div -->
