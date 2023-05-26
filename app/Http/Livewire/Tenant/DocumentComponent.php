<?php

namespace App\Http\Livewire\Tenant;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tenant\Customer;
use App\Models\Tenant\CustomerDocument;
use Tenancy\Facades\Tenancy;

class DocumentComponent extends Component
{
    use WithFileUploads;

    public ?Customer $customer = null;
    public $iteration = 0;
    public $file;
    public $documents = [];
    public $doc_name = "";
    public $selectedItem;
    


    public $listeners = [
        'refresh' => '$refresh',
        'cleanVars'
    ];

    public function rules()
    {
        return [
            'file' => ['required','mimes:xlsx,docx,pdf'],
        ];
    }

    public function mount($customer)
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.tenant.document-component');
    }

    public function updated($properties)
    {
        $this->resetValidation();
        $this->validateOnly($properties, $this->rules());
    }

    public function loadData()
    {
        if ($this->customer) {
            $this->documents = $this->customer->documents()->get();
        }else{
            $this->documents = [];
        }
    }


    public function toggleDocumentDiv()
    {
        $this->cleanVars();
        $this->dispatchBrowserEvent('toggleDocumentDiv');
    }

    public function save()
    {
        $this->validate($this->rules());
        $customer_id = $this->customer->id;
        $create['file'] = $this->file->storeAs('/customer/'.$customer_id.'/Documents', $this->file->getClientOriginalName(),'tenant');
        $create['customer_id'] = $customer_id;
        $create['user_id'] = auth()->user()->id;
        
        $customerDocument = CustomerDocument::create($create);  
        if($customerDocument){
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Document Created!')]);
            $this->emitSelf('refresh');
            $this->loadData();
            $this->toggleDocumentDiv();
        }
    }

    public function selectItem($modelId, $action)
    {
        $this->selectedItem = $modelId;
        if($action == 'delete'){
            $document = CustomerDocument::find($modelId);
            $this->doc_name = (!empty($document)) ? basename($document->file, PATHINFO_FILENAME) : "";
            $this->dispatchBrowserEvent('openDocumentModalDelete');
        }
    }

    public function downloadFile($modelId)
    {
        $document = CustomerDocument::find($modelId);
        return \Storage::disk('public')->download('tenants/' . Tenancy::getTenant()->getTenantKey() . '/' .$document->file);
    }
    
    public function closeDocumentModalDelete()
    {
        $this->dispatchBrowserEvent('closeDocumentModalDelete');
    }

    public function destroy()
    {
        $document = CustomerDocument::query()->find($this->selectedItem);
        \Storage::disk('public')->delete('tenants/' . Tenancy::getTenant()->getTenantKey() . '/' .$document->file);
        $document->delete();
        $this->loadData();
        $this->emitSelf('refresh');
        $this->closeDocumentModalDelete();
        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Document Deleted!')]);
    }

    public function cancel()
    {
        $this->toggleDocumentDiv();
    }

    public function cleanVars()
    {
        $this->resetValidation();
        $this->file = null;
        $this->iteration++;
    }
}
