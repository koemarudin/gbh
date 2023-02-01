<!DOCTYPE html>
<html>
<head>
    <title>Laravel Fullcalender</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
      
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
<body>

    <!-- Modal -->
  <div class="modal fade" id="bookingModalView" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Details Event</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">


            <form method="POST" action="{{ url('fullcalender') }}" enctype="multipart/form-data">
              @csrf
                <label for="">Penyewa</label>
                <input type="text" class="form-control" id="input_penyewa" name="input_penyewa" required>
                
                <label for="">Kegiatan</label>
                <input type="text" class="form-control" id="input_title" name="input_title" required>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="">Start</label>
                    <input type="datetime-local" class="form-control rounded-1" name="start" id="input_start_date" required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="">End</label>
                    <input type="datetime-local" class="form-control rounded-1" name="end" id="input_end_date" required>
                  </div>
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="form-control" id="input_description" name="input_description" rows="5"></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" id="CloseBtn">Close</button>
                </div>
              </form>
       
        </div>

      </div>
    </div>
  </div>

  
<div class="container">
    <h1>Laravel FullCalender</h1>
    <div id='calendar'></div>
</div>

<script>

function yesnoCheck(that) 
{
    if (that.value == "Eksternal") 
    {
        document.getElementById("input_biaya").style.display = "block";
        document.getElementById("div_bukti_bayar").style.display = "block";
    }
    else
    {
        document.getElementById("input_biaya").style.display = "none";
        document.getElementById("div_bukti_bayar").style.display = "none";
    }
   
}

$(document).ready(function () {

  $('#CloseBtn').click(function(){location.reload()});
   
var SITEURL = "{{ url('/') }}";
  
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
  
var calendar = $('#calendar').fullCalendar({
                    editable: false,
                    events: SITEURL + "/fullcalender",
                    displayEventTime: false,  
                    color:'black',                 
                    nextDayThreshold: '00:00:00',
                    eventRender: function (event, element, view) {
                        if (event.allDay === 'true') {
                                event.allDay = true;
                        } else {
                                event.allDay = false;
                        }
                    },
                    selectable: false,
                    selectHelper: true,
                    
                    eventClick: function (event) {
                        $('#bookingModalView').find('input').val("");
                        document.getElementById("input_penyewa").value = event.penyewa;
                        document.getElementById("input_title").value = event.title;
                        document.getElementById("input_start_date").value = moment(event.start).format('YYYY-MM-DD HH:mm');
                        document.getElementById("input_end_date").value = moment(event.end).format('YYYY-MM-DD HH:mm');
                        document.getElementById("input_description").value = event.description;
                        $('#bookingModalView').modal('show');
                        $('#deleteBtn').click(function()
                        {
                        // $('#bookingModal').modal('hide');
                        var deleteMsg = confirm("Do you really want to delete?");
                        if (deleteMsg) {
                            $.ajax({
                                type: "POST",
                                url: SITEURL + '/fullcalenderAjax',
                                data: {
                                        id: event.id,
                                        type: 'delete'
                                },
                                success: function (response) {
                                    calendar.fullCalendar('removeEvents', event.id);
                                    // displayMessage("Event Deleted Successfully");
                                    location.reload();
                                }
                            });
                        }
                        });

                        $('#saveBtn').click(function()
                        {
                            $.ajax({
                                url: SITEURL + "/fullcalenderAjax",
                                data: {
                                    id: event.id,
                                    penyewa: $('#input_penyewa').val(),
                                    title: $('#input_title').val(),
                                    start: $('#input_start_date').val(),
                                    end: $('#input_end_date').val(),
                                    description: $('#input_description').val(),
                                    clasification: $('#input_clasification').val(),
                                    biaya: $('#input_biaya').val(),
                                    type: 'update'
                                },
                                type: "POST",
                                success: function (data) {
                                    console.log(data);
                                    if (data.pesan=='Jadwal Tersebut Sudah Ada, silahkan pilih jadwal lainnya') {
                                        alert(data.pesan);
                                        return data;
                                    }
                                    alert(data.pesan);
                                    location.reload();
                                    $('#bookingModal').modal('hide');
                                    calendar.fullCalendar('unselect');
                                    
                                }
                            });
                            return false;
                        });
                        
                    }
 
                });
 
});

function displayMessage(message) {
    toastr.success(message, 'Event');
} 
</script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>