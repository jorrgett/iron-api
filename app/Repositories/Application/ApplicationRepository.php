<?php

namespace App\Repositories\Application;

use App\Models\Application;
use App\Repositories\BaseInterface;
use Illuminate\Support\Facades\DB;



class ApplicationRepository implements BaseInterface
{

    protected $model;
    protected $application;

    /**
     * Application Repository constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->model = $application;
    }

    /**
     * Get all paginated odometers
     *
     * @param $data
     *
     */
    public function getAll($data)
    {
        $page = !empty($data['size']) ? (int)$data['size'] : 10;

        return $this->model::paginate($page);
    }

    /**
     * Application a newly created user in storage
     *
     * @param $data
     *
     */
    public function create(array $data)
    {
        return $this->model::create($data);
    }

    /**
     * Display the specified user by field.
     *
     * @param $data
     *
     */
    public function getByField($field, $value, $operator = '=')
    {
        return $this->model::where($field, $operator, $value)->first();
    }

    /**
     * Remove the specified user in storage
     *
     * @param $id
     */
    public function destroy($id)
    {
        $application = $this->getByField('id', $id);
        return !is_null($application) ? $application->delete() : True;
    }

    /**
     * Update the specified user in storage
     *
     * @param $id
     * @param array $data
     */
    public function UpdateById($id, array $data)
    {
        $application = $this->getByField('id', $id);
        $application->fill($data);
        $application->save();

        return $application;
    }


    /**
     * Get the latest enabled version for each platform
     *
     * @return \Illuminate\Support\Collection
     */
    public function getLatestEnabledVersionsByPlatform()
    {
        return $this->model::where('enable', true)
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('applications')
                    ->where('enable', true)
                    ->groupBy('platform');
            })
            ->get(['id', 'version AS latest_version', 'platform', 'enable', 'note', 'created_at', 'updated_at']);
    }

    /**
     * Get available versions for a platform
     *
     * @param $data
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableVersionsByPlatform($platform)
    {
        $versions = $this->model::where('platform', $platform)
            ->where('enable', true)
            ->get(['id', 'version', 'platform', 'enable', 'note', 'created_at', 'updated_at']);

        $sortedVersions = $versions->sort(function ($a, $b) {
            return version_compare($b->version, $a->version);
        });

        return $sortedVersions->values();
    }
}
