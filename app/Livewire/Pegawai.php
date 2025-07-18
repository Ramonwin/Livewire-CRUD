<?php

namespace App\Livewire;

use App\Models\Pegawai as ModelsPegawai;
use Livewire\Component;
use Livewire\WithPagination;

class Pegawai extends Component
{
     //! bisa pake cara ini untuk mengeluarkan datanya langsung ke textbox
    // public $nama = 'Ramon';
    // public $email = 'ramon@gmail.com';
    // public $alamat = 'Bandung';
    //=============================
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $nama;
    public $email;
    public $alamat;
    public $updateData =false;
    public $employee_id;
    public $katakunci; // untuk searching
    public $employee_selected_id =[];// bulk delete checkbox


    public function store(){
        // $this->nama = 'Mr. ' . $this->nama;
        // $this->email = 'ramon@gmail.com';
        // $this->alamat = 'Bandung';

        $rules =[
            'nama' => "required",
            'email' => "required|email",
            'alamat' => "required",
        ];

        $pesan =[
            'nama.required'=>'Nama wajib diisi',
            'email.required'=>'Email wajib diisi',
            'email.email'=>'Format Email tidak sesuai',
            'alamat.required'=>'Alamat wajib diisi',
        ];

        $validated = $this->validate($rules,$pesan);
        ModelsPegawai::create($validated);
        session()->flash('message','Data berhasil disimpan');

        $this->clear();

    }

    public function edit($id){
        $data = ModelsPegawai::find($id);
        $this->nama = $data->nama;
        $this->email = $data->email;
        $this->alamat = $data->alamat;

        $this->updateData =true;
        $this->employee_id = $id;
    }

    public function update(){
        $rules =[
            'nama' => "required",
            'email' => "required|email",
            'alamat' => "required",
        ];

        $pesan =[
            'nama.required'=>'Nama wajib diisi',
            'email.required'=>'Email wajib diisi',
            'email.email'=>'Format Email tidak sesuai',
            'alamat.required'=>'Alamat wajib diisi',
        ];

        $validated = $this->validate($rules,$pesan);
        $data = ModelsPegawai::find($this->employee_id);
        $data->update($validated);
        session()->flash('message','Data berhasil di update');

        $this->clear();

    }

    public function clear(){
        $this->nama = '';
        $this->email = '';
        $this->alamat = '';
        $this->updateData =false;
        $this->employee_id = '';
        $this->employee_selected_id = [];
    }

    public function delete(){

        if($this->employee_id != ''){
            $id = $this->employee_id; //gunakan cara ini untuk menampung parameter $id & bisa digunakan dimanapun (public)
            ModelsPegawai::find($id)->delete();
        }
        if(count($this->employee_selected_id)){
            for($x=0; $x<count($this->employee_selected_id);$x++){
                ModelsPegawai::find($this->employee_selected_id[$x])->delete();
            }
        }
            session()->flash('message','Data berhasil di hapus');
            $this->clear();
    }

    public function delete_confirmation($id){
        if($id != ''){
            $this->employee_id = $id;
        }
    }

    public function render()
    {
        if($this->katakunci != null){
            $dataPegawai = ModelsPegawai::where('nama','like','%' . $this->katakunci . '%')
            ->orWhere('email','like','%' . $this->katakunci . '%')
            ->orWhere('alamat','like','%' . $this->katakunci . '%')
            ->orderBy('nama','asc')->paginate(5);
        }else{
            $dataPegawai = ModelsPegawai::where('nama','like','%' . $this->katakunci . '%')->orderBy('nama','asc')->paginate(5);
        }

        return view('livewire.pegawai', compact('dataPegawai'));
    }
}
