<?php
  
namespace App\Http\Controllers;
  
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\View\Components\Alert;

class FullCalenderController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */

     public function store(Request $request)
    {
        $file = time().'.'.$request->input_bukti_bayar->getClientOriginalExtension();
        $request->input_bukti_bayar->move('files',$file);
        // return view('fullcalender');
    }
    

    public function index(Request $request)
    {
      
      if($request->ajax()) {
       
        $data = Event::whereDate('start', '>=', $request->start)
                  ->whereDate('end',   '<=', $request->end)
                  ->get();
        return response()->json($data);

   }
     return view('fullcalender');
    }
 
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
      // dd($request);
      // $image = $request->file('input_bukti_bayar');
      // $image->move('files',"Joko.jpg");
      if($request->hasFile("input_form_acc"))
      {
        $file = time().'.'.$request->input_form_acc->getClientOriginalExtension();
        $request->input_form_acc->move('files',$file);
      }
      else{$file="";}
      if($request->hasFile("input_bukti_bayar"))
      {
        $file1 = time().'-1.'.$request->input_bukti_bayar->getClientOriginalExtension();
        $request->input_bukti_bayar->move('files',$file1);
      }
      else{$file1="";}
      

      $id=$request->id;
      $start=$request->start;
      $end=$request->end;    
      if ($request->input_clasification=='Internal') {
        $color='#f0aa0f ';
      }
      else
      $color='#ffa66a';  



      $pesan = DB::table('events')
      ->whereBetween('start', [$start,$end])
      ->orWhereBetween('end', [$start,$end])
      ->get();

      if (count($pesan) > 0) 
  {
    $pesan=['pesan'=>'Jadwal Tersebut Sudah Ada, silahkan pilih jadwal lainnya'];
    return view('fullcalender');
    return response()->json($pesan);
    dd($pesan);
  }        
          
          $event = Event::create([
              'penyewa' => $request->input_penyewa,
              'title' => $request->input_title,
              'start' => $request->start,
              'end' => $request->end,
              'description' => $request->input_description,
              // 'description' => $image,
              'clasification' => $request->input_clasification,
              'form_acc' => $file,
              'bukti_bayar' => $file1,
              'biaya' => $request->input_biaya,
              'admin' => Auth::user()->name,
              'color' => $color,
          ]);
          $pesan=['pesan'=>'Event Created Successfully'];
          return view('fullcalender');
    return response()->json($pesan);



        switch ($request->type) {
           case 'add':

               break;

            case 'update':
              $pesan = DB::table('events')
                      ->where('id','!=', $id)
                      ->where(function ($query)  use ($start,$end){
                $query->whereBetween('start', [$start,$end])
                      ->orWhereBetween('end', [$start,$end]);
                        })
                ->get();

                if (count($pesan) > 0) 
                {
                  $pesan=['pesan'=>'Jadwal Tersebut Sudah Ada, silahkan pilih jadwal lainnya'];
                  return response()->json($pesan);
                }

                $event = Event::find($request->id)->update([
                'penyewa' => $request->penyewa,
                'title' => $request->title,
                'start' => $request->start,
                'end' => $request->end,
                'description' => $request->description,
                'clasification' => $request->clasification,
                'biaya' => $request->biaya,
                'admin' => Auth::user()->name,
                'color' => $color,
              ]);
              $pesan=['pesan'=>'Event Updated Successfully'];
              return response()->json($pesan);
             break;
  
           case 'delete':
              $event = Event::find($request->id)->delete();
  
              return response()->json($event);
             break;
             
           default:
             # code...
             break;
        }
    }
}