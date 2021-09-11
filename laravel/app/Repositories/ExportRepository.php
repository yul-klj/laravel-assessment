<?php

namespace App\Repositories;

use App\Models\Export;

/**
 * Class ExportRepository
 *
 * @property string $type     Export Type
 * @property string $fields   Export Fields
 * @property string $location Export Location
 *
 * @package App\Repositories
 * @author Yul <yul_klj@hotmail.com>
 */
class ExportRepository
{
    /**
     * Load model into repository
     *
     * @return Export
     */
    protected function getModel()
    {
        return new Export();
    }

    /**
     * Get Export by id
     *
     * @param int $id Export id
     * @return Export
     */
    public function getById($id)
    {
        $exportModel = $this->getModel();

        return $exportModel::find($id);
    }

    /**
     * Create Export
     *
     * @param array $data Export data
     * @return Export
     * @throws \Exception
     */
    public function create(array $data)
    {
        $exportModel = $this->getModel();

        $exportModel->fill($data);
        $exportModel->save();

        return $exportModel;
    }

    /**
     * Update Export
     *
     * @param Export $export Export object
     * @param array  $data   data to be updated
     * @return Export
     * @throws \Exception
     */
    public function update(Export $export, array $data)
    {
        $export->fill($data);
        $export->save();

        return $export;
    }
}
