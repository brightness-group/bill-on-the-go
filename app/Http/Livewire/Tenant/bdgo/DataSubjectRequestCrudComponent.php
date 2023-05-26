<?php

namespace App\Http\Livewire\Tenant\bdgo;

use App\Helpers\Helper;
use App\Models\Tenant\Customer;
use Livewire\Component;
use App\Models\Bdgo\StatusType;
use App\Models\Bdgo\AffectedCategoryType;
use App\Models\Bdgo\RequestType;
use App\Models\Tenant\Bdgo\Request;
use App\Models\Tenant\Bdgo\RequestAffectedCategoryTypeId;
use App\Models\Tenant\Bdgo\Enums\PositivelyIdentifiedEnum;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use App\Models\Tenant\CustomerDocument;
use Tenancy\Facades\Tenancy;
use Livewire\WithFileUploads;

class DataSubjectRequestCrudComponent extends Component
{
    use WithFileUploads;

    public $document;

    public $action;

    public $doc_name = '';

    public $statuses;

    public $requestTypes;

    public $affectedCategories;

    // public $positivelyIdentifieds;

    public $randomRequestId;

    public $deadlineCycle;

    public $deadlineCycleDays = 0;

    public $positivelyIdentifiedBool = false;

    public $documents = [];
    public $iteration = 0;

    public $customer_id,
           $request_id,
           $status_type_id,
           $date_of_receipt,
           $request_type_id,
           $name,
           $email,
           $affected_category_type_ids = [],
           $request_text,
           $description,
           $positively_identified = '1',
           $identification_details;

    public $encryptedId, $editId;

    public $dateFormat = 'Y-m-d';

    protected $listeners = [
        'affectedCategorySelect',
        'requestTypeSelect',
        'statusSelect',
        // 'positivelyIdentifiedSelect',
        'destroy'
    ];

    protected function rules()
    {
        return [
            'request_id'                    => 'required|integer',
            'date_of_receipt'               => 'required|date',
            'name'                          => 'required|string',
            'email'                         => 'required|email',
            'request_text'                  => 'nullable|string',
            'description'                   => 'nullable|string',
            'positively_identified'         => 'required|in:' . implode(",", array_column(PositivelyIdentifiedEnum::cases(), 'value')),
            'identification_details'        => 'nullable|string',
            'affected_category_type_ids'    => 'required|array|exists:' . AffectedCategoryType::class . ',id',
            'request_type_id'               => 'required|exists:' . RequestType::class . ',id',
            'status_type_id'                => 'required|exists:' . StatusType::class . ',id',
            'customer_id'                   => 'nullable|exists:' . Customer::class . ',id'
        ];
    }

