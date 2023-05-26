<?php

namespace App\Http\Livewire\Tenant\bdgo;

use App\Helpers\Helper;
use App\Models\Tenant\Bdgo\PaLegalBasisDeletionControl;
use App\Models\Tenant\Bdgo\PaLegalBasisDeletionLogs;
use App\Models\Tenant\Bdgo\PaLegalBasisDeletionProcess;
use App\Models\Tenant\Bdgo\PaLegalBasisDeletionType;
use App\Models\Tenant\Bdgo\ProcessingActivityLegalBasis;
use App\Models\Bdgo\DeletionControl;
use App\Models\Bdgo\DeletionLog;
use App\Models\Bdgo\DeletionProcess;
use App\Models\Bdgo\DeletionType;
use App\Models\Bdgo\Template;
use Livewire\Component;

class AddDeletionProcessComponent extends Component
{
    public $action = 'create';

    public $paLegalBasis, $paDeletionProcess;

    public $templates = [],
           $deletionProcesses = [],
           $deletionLogs = [],
           $deletionControls = [],
           $deletionTypes = [];

    public $processing_activity_legal_basis_id,
           $template_id,
           $process_description,
           $retention_period,
           $other_information,
           $deletion_log_ids = [],
           $deletion_type_ids = [],
           $deletion_control_ids = [];

    public $listeners = [
        'deletionProcessSelect',
        'deletionLogSelect',
        'deletionTypeSelect',
        'deletionControlledSelect'
    ];

    public function mount(ProcessingActivityLegalBasis $paLegalBasis, int $deletionProcessId = null)
    {
        $this->paLegalBasis = $paLegalBasis;

        if (empty($this->paLegalBasis)) {
            abort(404);
        }

        $this->processing_activity_legal_basis_id = $this->paLegalBasis->id;

        $this->templates = Template::customerType()->get();

        $this->deletionProcesses = DeletionProcess::customerType()->get();

        $this->deletionLogs = DeletionLog::all();

        $this->deletionControls = DeletionControl::all();

        $this->deletionTypes = DeletionType::all();

        if (!empty($deletionProcessId)) {
            $this->action = 'edit';

            $this->paDeletionProcess = PaLegalBasisDeletionProcess::find($deletionProcessId);

            if (empty($this->paDeletionProcess)) {
                abort(404);
            }

            $this->template_id = $this->paDeletionProcess->template_id;
            $this->process_description = $this->paDeletionProcess->process_description;
            $this->retention_period = $this->paDeletionProcess->retention_period;
            $this->other_information = $this->paDeletionProcess->other_information;
            $this->deletion_log_ids = $this->paDeletionProcess->paLegalBasisDeletionLogs()->get()->pluck('deletion_log_id');
            $this->deletion_type_ids = $this->paDeletionProcess->paLegalBasisDeletionTypes()->get()->pluck('deletion_type_id');
            $this->deletion_control_ids = $this->paDeletionProcess->paLegalBasisDeletionControls()->get()->pluck('deletion_control_id');
        }
    }

    public function render()
    {
        return view('livewire.tenant.bdgo.add-deletion-process-component');
    }

    public function hydrate($event)
    {
        $eventName = null;

        if (!empty($event->updates[0]['payload']['event'])) {
            $eventName = $event->updates[0]['payload']['event'];
        }

        if (str_contains($eventName, 'Select')) {
            $this->dispatchBrowserEvent($eventName . 'Emitter', ['eventName' => $eventName]);
        }
    }

    protected function rules()
    {
        return [
            'template_id'           => 'required|exists:' . Template::class . ',id',
            'process_description'   => 'required|string',
            'retention_period'      => 'nullable|max:255',
            'other_information'     => 'required|string|max:255',
            'deletion_log_ids'      => 'required|array',
            'deletion_type_ids'     => 'required|array',
            'deletion_control_ids'  => 'required|array',
            'processing_activity_legal_basis_id' => 'required|integer'
        ];
    }

