Date.prototype.getWeek = function (dowOffset) {
  /*getWeek() was developed by Nick Baicoianu at MeanFreePath: http://www.meanfreepath.com */
  
      dowOffset = typeof(dowOffset) == 'number' ? dowOffset : 0; //default dowOffset to zero
      var newYear = new Date(this.getFullYear(),0,1);
      var day = newYear.getDay() - dowOffset; //the day of week the year begins on
      day = (day >= 0 ? day : day + 7);
      var daynum = Math.floor((this.getTime() - newYear.getTime() - 
        (this.getTimezoneOffset()-newYear.getTimezoneOffset())*60000)/86400000) + 1;
      var weeknum;
      //if the year starts before the middle of a week
      if(day < 4) {
        weeknum = Math.floor((daynum+day-1)/7) + 1;
        if(weeknum > 52) {
          nYear = new Date(this.getFullYear() + 1,0,1);
          nday = nYear.getDay() - dowOffset;
          nday = nday >= 0 ? nday : nday + 7;
              /*if the next year starts before the middle of
                the week, it is week #1 of that year*/
          weeknum = nday < 4 ? 1 : 53;
        }
      }
      else {
        weeknum = Math.floor((daynum+day-1)/7);
      }
      return weeknum;
    }
  
    function refresh(){
      var t = 1000; // rafraîchissement en millisecondes
      setTimeout('showDate()',t)
    }
    
    function showDate() {
      var date = new Date()
      var h = date.getHours();
      var m = date.getMinutes();
      var s = date.getSeconds();
      if( h < 10 ){ h = '0' + h; }
      if( m < 10 ){ m = '0' + m; }
      if( s < 10 ){ s = '0' + s; }
      var time = h + ':' + m + ':' + s
      document.getElementById('horloge').innerHTML = time;
      //Also show total hours
      showTotalHours();
  
      displayHoursByWeek();
  
      refresh();
  }
  
  //  Adding two times using hh:mm:ss format
    function addTimes (firstTime, secondTime) {
      var times = [ 0, 0, 0 ]
      var max = times.length
  
      var a = (firstTime || '').split(':')
      var b = (secondTime || '').split(':')
  
    // normalize time values
      for (var i = 0; i < max; i++) {
        a[i] = isNaN(parseInt(a[i])) ? 0 : parseInt(a[i])
        b[i] = isNaN(parseInt(b[i])) ? 0 : parseInt(b[i])
      }
  
    // store time values
      for (var i = 0; i < max; i++) {
        times[i] = a[i] + b[i]
      }
  
      var hours = times[0]
      var minutes = times[1]
      var seconds = times[2]
  
      if (seconds >= 60) {
        var m = (seconds / 60) << 0
        minutes += m
        seconds -= 60 * m
      }
      
      if (minutes >= 60) {
        var h = (minutes / 60) << 0
        hours += h
        minutes -= 60 * h
      }
  
      var formattedHour = ('0' + hours).slice(-2);
      var formattedMin = ('0' + minutes).slice(-2);
      var formattedSec = ('0' + seconds).slice(-2);
  
      return hours + ':' + formattedMin + ':' + formattedSec;
    }
  
    function showTotalHours(){
      let divTotalHours = document.getElementsByClassName("totalHours")[0];
  
      let tableLogsTotal = document.querySelector("table");
      let totalHr = "00:00:00";
      for (var i = 1, row; row = tableLogsTotal.rows[i]; i++) {
        let actualCell = row.cells[2].innerHTML;
        totalHr = addTimes(totalHr, actualCell);
      }
      divTotalHours.innerHTML = "Total du mois : " + totalHr;
    }
    
    function displayHoursByWeek(){
      let tableLogs = document.querySelector("table");
      let byHours = document.getElementById('byHours')
  
      if(byHours.children.length != 0){return}
  
  // Setting week number on all rows
      var byWeeks = [];
      for(let i = 1; i <= tableLogs.rows.length - 1; i++){
        var dateCell = new Date(tableLogs.rows[i].cells[0].innerText);
        var hourCell = tableLogs.rows[i].cells[3].innerText
        byWeeks.push({weekNumber: dateCell.getWeek(), totalHour: hourCell});
      }
  
  // Regrouping a new array all rows by weekNumber
      var sourceItemsByWeekNumber = {};
      for (const obj of byWeeks) {
        if (!sourceItemsByWeekNumber[obj.weekNumber]) {
          sourceItemsByWeekNumber[obj.weekNumber] = [];
        }
        sourceItemsByWeekNumber[obj.weekNumber].push(obj);
      }
      var groupedByWeek = Object.values(sourceItemsByWeekNumber);
  
  // Summs of all the hours grouped by date
      let hoursByWeek = [];
      for(let i = 0; i < groupedByWeek.length; i++){
        var totalOfWeek = "00:00:00";
        for(let j = 0; j < groupedByWeek[i].length; j++){
          totalOfWeek = addTimes(totalOfWeek, groupedByWeek[i][j].totalHour);
        }
        hoursByWeek.push(totalOfWeek);
      }
  
  // Displaying the result
      hoursByWeek.forEach(function callback(week, index){
        var node = document.createElement("li");
        var textnode = document.createTextNode("Semaine " + (index + 1) + " : " + week);
        node.appendChild(textnode);
        byHours.appendChild(node);
      })
    }