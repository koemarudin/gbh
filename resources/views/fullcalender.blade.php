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


{{-- <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#bookingModal">
  Launch demo modal
</button> --}}


    <!-- Modal -->
  <div class="modal fade" id="bookingModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Details Event</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            {{-- <span aria-hidden="true">{{ Auth::user()->role->name }}&times;</span> --}}
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
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="inputForm_acc">Form ACC</label>
                    <input type="file" class="form-control-file" id="input_form_acc" name="input_form_acc">
                  </div>
                  <div class="form-group col-md-4">
                    <select class="mb-2" id="input_clasification" name="input_clasification" onchange="yesnoCheck(this)">
                      <option value="Internal">Internal</option>
                      <option value="Eksternal">Eksternal</option>
                    </select>
                    <input type="text" class="mb-2" id="input_biaya" name="input_biaya" placeholder="Rp." style="display: none;">
                  </div>
                  <div class="form-group col-md-4" id="div_bukti_bayar" style="display: none;">
                    <label for="input_bukti_bayar">Bukti Bayar</label>
                    <input type="file" name="input_bukti_bayar" class="form-control" id="input_bukti_bayar">
                    <div class="img-holder"></div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" id="CloseBtn">Close</button>
                  <button type="button" class="btn btn-danger" id="deleteBtn">Delete</button>
                  <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
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
                    selectable: true,
                    selectHelper: true,
                    select: function (start, end, allDay) {
                        // $('#bookingModal').find('input_clasification').val("");
                        $('#bookingModal').find('input').val("");
                        $('#bookingModal').find('textarea').val("");
                        $('#bookingModal').modal('show');

                        // document.getElementById("input_start_date").value = date_format($date, 'Y-m-d H:i:s');
                        document.getElementById("input_start_date").value = moment(start).format('YYYY-MM-DD HH:m')+'1';
                        document.getElementById("input_end_date").value = moment(end).format('YYYY-MM-DD HH:mm');

                        // alert(moment(start).format('YYYY-MM-DD HH:mm'));

                        // $('#saveBtn').click(function()
                        // {
                        //     // var title = $('#input_title').val();
                        //     // var start = $('#input_start_date').val();
                        //     // var end = $('#input_end_date').val();
                        //     // var description = $('#input_description').val();
                        //     // console.log(start);


                        $.ajax({
                          type:"POST",
                        url: SITEURL + "/fullcalender",
                        data:formData,
                        success: function (response) {
                                    console.log(data);
                                    if (data.pesan=='Jadwal Tersebut Sudah Ada, silahkan pilih jadwal lainnya') {
                                        alert(data.pesan);
                                        return data;
                                    }
                                    alert(data.pesan);
                                    location.reload();
                                    $('#bookingModal').modal('hide');
                                  }
                        });




                        //     $.ajax({
                        //         url: SITEURL + "/fullcalenderAjax",
                        //         data: {
                        //             penyewa: $('#input_penyewa').val(),
                        //             title: $('#input_title').val(),
                        //             start: $('#input_start_date').val(),
                        //             end: $('#input_end_date').val(),
                        //             description: $('#input_description').val(),
                        //             clasification: $('#input_clasification').val(),
                        //             biaya: $('#input_biaya').val(),
                        //             bukti_bayar: $('#input_bukti_bayar').val(),
                        //             type: 'add'
                        //         },
                        //         type: "POST",
                        //         success: function (data) {
                        //             console.log(data);
                        //             if (data.pesan=='Jadwal Tersebut Sudah Ada, silahkan pilih jadwal lainnya') {
                        //                 alert(data.pesan);
                        //                 return data;
                        //             }
                        //             alert(data.pesan);
                        //             location.reload();
                        //             $('#bookingModal').modal('hide');
                        //             // swal("Good job!", "Event Deleted!", "success");
                        //             // displayMessage(data.pesan);
                        //             // calendar.fullCalendar('renderEvent',
                        //             // {
                        //             //     id: data.id,
                        //             //     title: title,
                        //             //     start: start,
                        //             //     end: end,
                        //             //     description: description,
                        //             //     allDay: allDay
                        //             //     },true);
  
                        //             calendar.fullCalendar('unselect');
                                    
                        //         }
                        //     });
                        //     return false;
                        // });

                        // Reset Input
                        $('input[type="file"][name="input_bukti_bayar"]').val('');
                        // Image Preview
                        $('input[type="file"][name="input_bukti_bayar"]').on('change',function(){
                          var img_path = $(this)[0].value;
                          var img_holder = $('.img-holder');
                          var extension = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();

                          // alert(extension);
                          if(extension=='jpeg'||extension=='jpg'||extension=='png'){
                            if(typeof(FileReader) != 'undefined'){
                              img_holder.empty();
                              var reader=new FileReader();
                              reader.onload=function(e){
                                $('<img/>',{'src':e.target.result,'class':'img-fluid','style':'max-width:250px;margin-bottom:10px;'}).appendTo(img_holder);
                              }
                              img_holder.show();
                              reader.readAsDataURL($(this)[0].files[0])
                            }
                            else{
                              $(img_holder).html('This browser does not support FileReader');
                            }
                          }
                            else{
                              $(img_holder).empty();
                            }
                        })


                    },
                    eventDrop: function (event, delta) {
                        var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                        var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
  
                        $.ajax({
                            url: SITEURL + '/fullcalenderAjax',
                            data: {
                                title: event.title,
                                start: start,
                                end: end,
                                id: event.id,
                                type: 'update'
                            },
                            type: "POST",
                            success: function (response) {
                                displayMessage("Event Updated Successfully");
                            }
                        });
                    },
                    eventClick: function (event) {
                        $('#bookingModal').find('input').val("");
                        document.getElementById("input_penyewa").value = event.penyewa;
                        document.getElementById("input_title").value = event.title;
                        document.getElementById("input_start_date").value = moment(event.start).format('YYYY-MM-DD HH:mm');
                        document.getElementById("input_end_date").value = moment(event.end).format('YYYY-MM-DD HH:mm');
                        document.getElementById("input_description").value = event.description;
                        document.getElementById("input_clasification").value = event.clasification;
                        document.getElementById("input_penyewa").value = event.penyewa;
                        document.getElementById("input_biaya").value = event.biaya;
                        $('#bookingModal').modal('show');
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