    public function save()
    {
        $now = now();

        $validatedData = $this->validate();

        if ($this->action == 'create') {
            $createOrUpdate = PaLegalBasisDeletionProcess::create($validatedData);
        } else {
            $update = $this->paDeletionProcess->update($validatedData);

            if ($update) {
                $createOrUpdate = $this->paDeletionProcess->refresh();
            }
        }

        if ($createOrUpdate) {
            // Delete old entries.
            PaLegalBasisDeletionLogs::where('processing_activity_legal_basis_id', $this->processing_activity_legal_basis_id)->delete();
            PaLegalBasisDeletionType::where('processing_activity_legal_basis_id', $this->processing_activity_legal_basis_id)->delete();
            PaLegalBasisDeletionControl::where('processing_activity_legal_basis_id', $this->processing_activity_legal_basis_id)->delete();

            // Enter new entries.
            $paLegalBasisDeletionLogs = $paLegalBasisDeletionTypes = $paLegalBasisDeletionControls = [];

            foreach ($validatedData['deletion_log_ids'] as $deletionLogId) {
                $paLegalBasisDeletionLogs[] = [
                    'deletion_log_id'                    => $deletionLogId,
                    'processing_activity_legal_basis_id' => $this->processing_activity_legal_basis_id
                ];
            }

            foreach ($validatedData['deletion_type_ids'] as $deletionTypeId) {
                $paLegalBasisDeletionTypes[] = [
                    'deletion_type_id'                   => $deletionTypeId,
                    'processing_activity_legal_basis_id' => $this->processing_activity_legal_basis_id
                ];
            }

            foreach ($validatedData['deletion_control_ids'] as $deletionControlId) {
                $paLegalBasisDeletionControls[] = [
                    'deletion_control_id'                => $deletionControlId,
                    'processing_activity_legal_basis_id' => $this->processing_activity_legal_basis_id
                ];
            }

            if (!empty($paLegalBasisDeletionLogs)) {
                // Check is exists or insert.
                foreach ($paLegalBasisDeletionLogs as &$option) {
                    $checkId = (!empty($option['deletion_log_id'])) ? $option['deletion_log_id'] : false;

                    if (empty($checkId)) {
                        continue;
                    }

                    $find = DeletionLog::find($checkId);

                    if (empty($find)) {
                        $newOption = [
                            'log'        => $checkId,
                            'created_at' => $now
                        ];

                        $option['deletion_log_id'] = DeletionLog::insertGetId($newOption);
                    }
                }

                $createOrUpdate->paLegalBasisDeletionLogs()->insert($paLegalBasisDeletionLogs);
            }

            if (!empty($paLegalBasisDeletionTypes)) {
                // Check is exists or insert.
                foreach ($paLegalBasisDeletionTypes as &$option) {
                    $checkId = (!empty($option['deletion_type_id'])) ? $option['deletion_type_id'] : false;

                    if (empty($checkId)) {
                        continue;
                    }

                    $find = DeletionType::find($checkId);

                    if (empty($find)) {
                        $newOption = [
                            'type'       => $checkId,
                            'created_at' => $now
                        ];

                        $option['deletion_type_id'] = DeletionType::insertGetId($newOption);
                    }
                }

                $createOrUpdate->paLegalBasisDeletionTypes()->insert($paLegalBasisDeletionTypes);
            }

            if (!empty($paLegalBasisDeletionControls)) {
                // Check is exists or insert.
                foreach ($paLegalBasisDeletionControls as &$option) {
                    $checkId = (!empty($option['deletion_control_id'])) ? $option['deletion_control_id'] : false;

                    if (empty($checkId)) {
                        continue;
                    }

                    $find = DeletionControl::find($checkId);

                    if (empty($find)) {
                        $newOption = [
                            'control'    => $checkId,
                            'created_at' => $now
                        ];

                        $option['deletion_control_id'] = DeletionControl::insertGetId($newOption);
                    }
                }

                $createOrUpdate->paLegalBasisDeletionControls()->insert($paLegalBasisDeletionControls);
            }

            if ($this->action == 'create') {
                $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Processing Legal Basis Deletion Process Created Successfully!')]);
            } else {
                $this->dispatchBrowserEvent('showToastrSuccess', ['message' => __('locale.Processing Legal Basis Deletion Process Updated Successfully!')]);
            }

            $this->emit('loadDeletionProcesses');

            $this->emit('hideModal');
        } else {
            $this->dispatchBrowserEvent('showToastrError', ['message' => __('locale.Something wrong happen with your request.')]);
        }
    }

    public function deletionProcessSelect($value)
    {
        $this->template_id = $value;

        $this->autofillDepended();
    }

    public function deletionTypeSelect($value)
    {
        $this->deletion_type_ids = $value;
    }

    public function deletionControlledSelect($value)
    {
        $this->deletion_control_ids = $value;
    }

    public function deletionLogSelect($value)
    {
        $this->deletion_log_ids = $value;
    }

    public function autofillDepended()
    {
        if (!empty($this->deletionProcesses) && !$this->deletionProcesses->isEmpty()) {
            $this->reset('process_description', 'retention_period');

            foreach ($this->deletionProcesses as $deletionProcess) {
                if ($deletionProcess->template_id == $this->template_id) {
                    $this->process_description = $deletionProcess->process_description;

                    $this->retention_period    = Helper::parseMonthToYear($deletionProcess->month);

                    break;
                }
            }
        }
    }
}
