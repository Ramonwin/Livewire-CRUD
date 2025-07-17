<?php

namespace App\Livewire;

use App\Models\Pegawai as ModelsPegawai;
use Livewire\Component;
use Livewire\WithPagination;

class Pegawai extends Component
{
    //! cara langsung menampilakn data
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
    }

    public function delete(){
        $id = $this->employee_id; //gunakan cara ini untuk menampung parameter $id & bisa digunakan dimanapun (public)
        ModelsPegawai::find($id)->delete();
        session()->flash('message','Data berhasil di hapus');
        $this->clear();
    }

    public function delete_confirmation($id){
        $this->employee_id = $id;
    }

    public function render()
    {
        $dataPegawai = ModelsPegawai::orderBy('nama','asc')->paginate(5);
        return view('livewire.pegawai', compact('dataPegawai'));
    }
}
