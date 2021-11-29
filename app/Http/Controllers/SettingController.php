<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use App\Models\LevelTerm;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\PaymentMethod;
use App\Models\PaymentAccount;
use App\Models\TermOfCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        $data = Setting::where('status',1)->orderBy('name')->get();
        return view('backend.setting.index',compact('data'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'value'=>'required|numeric',
            'password'=>'required'
        ]);

        $hasPassword = Hash::check($request->password, Auth::user()->password);
        if($hasPassword){
            Setting::find($request->id)->update(['value'=>$request->value]);
            return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Memperbaharui data']);
        }else{
            $request->session()->flash('failed', 'Gagal, Kata sandi salah');
            return redirect()->back();
        }
    }

    public function contact()
    {
        $data = Contact::orderBy('name')->get();
        return view('backend.setting.contact',compact('data'));
    }

    public function updateContact(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'nomor'=>'required|numeric',
            'password'=>'required'
        ]);

        $hasPassword = Hash::check($request->password, Auth::user()->password);
        if($hasPassword){
            Contact::find($request->id)->update(['value'=>$request->nomor]);
            return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Memperbaharui data']);
        }else{
            $request->session()->flash('failed', 'Gagal, Kata sandi salah');
            return redirect()->back();
        }
    }

    public function paymentMethod()
    {
        $data = PaymentMethod::orderBy('name')->get();
        return view('backend.setting.method',compact('data'));
    }

    public function updatePaymentMethod(Request $request)
    {
        $required = 'required';
        if($request->id){
            $required = 'nullable';
        }
        $this->validate($request, [
            'id' => 'nullable|integer|exists:payment_methods,id',
            'nama'=>'required|string|unique:payment_methods,name,'.$request->id,
            'logo' => $required.'|mimes:png,jpg,jpeg|max:2048',
            'password'=>'required'
        ]);

        $hasPassword = Hash::check($request->password, Auth::user()->password);
        if($hasPassword){
            $path = 'images/logo/';
            $filename = '';
            $file = $request->file('logo');
            if($file){
                $filename = time().$file->getClientOriginalName();
                $file->move($path,$filename);
            }
            if($request->id){
                $method = PaymentMethod::find($request->id);
                $dataUpdate = ['name'=>$request->nama];
                if($filename){
                    if($method->logo){
                        $dir = $path.$method->logo;
                        if(\file_exists($dir)){
                            unlink($dir);
                        }
                    }
                    $dataUpdate = ['name'=>$request->nama,'logo'=>$filename];
                }
                $method->update($dataUpdate);
                return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Memperbaharui data']);
            }else{
                PaymentMethod::create(['name'=>$request->nama,'logo'=>$filename]);
                return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Menambahkan data']);
            }
        }else{
            $request->session()->flash('failed', 'Gagal, Kata sandi salah');
            return redirect()->back();
        }
    }

    public function deletePaymentMethod(Request $request,$id)
    {
        $path = 'images/logo/';
        $method = PaymentMethod::find($id);
        if($method->logo){
            $dir = $path.$method->logo;
            if(\file_exists($dir)){
                unlink($dir);
            }
        }
        $method->delete();
        return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Menghapus data']);
    }

    public function paymentAccount()
    {
        $method = PaymentMethod::orderBy('name')->get();
        $data = PaymentAccount::orderBy('account_name')->get();
        return view('backend.setting.account',compact('data','method'));
    }

    public function updatePaymentAccount(Request $request)
    {
        $this->validate($request, [
            'id' => 'nullable',
            'tipe'=>'required|exists:payment_methods,id',
            'nama'=>'required|string',
            'nomor'=>'required|string',
            'password'=>'required'
        ]);

        $hasPassword = Hash::check($request->password, Auth::user()->password);
        if($hasPassword){
            if($request->id){
                PaymentAccount::find($request->id)->update([
                    'payment_method_id'=>$request->tipe,
                    'account_name'=>$request->nama,
                    'account_number'=>$request->nomor
                ]);
                return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Memperbaharui data']);
            }else{
                $check = PaymentAccount::where(['payment_method_id'=>$request->tipe])->first();
                if($check){
                    $request->session()->flash('failed', 'Gagal, Tipe Pembayaran sudah ada');
                    return redirect()->back();
                }
                PaymentAccount::create([
                    'payment_method_id'=>$request->tipe,
                    'account_name'=>$request->nama,
                    'account_number'=>$request->nomor
                ]);
                return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Menambahkan data']);
            }
        }else{
            $request->session()->flash('failed', 'Gagal, Kata sandi salah');
            return redirect()->back();
        }
    }

    public function level()
    {
        $data = Level::orderBy('level')->get();
        $term = LevelTerm::orderBy('step')->get();
        return view('backend.setting.level',compact('data','term'));
    }

    public function updateLevel(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'jumlah'=>'required|numeric',
            'koin'=>'required|integer',
            'kali'=>'required|integer',
            'terima'=>'required|integer',
            'password'=>'required'
        ]);

        $hasPassword = Hash::check($request->password, Auth::user()->password);
        if($hasPassword){
            Level::find($request->id)->update([
                'amount'=>$request->jumlah,
                'coin'=>$request->koin,
                'count'=>$request->kali,
                'count_r'=>$request->terima
            ]);
            return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Memperbaharui data']);
        }else{
            $request->session()->flash('failed', 'Gagal, Kata sandi salah');
            return redirect()->back();
        }
    }

    public function updateLevelTerm(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'jumlah'=>'required|numeric',
            'koin'=>'required|integer',
            'kali'=>'required|integer',
            'password'=>'required'
        ]);

        $hasPassword = Hash::check($request->password, Auth::user()->password);
        if($hasPassword){
            LevelTerm::find($request->id)->update([
                'amount'=>$request->jumlah,
                'coin'=>$request->koin,
                'count'=>$request->kali
            ]);
            return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Memperbaharui data']);
        }else{
            $request->session()->flash('failed', 'Gagal, Kata sandi salah');
            return redirect()->back();
        }
    }

    public function termOfCondition(Request $request)
    {
        $data = TermOfCondition::first();
        return view('backend.setting.termOfCondition',compact('data'));
    }

    public function updateTermOfCondition(Request $request)
    {
        $this->validate($request, [
            'aturan_sedekah' => 'required'
        ]);

        TermOfCondition::first()->update([
            'description'=>$request->aturan_sedekah,
        ]);
        return redirect()->back()->with(['flash_success' => true,'title' => 'Berhasil','message' => 'Memperbaharui data']);
    }
}