    public function mount($action = 'create')
    {
        $this->customer_id = Helper::getSelectedCustomerId();
        $this->loadDocumentData();
        if (empty($this->customer_id) && $action == 'create') {
            return redirect()->to(route('data-subject-request'))->with('type', 'error')->with('msg', __('locale.Something wrong happen with your request.'));
        }

        $this->statuses                 = StatusType::all();

        $this->requestTypes             = RequestType::customerType()->get();

        $this->affectedCategories       = AffectedCategoryType::customerType()->get();

        // $this->positivelyIdentifieds    = PositivelyIdentifiedEnum::cases();

        $this->request_id               = Request::getRandomRequestId();

        $this->action                   = $action;

        $this->dateFormat               = config('bdgo.date_format');

        $this->date_of_receipt          = now()->isoFormat($this->dateFormat);

        if ($action == 'edit') {
            $this->encryptedId  = request()->get('id');

            try {
                $this->editId   = (int)Crypt::decrypt($this->encryptedId);
            } catch(Exception $e) {}

            $request            = Request::find($this->editId);

            if (!empty($request)) {
                // Set default customer because customer should not be change according to Susann.
                $this->customer_id  = $request->customer_id;

                // Check request type is available for the selected customer type.
                $requestTypes  = $this->requestTypes->keyBy('id')->toArray();
                $requestTypeId = (!empty($requestTypes[$request->request_type_id]) ? $request->request_type_id : null);

                // Check affected category type is available for the selected customer type.
                $affectedCategories      = $this->affectedCategories->keyBy('id')->toArray();
                $affectedCategoryTypeIds = RequestAffectedCategoryTypeId::where('request_id', $this->editId)->pluck('affected_category_type_id')->toArray();

                foreach ($affectedCategoryTypeIds as $index => $affectedCategoryTypeId) {
                    if (empty($affectedCategories[$affectedCategoryTypeId])) {
                        unset($affectedCategoryTypeIds[$index]);
                    }
                }

                $this->customer_id                = $request->customer_id;
                $this->request_id                 = $request->request_id;
                $this->status_type_id             = $request->status_type_id;
                $this->date_of_receipt            = $request->date_of_receipt->isoFormat($this->dateFormat);
                $this->request_type_id            = $requestTypeId;
                $this->name                       = $request->name;
                $this->email                      = $request->email;
                $this->affected_category_type_ids = $affectedCategoryTypeIds;
                $this->request_text               = $request->request_text;
                $this->description                = $request->description;
                $this->positively_identified      = $request->positively_identified;
                $this->identification_details     = $request->identification_details;

                $this->positivelyIdentifiedBool   = ($request->positively_identified == '2' ? true : false);
            } else {
                abort(404);
            }
        }

        $this->updatedDateOfReceipt();
    }

    public function hydrate($event)
    {
        // $this->positivelyIdentifieds = PositivelyIdentifiedEnum::cases();

        $eventName = null;

        if (!empty($event->updates[0]['payload']['event'])) {
            $eventName = $event->updates[0]['payload']['event'];
        }

        if (str_contains($eventName, 'Select')) {
            $this->dispatchBrowserEvent($eventName . 'Emitter', ['eventName' => $eventName]);
        }
    }

    public function render()
    {
        $container = 'container';

        return view('livewire.tenant.bdgo.data-subject-request-crud-component')
                    ->extends('tenant.theme-new.layouts.layoutMaster', compact('container'))
                    ->section('content');
    }

    public function updatedDateOfReceipt()
    {
        if (empty($this->date_of_receipt) || strtotime($this->date_of_receipt) <= 0) {
            $this->reset(['deadlineCycle', 'deadlineCycleDays']);
        } else {
            $today                  = now()->parse(now()->format('Y-m-d'));

            $date                   = Carbon::parse($this->date_of_receipt);

            $this->deadlineCycle    = $date->clone()->addDays(Request::DEADLINE_CYCLE);

            if ($this->deadlineCycle->gte($today)) {
                if ($date->lte($today)) {
                    $this->deadlineCycleDays = $today->diffInDays($this->deadlineCycle);
                } else {
                    $this->deadlineCycleDays = $this->deadlineCycle->clone()->diffInDays($date);
                }
            }
        }
    }

    public function save()
    {
        $update = $create = false;

        $validatedData = $this->validate();

        $validatedData['date_of_receipt'] = Carbon::createFromIsoFormat($this->dateFormat, $this->date_of_receipt)->format('Y-m-d');

        if ($this->action == 'edit') {
            $request = Request::find($this->editId);

            if (!empty($request)) {
                $update = $request->update($validatedData);

                // Delete affected category types.
                RequestAffectedCategoryTypeId::where('request_id', $this->editId)->delete();

                // Add affected category types.
                foreach ($validatedData['affected_category_type_ids'] as $affectedCategoryTypeId) {
                    $insertAffectedCategoryTypeIds[] = [
                        'affected_category_type_id' => $affectedCategoryTypeId,
                        'request_id' => (int)Crypt::decrypt($request->id)
                    ];
                }

                $request->requestAffectedCategoryTypeIds()->insert($insertAffectedCategoryTypeIds);
            } else {
                abort(404);
            }
        } else {
            $create = Request::create($validatedData);

            $insertAffectedCategoryTypeIds = [];

            // Add affected category types.
            foreach ($validatedData['affected_category_type_ids'] as $affectedCategoryTypeId) {
                $insertAffectedCategoryTypeIds[] = [
                    'affected_category_type_id' => $affectedCategoryTypeId,
                    'request_id' => (int)Crypt::decrypt($create->id)
                ];
            }

            $create->requestAffectedCategoryTypeIds()->insert($insertAffectedCategoryTypeIds);
        }

        if ($update) {
            $this->emit('updateInitForm');

            return redirect()->to(route('data-subject-request'))->with('type', 'success')->with('msg', __('locale.Request Updated'));
        } elseif ($create) {
            $this->emit('updateInitForm');

            return redirect()->to(route('data-subject-request'))->with('type', 'success')->with('msg', __('locale.Request Created'));
        }

        return redirect()->to(route('data-subject-request'))->with('type', 'error')->with('msg', __('locale.Something wrong happen with your request.'));
    }

