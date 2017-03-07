//Stop caching JSON
$.ajaxSetup({ cache: false });

//Close eventform when clicking outside box
$(document).click(function(event) { 
  if(!$(event.target).closest('#eventform').length) {
    if($('#form').is(':visible')) {
      $('#form').hide();
    }
  }				 
});

//vars
LABEL_DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday']; 
LABEL_MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

var d = new Date();

//Counters
var dayCounter = d.getDate(); 
if (dayCounter < 10)
  dayCounter = '0'+dayCounter;
var monthCounter = (d.getMonth()+1);
if (monthCounter < 10)
  monthCounter = '0'+monthCounter;
var yearCounter = d.getFullYear();

//Today
var dateID = yearCounter+''+monthCounter+''+dayCounter;

//Constructors
var gridMonths = function(){
  var self = {}
  self.grid = $('#gridMonths');
  self.month = $('#month');
  self.month.html(LABEL_MONTHS[monthCounter-1]+' '+yearCounter);

  self.update = function(){
    self.month.html(LABEL_MONTHS[monthCounter-1]+' '+yearCounter);
  }
  self.buttons = function(){
    $('#buttonLeft').on('click', goToPreviousMonth );
    $('#buttonRight').on('click', goToNextMonth );
  }
  var activateButtons = self.buttons();
  return self;
}

var gridDays = function(){
  var self = {};
  self.grid = $('#gridDays');
  self.createDaysLabels = function(){
    LABEL_DAYS.forEach(function(item){
      daysOfWeek = $('<div></div>');
      daysOfWeek.attr('class','dayOfTheWeek');
      day = $(document.createTextNode(item));
      daysOfWeek.append(day); 
      $('#gridDays').append(daysOfWeek);
    });
  }
  var daysLabel = self.createDaysLabels();
  return self;
}
var gridEvents = function(){
  var self = {}
  self.grid = $('#gridEvents');
  
  self.update = function(){
    self.draw();

  }
  self.draw = function(){
    var year = yearCounter;
    var month = monthCounter;
    var daysInMonth = new Date(year, month, 0).getDate();
    var firstDayBeginning = new Date(year, month-1, 1).getDay();

    if (firstDayBeginning == 0){
      for (i=0;i<6;i++){
        var days = $('<div></div>');
        days.attr('class', 'days Ndays');
        self.grid.append(days);
      }
    }
    while (firstDayBeginning > 1){
      var days = $('<div></div>');
      days.attr('class', 'days Ndays');
      self.grid.append(days);
      firstDayBeginning--;
    }

    for (i=1; i<=daysInMonth; i++){
      var days = $('<div></div>');
      days.attr('class', 'days');
      if (i<10){i='0'+i;}
      days.attr('id', year+''+month+''+i);
      self.grid.append(days);
      var day = document.createTextNode(i);
      days.append(day); 
    }
    checkForEvents(); 
    addEventForm();
  }
  var drawGrid = self.draw();

  return self;
}



function addEventForm(){
  //jquery
  $('.days').on('click',function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    if (id === undefined){
      return;
      console.log("id is undefined");
    } else{
      console.log(id);
      $('#form').fadeIn(300,function(){$(this).focus();});
      document.getElementById('eventId').value = id;

    }
  });
}

function checkForEvents(){
  $.getJSON('events.json', function(data) {							
    for (i=0;i<data.events.length;i++){
      $('#'+data.events[i].date).addClass('event');
      $('#'+data.events[i].date).addClass(data.events[i].color);
      $('#'+data.events[i].date).append('<p>'+ data.events[i].title+'</p>');
      $('#'+data.events[i].date).append('<p>'+ data.events[i].description+'</p>');
    }
  });
  $('#'+dateID).css('background-color','#272b33');
  $('#'+dateID).css('color','white');
  $('#gridEvents .days').each(function(){
    id=$(this).attr('id');
    if (id < dateID){
      $('#'+id).css('opacity','0.4');
    }
  });
  $('#datesGrid .Ndays').css('opacity','0.0');
  
}

function clean(){
  var container = document.getElementById('gridEvents');
  while (container.firstChild) { container.removeChild(container.firstChild); } 
}

function goToPreviousMonth(){ 
  clean();	 
  monthCounter--;
  monthCounter = '0'+monthCounter;
  if(monthCounter < 1){
    monthCounter = 12;
    yearCounter--;
  }
  updateAll();
}
function goToNextMonth(){ 
  clean();	 
  monthCounter++;
  monthCounter = '0'+monthCounter;
  if (monthCounter > 12){
    monthCounter = 01;
    yearCounter++;
  }
  updateAll();
}

document.addEventListener('keydown', keyDownTextField, false);
function keyDownTextField(e) {
  var keyCode = e.keyCode;
  if(keyCode==37) {		 
    goToPreviousMonth();
  } else if(keyCode==39){
    goToNextMonth();
  }
}
$('#calendar').on('swipeleft',function(){
  goToNextMonth();
});
$('#calendar').on('swiperight',function(){
  goToPreviousMonth();
});

$(document).ready(function() { 
  $('#eventform').submit(function(e){
    $('#form').fadeOut(500);
    $.ajax({
      method:'POST',
      url:'calendar.php',
      data: $('#eventform').serialize(),
      datatype:'json',
      success:function(){
        clean();
        updateAll();
      }
    });
    e.preventDefault();
    $(this).get(0).reset();
  });
});
function updateAll(){
  m.update();
  e.update();
}

var m = new gridMonths();
var d = new gridDays();
var e = new gridEvents();
