<?php


namespace App\Service\Master\ServiceUnit;

use Illuminate\Support\Facades\Hash;

use App\Models\Entity\User;
use App\Models\Entity\Role;
use App\Models\Entity\ServiceUnit;

use App\Models\Table\ServiceUnitTable;
use App\Models\Table\FileTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;
use App\Service\FileUploadService;

use Illuminate\Database\Eloquent\Model;

class ServiceUnitService extends AppService implements AppServiceInterface
{
    public function __construct(
        ServiceUnitTable $model
    )
    {
        parent::__construct($model);

    }

    public function getAll($search = null)
    {
        $result =   $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->get();

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->orderBy('created_at','DESC')
                                ->paginate((int)$perPage, ['*'], null, $page);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {
            $unit = ServiceUnit::create([
                'name'       =>  $data['name'],
                'created_id' => \Auth::user()->id
            ]);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($unit);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $health  =  ServiceUnit::find($id);

        \DB::beginTransaction();

        try {

            $health->name          =   $data['name'];
            $health->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($health);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function delete($id)
    {
        $read   =   $this->model->newQuery()->find($id);
        try {
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }
}