    public function affectedCategorySelect($value)
    {
        $this->affected_category_type_ids = $value;
    }

    public function requestTypeSelect($value)
    {
        $this->request_type_id = $value;
    }

    public function statusSelect($value)
    {
        $this->status_type_id = $value;
    }

    /* public function positivelyIdentifiedSelect($value)
    {
        $this->positively_identified = $value;
    } */

    public function triggerDelete($id)
    {
        $this->dispatchBrowserEvent('triggerDelete', ['id' => $id]);
    }

    public function destroy($id)
    {
        $id      = (int)Crypt::decrypt($id);

        $request = Request::find($id);

        if (!empty($request)) {
            $request->delete();
        }

        return redirect()->to(route('data-subject-request'))->with('type', 'success')->with('msg', __('locale.Request Deleted'));
    }

    public function updatedPositivelyIdentifiedBool($value)
    {
        if ($value) {
            $this->positively_identified = '2';
        } else {
            $this->positively_identified = '1';
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

    public function destroyDocument()
    {
        $document = CustomerDocument::query()->find($this->selectedItem);
        \Storage::disk('public')->delete('tenants/' . Tenancy::getTenant()->getTenantKey() . '/' .$document->file);
        $document->delete();
        $this->loadDocumentData();
        $this->closeDocumentModalDelete();
        $this->dispatchBrowserEvent('showToastrDelete', ['message' => __('locale.Document Deleted!')]);
    }

    public function loadDocumentData()
    {
        $this->documents = CustomerDocument::where('customer_id',$this->customer_id)->get();
    }

    public function toggleDocumentDiv()
    {
        if($this->customer_id){
            $this->cleanVars();
            $this->dispatchBrowserEvent('toggleDocumentDiv');
        }else{
            $this->dispatchBrowserEvent('showToastrError', ['message' => __('locale.No Customer')]);
        }
    }

    public function cleanVars()
    {
        $this->resetValidation();
        $this->document = null;
        $this->iteration++;
    }

    public function saveDocument()
    {

        $this->validate([
            'document' => ['required','mimes:xlsx,docx,pdf'],
        ]);

        $create['file'] = $this->document->storeAs('/customer/'.$this->customer_id.'/Documents', $this->document->getClientOriginalName(),'tenant');
        $create['customer_id'] = $this->customer_id;
        $create['user_id'] = auth()->user()->id;

        $customerDocument = CustomerDocument::create($create);
        if($customerDocument){
            $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Document Created!')]);
            $this->emitSelf('refresh');
            $this->loadDocumentData();
            $this->toggleDocumentDiv();
        }
    }

    public function cancelDocument()
    {
        $this->toggleDocumentDiv();
    }

    public function closeDocumentModalDelete()
    {
        $this->dispatchBrowserEvent('closeDocumentModalDelete');
    }

    public function downloadFile($modelId)
    {
        $document = CustomerDocument::find($modelId);
        return \Storage::disk('public')->download('tenants/' . Tenancy::getTenant()->getTenantKey() . '/' .$document->file);
    }
}
