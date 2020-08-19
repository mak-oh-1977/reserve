<?php include_once(__DIR__ . '/../common/common_head.php'); ?>

<!-- fullCalendar -->
<link rel="stylesheet" href="../plugins/fullcalendar/main.min.css">
<link rel="stylesheet" href="../plugins/fullcalendar-daygrid/main.min.css">
<link rel="stylesheet" href="../plugins/fullcalendar-timegrid/main.min.css">
<link rel="stylesheet" href="../plugins/fullcalendar-bootstrap/main.min.css">

<style type="text/css">
</style>


<?php include(__DIR__ . "/../common/common_body.php"); ?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">シフト設定</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div>
  </div>
  <!-- /.content-header -->

  <content>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <div class="sticky-top mb-3">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Draggable Events</h4>
              </div>
              <div class="card-body">
                <!-- the events -->
                <div id="external-events">
                  <div class="external-event bg-success">Lunch</div>
                  <div class="external-event bg-warning">Go home</div>
                  <div class="external-event bg-info">Do homework</div>
                  <div class="external-event bg-primary">Work on UI design</div>
                  <div class="external-event bg-danger">Sleep tight</div>
                  <div class="checkbox">
                    <label for="drop-remove">
                      <input type="checkbox" id="drop-remove">
                      remove after drop
                    </label>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Create Event</h3>
              </div>
              <div class="card-body">
                <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                  <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                  <ul class="fc-color-picker" id="color-chooser">
                    <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                    <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                  </ul>
                </div>
                <!-- /btn-group -->
                <div class="input-group">
                  <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                  <div class="input-group-append">
                    <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
                  </div>
                  <!-- /btn-group -->
                </div>
                <!-- /input-group -->
              </div>
            </div>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary">
            <div class="card-body p-0">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
    </div>
  </content>
</div>


<?php include_once(__DIR__ . '/../common/common_footer.php'); ?>

<!-- fullCalendar 2.2.5 -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/fullcalendar/main.min.js"></script>
<script src="../plugins/fullcalendar-daygrid/main.min.js"></script>
<script src="../plugins/fullcalendar-timegrid/main.min.js"></script>
<script src="../plugins/fullcalendar-interaction/main.min.js"></script>
<script src="../plugins/fullcalendar-bootstrap/main.min.js"></script>
<!-- Page specific script -->
<script src='../plugins/fullcalendar/locales/ja.js'></script>

<script type="text/javascript">
  //////////////////////////////////////////////////////////////////
  //
  //
  //
  $(document).ready(function() {
    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 1070,
          revert: true, // will cause the event to go back to its
          revertDuration: 0 //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d = date.getDate(),
      m = date.getMonth(),
      y = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendarInteraction.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    // initialize the external events
    // -----------------------------------------------------------------

    new Draggable(containerEl, {
      itemSelector: '.external-event',
      eventData: function(eventEl) {
        console.log(eventEl);
        return {
          title: eventEl.innerText,
          backgroundColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
          borderColor: window.getComputedStyle(eventEl, null).getPropertyValue('background-color'),
          textColor: window.getComputedStyle(eventEl, null).getPropertyValue('color'),
        };
      }
    });

    var calendar = new Calendar(calendarEl, {
      plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid'],
      locale: 'js',
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      'themeSystem': 'bootstrap',
      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar !!!
      eventReceive: function(info) {


        var param = {
          StaffId: getParm('staff_id'),
          Start: info.event.start.toLocaleString('ja-JP'),
          End: info.event.end.toLocaleString('ja-JP'),
        };
        Api('200_reserve_frame/add_reserve', param,
          function(ret) {
            info.event.setExtendedProp('EventId', ret['EventId']);
          }
        );

      },

      eventDragStop: function(info) {
        console.log(info.event);

      },
      eventDragStart: function(info) {
        console.log(info.event);
      },
      eventChange: function(info) {
        console.log(info.event);
      },
      eventSet: function(info) {
        console.log(info.event);
      },

      eventResizeStop: function(info) {
        console.log(info.event);
      },

      eventResize: function(info) {
        console.log(info.event);
        update_info(info);
      },

      eventDrop: function(info) {
        update_info(info);
      },

      events: function(info, successCallback, failureCallback) {
        console.log(info);
        var s = new Date(info.startStr);
        var param = {
          StaffId: getParm('staff_id'),
          Start: info.start.toLocaleString('ja-JP'),
          End: info.end.toLocaleString('ja-JP')
        };
        Api('200_reserve_frame/list_reserve', param,
          function(ret) {
            var a = [];
            for (var i = 0; i < ret['rows'].length; i++) {
              var r = ret['rows'][i];

              a.push({
                  title: 'test',
                  start: new Date(r['Start']),
                  end: new Date(r['End']),
                  backgroundColor: '#3c8dbc',
                  borderColor: '#3c8abc',
                  allDay: r['AllDay'] == 1 ? (true) : (false),
                  extendedProps: {
                    EventId: r['EventId'],
                  }
                }
              );
            }
            successCallback(a);
          }
        );
      },

      slotDuration: '00:10:00'
    });

    calendar.render();
    // $('#calendar').fullCalendar()

    function update_info(info) {
      //クリックしたイベントのタイトルが取れるよ
      var ev = calendar.getEventById(info.event.id);
      var param = {
        StaffId: getParm('staff_id'),
        EventId: info.event.extendedProps.EventId,
        Start: info.event.start.toLocaleString('ja-JP'),
        End: info.event.end == null ? (null) : (info.event.end.toLocaleString('ja-JP')),
      };
      Api('200_reserve_frame/update_reserve', param,
        function(ret) {}
      );
      console.log(info);

    }

  });
</script>



</body>

</html>