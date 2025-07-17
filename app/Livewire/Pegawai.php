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

    }
    public function render()
    {
        $dataPegawai = ModelsPegawai::orderBy('nama','asc')->paginate(5);
        return view('livewire.pegawai', compact('dataPegawai'));
    }
}
