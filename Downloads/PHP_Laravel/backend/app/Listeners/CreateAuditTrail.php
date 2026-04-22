<?php

namespace App\Listeners;

use App\Models\AuditLog;
use Illuminate\Database\Events\Saved;
use Illuminate\Database\Events\Updated;
use Illuminate\Database\Events\Deleted;

class CreateAuditTrail extends BaseListener
{
    protected $excludedModels = [
        'App\Models\AuditLog',
        'App\Models\Session',
        'App\Models\Cache'
    ];

    public function handleSaved(Saved $event)
    {
        $model = $event->model;
        $className = get_class($model);
        
        if (in_array($className, $this->excludedModels)) {
            return;
        }
        
        $isNew = !$model->exists;
        $action = $isNew ? 'created' : 'updated';
        
        $oldValues = $isNew ? null : $model->getOriginal();
        $newValues = $model->getAttributes();
        
        $this->log($model, $action, $oldValues, $newValues);
    }

    public function handleDeleted(Deleted $event)
    {
        $model = $event->model;
        $className = get_class($model);
        
        if (in_array($className, $this->excludedModels)) {
            return;
        }
        
        $this->log($model, 'deleted', $model->getAttributes(), null);
    }

    protected function log($model, $action, $oldValues, $newValues)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'resource' => $model->getTable(),
            'resource_id' => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public function subscribe($events)
    {
        $events->listen(Saved::class, [CreateAuditTrail::class, 'handleSaved']);
        $events->listen(Deleted::class, [CreateAuditTrail::class, 'handleDeleted']);
    }
}