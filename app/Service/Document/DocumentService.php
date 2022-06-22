<?php


namespace App\Service\Document;


use App\Models\Table\FileTable;
use App\Models\Table\DocumentTable;
use App\Models\Table\DocumentRelatedTable;

use App\Service\AppService;
use App\Service\AppServiceInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentService extends AppService implements AppServiceInterface
{
    protected $fileTable;
    protected $documentRelated;

    public function __construct(
        DocumentTable $model,
        FileTable $fileTable,
        DocumentRelatedTable $documentRelated
    )
    {
        $this->fileTable = $fileTable;
        $this->documentRelated = $documentRelated;
        parent::__construct($model);
    }

    public function getAll($search = null,$year = null, $type = null, $program = null)
    {
        $result =   $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->when($type, function ($query, $type) {
                                    return $query->where('document_type_id', $type);
                                })
                                ->when($program, function ($query, $program) {
                                    return $query->where('program_id', $program);
                                })
                                ->get();

        $countAll       = $this->model->newQuery()->count();
        $countSelected  = $result->count();
        $countNew       = $this->model->newQuery()->whereMonth('created_at', date('m'))->count();

        return $this->sendSuccess([
            'countAll' => $countAll,
            'countSelected' => $countSelected,
            'countNew' => $countNew,
            'data' => $result,
        ]);
    }

    public function getPaginated($search = null,$year = null, $type = null, $program = null, $perPage = 15, $page = null)
    {
        $result  = $this->model->newQuery()
                                ->when($search, function ($query, $search) {
                                    return $query->where('name','like','%'.$search.'%');
                                })
                                ->when($year, function ($query, $year) {
                                    return $query->whereYear('created_at', $year);
                                })
                                ->when($type, function ($query, $type) {
                                    return $query->where('document_type_id', $type);
                                })
                                ->when($program, function ($query, $program) {
                                    return $query->where('program_id', $program);
                                })
                                ->orderBy('created_at','DESC')
                                ->paginate((int)$perPage, ['*'], null, $page);

        $countAll       = $this->model->newQuery()->count();
        $countSelected  = $result->count();
        $countNew       = $this->model->newQuery()->whereMonth('created_at', date('m'))->count();

        return $this->sendSuccess([
            'countAll' => $countAll,
            'countSelected' => $countSelected,
            'countNew' => $countNew,
            'data' => $result,
        ]);
    }

    public function getById($id)
    {
        $result = $this->model->newQuery()
            ->with('file')
            ->with('documentType')
            ->with('program')
            ->with('relatedFile.related.file')
            ->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $document = $this->model->newQuery()->create([
                'name'              =>  $data['name'],
                'slug'              =>  Str::slug($data['name']),
                'document_type_id'  =>  $data['document_type_id'],
                'program_id'        =>  $data['program_id'],
            ]);

            if (isset($data['document_related'])) {
                foreach($data['document_related'] as $doc) {
                    $this->documentRelated->newQuery()->create([
                        'document_id'            =>  $document->id,
                        'related_document_id'    =>  $doc,
                    ]);
                }
            }

            if (!empty($data['document_id'])) {
                $image = $this->fileTable->newQuery()->find($data['document_id']);
                $image->update([
                    'fileable_type' => get_class($document),
                    'fileable_id'   => $document->id,
                ]);
            }


            \DB::commit(); // commit the changes
            return $this->sendSuccess($document);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $document   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $document->name    =   $data['name'];
            $document->slug    =   Str::slug($data['name']);
            $document->document_type_id = $data['document_type_id'];
            $document->program_id = $data['program_id'];
            $document->save();

            $this->documentRelated->newQuery()->where('document_id', $id)->delete();
            foreach($data['document_related'] as $doc) {
                $this->documentRelated->newQuery()->create([
                    'document_id'            =>  $doc->id,
                    'related_document_id'    =>  $doc,
                ]);
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($document);
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
