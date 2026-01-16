<?php

namespace App\Http\Livewire\Traits;

trait AlertMessage
{
    public function showToastr($type,$text,$redirect=true)
    {
        if($redirect)
            session()->flash($type,$text);
        else
            $this->dispatchBrowserEvent('toastr', ['type' => $type, 'msg' => $text]);
    }

    public function showModal($type,$title,$text)
    {
        $this->emit('swal:modal', [
            'type'  => $type,
            'title' => $title,
            'text'  => $text,
        ]);
    }

    public function showConfirmation($type,$title,$text,$confirmText,$method,$params=[])
    {
        $this->emit("swal:confirm", [
            'type'        => $type,
            'title'       => $title,
            'text'        => $text,
            'confirmText' => $confirmText,
            'method'      => $method,//'appointments:delete',
            'params'      => $params, // optional, send params to success confirmation
            'callback'    => '', // optional, fire event if no confirmed
        ]);
    }
}
